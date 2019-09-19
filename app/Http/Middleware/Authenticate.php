<?php

namespace App\Http\Middleware;

use App\Exchangers;
use Carbon\Carbon;
use Closure;
use Illuminate\Auth\Middleware\Authenticate as Middleware;

class Authenticate extends Middleware
{
    /**
     * Get the path the user should be redirected to when they are not authenticated.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return string
     */
    protected function redirectTo($request)
    {
        if (! $request->expectsJson()) {
            return route('login');
        }
    }

    public function handle($request, Closure $next, ...$guards)
    {
        if ($request->user() != null) {
            if ($request->user()->expire != '0000-00-00 00:00:00' && $request->user()->expire >= Carbon::now() === false) {
                Exchangers::where('is_visible', 1)->where('user_id', $request->user()->id)->update(['is_visible' => 0]);
                $this->auth->logout();
                return redirect()->guest('auth/login');
            }
        }
        if ($this->auth->guest()) {
            if ($request->ajax()) {
                return response('Unauthorized.', 401);
            } else {
                return redirect()->guest('auth/login');
            }
        }

     return $next($request);

    }
}
