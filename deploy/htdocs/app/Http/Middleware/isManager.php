<?php

namespace App\Http\Middleware;

use Closure;
use Auth;
use Session;

class isManager
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
        if (!Auth::isManager(Session::get('login_email'))) return redirect('/manager/dashboard');        
        
        return $next($request);
    }
}
