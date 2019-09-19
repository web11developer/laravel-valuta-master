<?php

namespace App\Http\Controllers\Auth;

use App\Cities;
use App\Exchangers;
use App\User;
use Carbon\Carbon;
use Illuminate\Auth\Events\Registered;
use Illuminate\Contracts\Auth\Guard;
use Illuminate\Foundation\Auth\AuthenticatesUsers;
use Illuminate\Foundation\Auth\RegistersUsers;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Input;
use Illuminate\Support\Facades\Lang;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Redirect;
use Illuminate\Support\Facades\Validator;
use App\Http\Controllers\Controller;
use Illuminate\Foundation\Auth\ThrottlesLogins;

class AuthController extends Controller
{
    /*
    |--------------------------------------------------------------------------
    | Registration & Login Controller
    |--------------------------------------------------------------------------
    |
    | This controller handles the registration of new users, as well as the
    | authentication of existing users. By default, this controller uses
    | a simple trait to add these behaviors. Why don't you explore it?
    |
    */

  //  use ThrottlesLogins;

    use AuthenticatesUsers, RegistersUsers {
        AuthenticatesUsers::redirectPath insteadof RegistersUsers;
        AuthenticatesUsers::guard insteadof RegistersUsers;
    }
    /**
     * @var Guard $auth
     */
    protected $auth;

    /**
     * Create a new authentication controller instance.
     *
     * @param Guard $auth
     */
    public function __construct(Guard $auth)
    {
        $this->auth = $auth;
        $this->middleware('guest', ['except' => 'getLogout']);
    }

    /**
     * Get a validator for an incoming registration request.
     *
     * @param  array $data
     * @return \Illuminate\Contracts\Validation\Validator
     */
    protected function validator(array $data)
    {
//        UNCOMMENT ALL FOR DEACTIVATE REGISTER EXCHANGERS!
        /*$is_exchange = false;

        foreach ($data['exchange'] as $key => $ex) {
            if ($key == 'city') continue;
            if ($ex != '') $is_exchange = true;
        }*/

//        if ($is_exchange) {
        return Validator::make($data, [
            'exchange.title' => 'required|min:3|unique:exchangers,title',
            'exchange.city' => 'required|integer',
            'exchange.email' => 'email',
            'exchange.phones' => 'required|min:12',
            'exchange.address' => 'required|min:20',
            'username' => 'required|max:255|unique:users',
            'email' => 'required|email|max:255|unique:users',
            'password' => 'required|confirmed|min:6'
        ]);
//        } else {
//            return Validator::make($data, [
//                'username' => 'required|max:255|unique:users',
//                'email' => 'required|email|max:255|unique:users',
//                'password' => 'required|confirmed|min:6'
//            ]);
//        }
    }

    /**
     * @param Request $request
     * @return $this|\Illuminate\Http\RedirectResponse
     */
    public function postLogin(Request $request)
    {
        $login = $request->input('login');
        $login_type = filter_var($login, FILTER_VALIDATE_EMAIL) ? 'email' : 'username';
        $request->merge([$login_type => $login]);
        if ($login_type == 'email') {
            $this->validate($request, [
                'email' => 'required|email',
                'password' => 'required',
            ]);

            $credentials = [
                'confirmed' => 1,
                'email' => $request->get('email'),
                'password' => $request->get('password')
            ];
//            $credentials = $request->only('email', 'password');
        } else {
            $this->validate($request, [
                'username' => 'required',
                'password' => 'required',
            ]);

            $credentials = [
                'confirmed' => 1,
                'username' => $request->get('username'),
                'password' => $request->get('password')
            ];
//            $credentials = $request->only('username', 'password');
        }
        if ($this->guard()->attempt($this->credentials($request), $request->filled('remember'))) {
            if ($login_type == 'email') {
                $user = User::where('email', $credentials['email'])->first();
            } else {
                $user = User::where('username', $credentials['username'])->first();
            }

            if (str_is($user->expire, '0000-00-00 00:00:00')) return redirect()->intended($this->redirectPath());


            if ($user->expire >= Carbon::now() === true) {
                return redirect()->intended($this->redirectPath());
            } else {
                Auth::logout();
                Exchangers::where('is_visible', 1)->where('user_id', $user->id)->update(['is_visible' => 0]);
                return redirect($this->loginPath())
                    ->withInput($request->only('login', 'remember'))
                    ->withErrors([
                        'login' => 'Ошибка, время использования вышло',
                    ]);
            }
        } else {
            if ($login_type == 'email') {
                $user = User::where('email', $credentials['email'])->first();
            } else {
                $user = User::where('username', $credentials['username'])->first();
            }
           // dd([$user->password,md5($credentials['password']),$credentials['password']]);
            if ($user && Hash::check($credentials['password'], $user->password)) {
                $user->password = bcrypt($credentials['password']);
                $user->save();

                //dd($credentials);
                if ($this->guard()->attempt($this->credentials($request), $request->filled('remember'))) {
                    Auth::login($user);
                    return redirect()->intended($this->redirectPath());
                }
            }
        }

        return redirect($this->loginPath())
            ->withInput($request->only('login', 'remember'))
            ->withErrors([
                'login' => $this->getFailedLoginMessage(),
            ]);
    }


