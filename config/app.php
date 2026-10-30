<?php

return [

    'api_key' => 'Yk(GYvBc@9AyWpi3JZOY',

    'merchant_code' => 'M16109',
    'merchant_key' => 'lULCf1h60a',

		'bank_name' => 'WHC Marketing Sdn Bhd ',
		'bank_acc_no' => 'Maybank 514440522620',

    'is_dev' => true,

    /*
    |--------------------------------------------------------------------------
    | Application Name
    |--------------------------------------------------------------------------
    |
    | This value is the name of your application. This value is used when the
    | framework needs to place the application's name in a notification or
    | any other location as required by the application or its packages.
    |
    */

    'name' => env('APP_NAME', 'Edu360'),

    'web_domain' => 'https://zcard.com/',

    /***********************************************************************
     * SenangPay
     ***********************************************************************/
     //sandbox
     'senang_secret_key' => '2691-481',
     'senang_member_id' => '949159487967458',
     'senang_url' => 'https://sandbox.senangpay.my/payment/949159487967458',

     //live
     // 'senang_secret_key' => '2691-481',
     // 'senang_member_id' => '949159487967458',
     // 'senang_url' => 'https://sandbox.senangpay.my/payment/949159487967458',
    /*

    /***********************************************************************
       * billplz
       ***********************************************************************/
      'billplz_secret' => '54e5c545-272c-477c-88c4-ee87d33ff867',
      'billplz_x_sign' => 'S-a1sx79DGzmGzCWXS7Fk4dA',
      'billplz_collection' => 'pxs8t5qt',

      //sanbox
      // 'billplz_secret' => '9164f475-fccb-49d7-ab20-28b7f4141f7b',
      // 'billplz_x_sign' => 'S-vpHubDTwm-N1wEFXQEP8IA',
      // 'billplz_collection' => 'u03aggdg',
    /***********************************************************************
     * Easy Parcel
     ***********************************************************************/
     'ez_url_sand' => 'http://demo.connect.easyparcel.my/?ac=',
     'ez_api' => 'EP-bJjtR6R0Z',
     'ez_auth_sand' => 'fdePlQYfuv', // sandbbox

     /***********************************************************************
      * ISMS
      ***********************************************************************/

     'isms_username' => 'phplaravel-1186796-4174692.cloudwaysapps.com',
     'isms_pwd' => 'Edu360,112233',
     'isms_url' => 'https://www.isms.com.my/isms_send_all_id.php',
     'isms_sender' => 'Edu360',

     // 'isms_username' => 'bullmart',
     // 'isms_pwd' => 'bullmart123321',
     // 'isms_url' => 'https://www.isms.com.my/isms_send.php',
     // 'isms_sender' => 'Bullmart',

     /*

    |--------------------------------------------------------------------------
    | Application Environment
    |--------------------------------------------------------------------------
    |
    | This value determines the "environment" your application is currently
    | running in. This may determine how you prefer to configure various
    | services your application utilizes. Set this in your ".env" file.
    |
    */

    'env' => env('APP_ENV', 'production'),

    /*
    |--------------------------------------------------------------------------
    | Application Debug Mode
    |--------------------------------------------------------------------------
    |
    | When your application is in debug mode, detailed error messages with
    | stack traces will be shown on every error that occurs within your
    | application. If disabled, a simple generic error page is shown.
    |
    */

    'debug' => env('APP_DEBUG', false),

    /*
    |--------------------------------------------------------------------------
    | Application URL
    |--------------------------------------------------------------------------
    |
    | This URL is used by the console to properly generate URLs when using
    | the Artisan command line tool. You should set this to the root of
    | your application so that it is used when running Artisan tasks.
    |
    */

    'url' => env('APP_URL', 'http://localhost'),

    /*
    |--------------------------------------------------------------------------
    | Application Timezone
    |--------------------------------------------------------------------------
    |
    | Here you may specify the default timezone for your application, which
    | will be used by the PHP date and date-time functions. We have gone
    | ahead and set this to a sensible default for you out of the box.
    |
    */

    'timezone' => 'Asia/Kuala_Lumpur',

    /*
    |--------------------------------------------------------------------------
    | Application Locale Configuration
    |--------------------------------------------------------------------------
    |
    | The application locale determines the default locale that will be used
    | by the translation service provider. You are free to set this value
    | to any of the locales which will be supported by the application.
    |
    */

    'locale' => 'en',

    'locales' => ['en' => 'English', 'ch' => 'Chinese', 'bm' => 'Bahase Malaysia'],

    /*
    |--------------------------------------------------------------------------
    | Application Fallback Locale
    |--------------------------------------------------------------------------
    |
    | The fallback locale determines the locale to use when the current one
    | is not available. You may change the value to correspond to any of
    | the language folders that are provided through your application.
    |
    */

    'fallback_locale' => 'en',

    /*
    |--------------------------------------------------------------------------
    | Encryption Key
    |--------------------------------------------------------------------------
    |
    | This key is used by the Illuminate encrypter service and should be set
    | to a random, 32 character string, otherwise these encrypted strings
    | will not be safe. Please do this before deploying an application!
    |
    */

    'key' => env('APP_KEY'),

    'cipher' => 'AES-256-CBC',

    /*
    |--------------------------------------------------------------------------
    | Logging Configuration
    |--------------------------------------------------------------------------
    |
    | Here you may configure the log settings for your application. Out of
    | the box, Laravel uses the Monolog PHP logging library. This gives
    | you a variety of powerful log handlers / formatters to utilize.
    |
    | Available Settings: "single", "daily", "syslog", "errorlog"
    |
    */

    'log' => env('APP_LOG', 'daily'),

    'log_level' => env('APP_LOG_LEVEL', 'debug'),

    /***********************************************************************
     * Captcha
     ***********************************************************************/
    'secret_key' => env('NOCAPTCHA_SECRET'),

    'site_key' => env('NOCAPTCHA_SITEKEY'),

    /*
    |--------------------------------------------------------------------------
    | Autoloaded Service Providers
    |--------------------------------------------------------------------------
    |
    | The service providers listed here will be automatically loaded on the
    | request to your application. Feel free to add your own services to
    | this array to grant expanded functionality to your applications.
    |
    */

    'providers' => [

        /*
         * Laravel Framework Service Providers...
         */
        Illuminate\Auth\AuthServiceProvider::class,
        Illuminate\Broadcasting\BroadcastServiceProvider::class,
        Illuminate\Bus\BusServiceProvider::class,
        Illuminate\Cache\CacheServiceProvider::class,
        Illuminate\Foundation\Providers\ConsoleSupportServiceProvider::class,
        Illuminate\Cookie\CookieServiceProvider::class,
        Illuminate\Database\DatabaseServiceProvider::class,
        Illuminate\Encryption\EncryptionServiceProvider::class,
        Illuminate\Filesystem\FilesystemServiceProvider::class,
        Illuminate\Foundation\Providers\FoundationServiceProvider::class,
        Illuminate\Hashing\HashServiceProvider::class,
        Illuminate\Mail\MailServiceProvider::class,
        Illuminate\Notifications\NotificationServiceProvider::class,
        Illuminate\Pagination\PaginationServiceProvider::class,
        Illuminate\Pipeline\PipelineServiceProvider::class,
        Illuminate\Queue\QueueServiceProvider::class,
        Illuminate\Redis\RedisServiceProvider::class,
        Illuminate\Auth\Passwords\PasswordResetServiceProvider::class,
        Illuminate\Session\SessionServiceProvider::class,
        Illuminate\Translation\TranslationServiceProvider::class,
        Illuminate\Validation\ValidationServiceProvider::class,
        Illuminate\View\ViewServiceProvider::class,

        /*
         * Package Service Providers...
         */


        /*
         * Application Service Providers...
         */
        App\Providers\AppServiceProvider::class,
        App\Providers\AuthServiceProvider::class,
        // App\Providers\BroadcastServiceProvider::class,
        App\Providers\EventServiceProvider::class,
        App\Providers\RouteServiceProvider::class,
        App\Providers\CustomAuthProvider::class,
        SimpleSoftwareIO\QrCode\QrCodeServiceProvider::class,
        Jenssegers\Agent\AgentServiceProvider::class,

        //Anhskohbo\NoCaptcha\NoCaptchaServiceProvider::class,
        //Maatwebsite\Excel\ExcelServiceProvider::class,
        //Gloudemans\Shoppingcart\ShoppingcartServiceProvider::class,

    ],

    /*
    |--------------------------------------------------------------------------
    | Class Aliases
    |--------------------------------------------------------------------------
    |
    | This array of class aliases will be registered when this application
    | is started. However, feel free to register as many as you wish as
    | the aliases are "lazy" loaded so they don't hinder performance.
    |
    */

    'aliases' => [

        'App' => Illuminate\Support\Facades\App::class,
        'Artisan' => Illuminate\Support\Facades\Artisan::class,
        'Auth' => Illuminate\Support\Facades\Auth::class,
        'Blade' => Illuminate\Support\Facades\Blade::class,
        'Broadcast' => Illuminate\Support\Facades\Broadcast::class,
        'Bus' => Illuminate\Support\Facades\Bus::class,
        'Cache' => Illuminate\Support\Facades\Cache::class,
        'Config' => Illuminate\Support\Facades\Config::class,
        'Cookie' => Illuminate\Support\Facades\Cookie::class,
        'Crypt' => Illuminate\Support\Facades\Crypt::class,
        'DB' => Illuminate\Support\Facades\DB::class,
        'Eloquent' => Illuminate\Database\Eloquent\Model::class,
        'Event' => Illuminate\Support\Facades\Event::class,
        'File' => Illuminate\Support\Facades\File::class,
        'Gate' => Illuminate\Support\Facades\Gate::class,
        'Hash' => Illuminate\Support\Facades\Hash::class,
        'Lang' => Illuminate\Support\Facades\Lang::class,
        'Log' => Illuminate\Support\Facades\Log::class,
        'Mail' => Illuminate\Support\Facades\Mail::class,
        'Notification' => Illuminate\Support\Facades\Notification::class,
        'Password' => Illuminate\Support\Facades\Password::class,
        'Queue' => Illuminate\Support\Facades\Queue::class,
        'Redirect' => Illuminate\Support\Facades\Redirect::class,
        'Redis' => Illuminate\Support\Facades\Redis::class,
        'Request' => Illuminate\Support\Facades\Request::class,
        'Response' => Illuminate\Support\Facades\Response::class,
        'Route' => Illuminate\Support\Facades\Route::class,
        'Schema' => Illuminate\Support\Facades\Schema::class,
        'Session' => Illuminate\Support\Facades\Session::class,
        'Storage' => Illuminate\Support\Facades\Storage::class,
        'URL' => Illuminate\Support\Facades\URL::class,
        'Validator' => Illuminate\Support\Facades\Validator::class,
        'View' => Illuminate\Support\Facades\View::class,
        // 'Excel'     => Maatwebsite\Excel\Facades\Excel::class,
        //'Cart' => Gloudemans\Shoppingcart\Facades\Cart::class,
        'QrCode' => SimpleSoftwareIO\QrCode\Facades\QrCode::class,
				'Pusher' => Pusher\Pusher::class,
				'PushNotifications' => Pusher\PushNotifications\PushNotifications::class,
        //'NoCaptcha' => Anhskohbo\NoCaptcha\Facades\NoCaptcha::class,
    ],

];
