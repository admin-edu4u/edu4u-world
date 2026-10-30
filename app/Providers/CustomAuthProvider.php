<?php

namespace App\Providers;
use Auth;

use App\Auth\CustomUserProvider;
use Illuminate\Support\ServiceProvider;


/**
 * CustomAuthProvider
 */
class CustomAuthProvider extends ServiceProvider {
    /**
     * Bootstrap any application services.
     * 
     * @return void
     */
    public function boot()
    {
        $this->app['auth']->extend('externalauthapi',function() {
            return new CustomUserProvider();
        });
        
    }

    /**
     * Register any application services.
     *
     * @return void
     */
    public function register()
    {
        //
    }

}