    public function getRegister()
    {
        $cities = Cities::pluck('name', 'id')->toArray();
        return view('auth.register', compact('cities'));
    }

    public function getVerify($confirm)
    {

        if (!$confirm) {
            return Redirect::to('/');
        }

        $user = User::whereConfirmationCode($confirm)->first();

        if (!$user) {
            return Redirect::to('/');
        }

        $user->confirmed = 1;
        $user->confirmation_code = null;
        $user->save();

        Auth::login($user);

        return Redirect::to('/')->with('message', 'Вы успешно прошли активацию!');
    }

    /**
     * Create a new user instance after a valid registration.
     *
     * @param  array $data
     * @return User
     */
    protected function create(array $data)
    {
        $is_exchange = false;

        foreach ($data['exchange'] as $key => $ex) {
            if ($key == 'city') continue;
            if ($ex != '') $is_exchange = true;
        }

        $confirmation_code = str_random(32);

        $user = User::create([
            'username' => $data['username'],
            'email' => $data['email'],
            'confirmation_code' => $confirmation_code,
            'password' => bcrypt($data['password']),
            'access' =>null,
            'expire'=> Carbon::now()->addYear()->format('Y-m-d H:i:s')
        ]);

        \Log::info('Send email');

        $to = 'valuta@portex.kz';
        $subject = 'Регистрация на сайте valuta.kz: ' . $data['username'];
        $message = 'Регистрация на сайте valuta.kz: ' . $data['username'];
        $headers = 'From: info@valuta.kz';

        mail($to, $subject, $message, $headers);

//        \Mail::send('emails.register', [], function ($message) use ($data) {
//            $message->to('valuta@portex.kz', 'Mig')->subject('Регистрация на сайте: ' . $data['username']);
//            $message->to('al.tolerant@gmail.com', 'Mig')->subject('Регистрация на сайте: ' . $data['username']);
//        });

//        if ($is_exchange) {
        $exchange = new Exchangers($data['exchange']);
        $exchange->user_id = $user->id;
        $exchange->save();
//        }

        return $user;
    }
    /**
     * Get the path to the login route.
     *
     * @return string
     */
    public function loginPath()
    {
        return property_exists($this, 'loginPath') ? $this->loginPath : '/auth/login';
    }

    /**
     * Get the failed login message.
     *
     * @return string
     */
    protected function getFailedLoginMessage()
    {
        return Lang::has('auth.failed')
            ? Lang::get('auth.failed')
            : 'These credentials do not match our records.';
    }

    /**
     * Handle a registration request for the application.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function register(Request $request)
    {
       // dd($request->all());
        $this->validator($request->all())->validate();

        event(new Registered($user = $this->create($request->all())));

        $this->guard()->login($user);

        return $this->registered($request, $user)
            ?: redirect($this->redirectPath());
    }

    /**
     * Get the login username to be used by the controller.
     *
     * @return string
     */
    public function username()
    {
        return 'username';
    }

    /**
     * Log the user out of the application.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    /**
     * Log the user out of the application.
     *
     * @return \Illuminate\Http\Response
     */
    public function getLogout()
    {
        Auth::logout();

        return redirect(property_exists($this, 'redirectAfterLogout') ? $this->redirectAfterLogout : '/');
    }
}
