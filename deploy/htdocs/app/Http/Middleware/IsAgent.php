<?php

namespace App\Http\Middleware;

use Closure;
use Auth;
use Session;

class IsAgent
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
        if (!Auth::isAgent(Session::get('login_email'))) return redirect('/agent/dashboard');
        
        return $next($request);
    }
}
