<?php

namespace App\Http\Middleware;

use Session;
use User;
use Closure;
use Illuminate\Http\Request;

class ShareAccess
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @return mixed
     */
    public function handle(Request $request, Closure $next)
    {
        $u = User::getByUsername(Session::get('login_username'));
        if(isset($u)){
            if($u->isAdmin == 1 or $u->member_type == 3){
                //is admin or is agent    
            }else{
                return redirect('/sign-in');
            }
        }else{
            return redirect('/sign-in');
        }

        return $next($request);
    }
}
