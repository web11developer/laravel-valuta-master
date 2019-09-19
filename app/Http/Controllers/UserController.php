<?php namespace App\Http\Controllers;

use App\User;
use App\Http\Requests\UserRequest;
use Illuminate\Support\Facades\Auth;

class UserController extends Controller
{

    public function __construct()
    {
        view()->share('type', 'user');
        $this->middleware('auth');
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param $user
     * @return Response
     */
    public function edit(User $user)
    {
        if (Auth::user()->id != $user->id) {
            if (Auth::user()->admin != 1) {
                return redirect('/');
            }
        }

        return view('user.create_edit', compact('user'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param $user
     * @return Response
     */
    public function update(UserRequest $request, User $user)
    {


        if (Auth::user()->id != $user->id) {
            if (Auth::user()->admin != 1) {
                return redirect('/');
            }
        }

        $password = $request->password;
        $passwordConfirmation = $request->password_confirmation;

        if (!empty($password)) {
            if ($password === $passwordConfirmation) {
                $user->password = bcrypt($password);
            }
        }
        $data = $request->except('password', 'password_confirmation');
        $diff_exchange = $request->get('diff_exchange',0);
        $data['diff_exchange'] = $diff_exchange;


        $user->update($data);
        return redirect('/exchangers/'. $user->id)->with('message', 'Ваш профиль обновлен успешно');
    }
}
