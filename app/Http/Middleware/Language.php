<?php
namespace App\Http\Middleware;

use Closure;
use Illuminate\Foundation\Application;
use Illuminate\Http\Request;
use Illuminate\Routing\Redirector;
use Illuminate\Support\Facades\App;
use Illuminate\Support\Facades\Config;
use Illuminate\Support\Facades\Session;

class Language {
    public function handle($request, Closure $next)
    {
			  // \Artisan::call('route:clear');
	    /*if($request->getRequestUri() == "/member-signin" and $request->header('host') == $fe_domain) {
				$request->headers->set('host', $be_domain);
				return \Redirect::to($request->fullUrl(), 301);
			} */
			
			/*$be_domain = "mpointreward.com";
			$fe_domain = "miserra.com.my";
			if($request->getRequestUri() == "/" and $request->header('host') == $be_domain) {
				 \Artisan::call('route:clear');
				return redirect('http://mpointreward.com/member-signin', 301);
				// $request->headers->set('host', $be_domain);
				// return \Redirect::to($request->fullUrl() . '/member-signin', 301);
			}*/

			/*if($request->getRequestUri() != "/logout"){
				if($request->getRequestUri() != "/" and $request->header('host') == $fe_domain){
					$u = \App\Gen::currUser();
					$domain = "";
					if(isset($u) and $u->member_type > 0) {
						$domain = $be_domain;	
					} else {
						$domain = $fe_domain;
					}	
					// dd($domain, $request->header('host'));
					if($domain != "" and $request->header('host') != $domain ) {
						$request->headers->set('host', $domain);
						return \Redirect::to($request->fullUrl(), 301);
					}
				}
			}*/
			
	
        if (Session::has('locale')) {
            App::setLocale(Session::get('locale'));
        } else { //fall back language
            App::setLocale(Config::get('app.fallback_locale'));
        }

        return $next($request);
    }
}