<?php

namespace App\Http\Middleware;

use Closure;
use Auth;
use Session;

class AfterLogin
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @return mixed
     */
    public function handle($request, Closure $next)
    {

        if (Auth::isLogin()) {
            if (Auth::isAdmin(Session::get('login_email'))) return redirect('/admin/dashboard');
            return redirect('/');
        }
        
        return $next($request);
    }
}
