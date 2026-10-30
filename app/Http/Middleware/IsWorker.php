<?php

namespace App\Http\Middleware;

use Closure;
use Auth;
use Session;

class IsWorker
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
        if (!Auth::isWorker(Session::get('login_email'))) return redirect('/worker/dashboard');
        
        return $next($request);
    }
}
