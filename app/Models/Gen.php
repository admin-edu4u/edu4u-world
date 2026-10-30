<?php

namespace App\Models;

use Carbon\Carbon;
use Request;

use Session;
use Lang;

/**
 * Generic Function
 */
class Gen {

    /**
     * get current local time
     */
    public static function currTime() {
        $dt = Carbon::now();
        $dt->setTimezone('Asia/Kuala_Lumpur');

        return $dt;
    }

    /**
     * get current language
     */
    public static function currLang() {
        if(Session::get('locale') == 'en')
            return Lang::get('lang.en');
        elseif(Session::get('locale') == 'bm')
            return Lang::get('lang.bm');
        else
            return Lang::get('lang.ch'); 
    }


    /**
     * dtFull
     */    
    public static function dtFull($dt) {
        return Carbon::parse(trim($dt))->format('d M Y');
    }


    /**
     * dtFullMonthYear
     */    
    public static function dtFullMonthYear($dt) {
        return Carbon::parse(trim($dt))->format('M Y');
    }


    /**
     * currDate
     */    
    public static function currDate() {
        $dt = Gen::currTime();
        return Carbon::parse($dt)->format('Y-m-d');
    }

    
    /**
     * dfFormatSlash
     */
    public static function dtDMYSlash($dt) {
        return Carbon::parse($dt)->format('d/m/Y g:i');
    }

    
    /**
     * dfFormat
     */
    public static function dtDMY($dt) {
        return Carbon::parse($dt)->format('d-m-Y g:i:s a');
    }
    
    
    /**
     * dfFormat
     */
    public static function dtDMYNoTime($dt) {
        return Carbon::parse($dt)->format('d-m-Y');
    }

    /**
     * dfFormat
     */
    public static function dtdMYHi($dt) {
        return Carbon::parse($dt)->format('d M Y H:i');
    }
    
    
    /**
     * Y-m-d
     */
    public static function dtYMD($dt) {
        return Carbon::parse(trim($dt))->format('Y-m-d');
    }


    /**
     * verifyCaptcha
     */    
    public static function verifyCaptcha($captcha) {
        $secretKey = config('app.secret_key');
        $url = "https://www.google.com/recaptcha/api/siteverify?secret=".$secretKey."&response=".$captcha;

        $res = file_get_contents($url);
        $key = json_decode($res, true);
        if ($key["success"] == true) {
            return 1;
        } else {
            return 0;
        }

                //$client = new \GuzzleHttp\Client();
        //$response = $client->post(
            //'https://www.google.com/recaptcha/api/siteverify',
            //['form_params'=>
            //[
                //'secret'=>$secretKey,
                //'response'=> $captcha
            //]
            //]
        //);
        //$body = json_decode((string)$response->getBody());

    } 
    
    /**
     * monthStr
     */    
    public static function monthStr() {
        $arr = array(); 
        $arr[1] = Lang::get('lang.jan');
        $arr[2] = Lang::get('lang.feb');
        $arr[3] = Lang::get('lang.mar');
        $arr[4] = Lang::get('lang.apr');
        $arr[5] = Lang::get('lang.may');
        $arr[6] = Lang::get('lang.jun');
        $arr[7] = Lang::get('lang.jul');
        $arr[8] = Lang::get('lang.aug');
        $arr[9] = Lang::get('lang.sep');
        $arr[10] = Lang::get('lang.oct');
        $arr[11] = Lang::get('lang.nov');
        $arr[12] = Lang::get('lang.dec');
        
        return $arr;
    }

    
	/**
     * genRandomStr
     */
    public static function genRandomStr() {
        return substr(md5(uniqid(rand(), true)), 0, 8);
    }


    /***********************************************************************
     * Recaptcha V3
     ***********************************************************************/

    /**
     * isHuman
     */
    public static function isHuman($recaptcha_response) {
        return true;
        // Build POST request:
        $recaptcha_url = 'https://www.google.com/recaptcha/api/siteverify';
        $recaptcha_secret = config('app.secret_key');
        
        // Make and decode POST request:
        $recaptcha = file_get_contents($recaptcha_url . '?secret=' . $recaptcha_secret . '&response=' . $recaptcha_response);
        $recaptcha = json_decode($recaptcha);
        
        // Take action based on the score returned:
        if (isset($recaptcha) and isset($recaptcha->score)) {
            if($recaptcha->score >= 0.5) return true;
        }
        
	    return false;
    }


    /***********************************************************************
     * Formatting
     ***********************************************************************/

    /**
     * removeComma
     */    
    public static function removeComma($num) {
        if(isset($num)) {
            return floatval(str_replace(',', '', $num));
        }

        return null;
    }


    /**
     * moneyFormat
     */    
    public static function moneyFormat($money) {
        // if(isset($money) and is_numeric($money)) {
        //     $arr = explode('.', $money);
        //     if(count($arr) > 1) {
        //         return number_format($money, 2, '.', ',');
        //     } else {
        //         return number_format($money, 2, '.', ',');
        //     }
        // }

        if(isset($money))
            return number_format($money, 0);
        // return '0.00';

        return number_format(0,2);
    }


	/***********************************************************************
     * Member Info
     ***********************************************************************/
    
    /**
     * get current login user
     */
    public static function currUser() {
        return User::where('users.id', '=', Session::get('login_user_id'))
          ->leftjoin('users as a', 'a.id', '=', 'users.referral')
          ->selectRaw('users.*, a.member_code as referral_code, CONCAT(a.full_name) as referral_name')
          ->first();
    }
    
    
    
    /**
     * userInfo
     */
    public static function userInfo($id) {
        $user = User::where('id', '=', $id)->first();
        return $user;
    }


    /**
     * issetArr
     * - check if array is null and container item
     */    
    public static function issetArr($var=null) {
        return (isset($var) and count($var) > 0);
    }
    
    /***********************************************************************
     * Generate
     ***********************************************************************/
    
    function generateRandomString($length = 25) {
        $characters = '0123456789ABCDEFGHIJKLMNOPQRSTUVWXYZ';
//        $characters = '0123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ';
        $charactersLength = strlen($characters);
        $randomString = '';
        for ($i = 0; $i < $length; $i++) {
            $randomString .= $characters[rand(0, $charactersLength - 1)];
        }
        return $randomString;
        
        //usage 
//        $myRandomString = generateRandomString(5);
    }
    


    /***********************************************************************
     * Closing
     ***********************************************************************/

    /**
     * closingPeriod
     */    
    public static function closingPeriod() {
		$dt = Gen::currTime();
	    $from = clone($dt);
	    $to = clone($dt);
	    $from = $from->startOfMonth();
	    $to = $to->endOfMonth();

        return array('from' => $from, 'to' => $to);
    }

    /***********************************************************************
     * Generate
     ***********************************************************************/

    /**
     * generate batch
     */
    public static function genBatch()
    { 
        $dt = Gen::currTime();
        $format_dt = Carbon::parse($dt)->format('YmdHis');
        $str = 'BATCH_'.$format_dt;

        return $str;
    }
}