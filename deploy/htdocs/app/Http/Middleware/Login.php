<?php

namespace App\Http\Middleware;

use Auth;
use Closure;

use Illuminate\Support\Facades\Session;

class Login
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

        if (!Auth::isLogin()) 
        {
            Session::put('prev_page', $request->fullUrl());
            return redirect('/');
        }
        
        if (Session::has('prev_page')) {
            $prev_page = Session::get('prev_page');
            Session::forget('prev_page');
            return redirect($prev_page);
          } 

        return $next($request);
    }
}
