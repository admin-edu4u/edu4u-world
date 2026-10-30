<?php

namespace App\Http\Controllers;
use Illuminate\Http\Request;
use App\Http\Requests;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\App;

use Session;
use Config;

class LanguageController extends Controller {

    /**
     * index
     */
     public function index($locale){
         Session::put('locale', $locale);
         App::setLocale($locale);

         return redirect()->back();
     }


    private function getAvailableAppLangArray() {
        $locales[''] = Lang::get('app.select_your_language');

        foreach (Config::get('app.locales') as $key => $value)
        {
            $locales[$key] = $value;
        }

        return $locales;
    }
}
