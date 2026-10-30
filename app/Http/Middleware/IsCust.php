<?php

namespace App\Http\Middleware;

use Closure;
use Auth;
use Session;

class IsCust
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
        if (!Auth::isCust(Session::get('login_email'))) return redirect('/customer/dashboard');
        
        return $next($request);
    }
}
