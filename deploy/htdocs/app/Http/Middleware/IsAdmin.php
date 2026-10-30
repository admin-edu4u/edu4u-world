<?php

namespace App\Http\Middleware;

use Closure;
use Auth;
use Session;

class IsAdmin
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
	
        if (!Auth::isLogin()) return redirect('/');
        if(Session::get('login_type') == 20) return redirect('/merchant/dashboard');
        if (!Auth::isAdmin(Session::get('login_email'))) return redirect('/admin/dashboard');

        
        return $next($request);
    }
}
