<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use DB;
use Lang;
use Auth;
use Mail;
use Session;

use \App\Gen;
use \App\User;
use \App\Country;

class AuthController extends Controller
{
  /**
   * Sign In
   */
  public function signIn()
  {
    if (Auth::isLogin()) {

        if (Auth::isAdmin(Session::get('login_contact_code') . Session::get('login_contact_no'))) return redirect('/admin/dashboard');
        return redirect('/main');
    }
    $countries = Country::getAllActive();
    return view('shop.shopSignIn', compact('countries'));
  }

  /**
   * Submit Sign In
   */
  public function submitSignIn()
  {
    $this->validate(request(), [
      'login_id' => 'required',
      'pwd' => 'required|min:6',      
    ]);

    $login_id = request('contact_code') . request('login_id');
    if (is_numeric($login_id)) { // phone login
      // $first = substr($login_id, 0, 1);

      // $no_login = $login_id;
      // if ($first != '6') $no_login = '6' . $no_login;

      $u = Auth::contactExists($login_id);

      if (!isset($u)) {
        return redirect()->back()->withErrors(Lang::get('lang.memberNotExists'));
      }

      if ($u->isActive == 0) {
        return redirect()->back()->withErrors(Lang::get('lang.accNotActivated'));
      }

      if (Auth::attemptContact($login_id, request('pwd'))) {
        if (Auth::isAdmin($login_id)) { // if admin, redirect to admin page
          return redirect('/admin/dashboard');
        } else if(Session::get('login_is_agent') == 1) {
          return redirect('/agent/dashboard');
        }
        
        if (Session::has('prev_page')) {
          $prev_page = Session::get('prev_page');
          Session::forget('prev_page');
          return redirect($prev_page);
        } else {
          return redirect('/main');
        }
      }
    }
    else if (filter_var($login_id, FILTER_VALIDATE_EMAIL)) { // email login
      if(!Auth::emailExist($login_id)) {
        return redirect()->back()->withInput()->withErrors(Lang::get('lang.memberNotExists'));
      }

      if(!Auth::isActiveEmail($login_id)) {
        return redirect()->back()->withInput()->withErrors(Lang::get('lang.accNotActivated'));
      }
      if (Auth::attempt($login_id, request('pwd'))) {
        if (Auth::isAdmin($login_id)) {
          return redirect('/admin/dashboard');
        } else if(Session::get('login_is_agent') == 1) {
          return redirect('/agent/dashboard');
        } else {
          // return redirect()->back()->withInput()->withErrors(Lang::get('lang.unauthorized'));;
          return redirect('/main');
        }
      }
    }
    return redirect()->back()->withErrors(Lang::get('lang.contactNoPwdWrong'));
  }

  /**
   * Sign Up
   */
  public function signUp(Request $r)
  {
    $countries = Country::getAllActive();

    $refer_code = null;
    if(isset($r->refer_code))
      $refer_code = $r->refer_code;

    return view('shop.shopSignUp', compact('countries', 'refer_code'));
  }

  public function submitSignUp(Request $r)
  { 
    $this->validate($r, [ 
      'full_name' => 'required', 
      'contact_code' => 'required',
      'contact_no' => 'required|min:9',
      'pwd' => 'required|min:6',
      'confirm_pwd' => 'required|min:6|same:pwd',
      // 'is_agree' => 'required',      
    ]);

    try {
      $contact_no = $r->contact_no;

      //avoid 1st no is 0
      $first = substr($contact_no, 0, 1);
      if ($first == '0') $contact_no = substr($contact_no, 1);

      // $contact_no = $r->contact_code.$contact_no;

      //check contact exist
      $exist = Auth::contactExists($contact_no);
      if(isset($exist)) return redirect()->back()->withInput()->with('errMsg', \Lang::get('lang.contact_exist'));

      //check refer code
      $referral_id = null;
			if(isset($r->refer_code)){
				$referral_id = User::getIdByReferCode($r->refer_code);
        if(!isset($referral_id)) return redirect()->back()->withInput()->with('errMsg', \Lang::get('lang.invalid_refer_code'));
			}

      $r->merge([
        'contact_no' => $contact_no,
        'referral_id' => $referral_id,
      ]);

      $u = User::newUser($r);
      if(isset($u)) return redirect('/sign-in')->with('msg', \Lang::get('lang.successSignUp'));

    } catch (Exception $e) {
      \Log::error($e);
    }
    return redirect()->back()->withInput()->with('errMsg', \Lang::get('lang.signUpFailed'));
  }

  /**
   * Sign Out
   */
  public function signOut(){
    Session::forget('lastActivityTime');
    Session::flush();
    Auth::logout();

    return redirect('/');
  }

  /**
   * Admin Log Out
   */
  public function adminLogout()
    {
        Auth::logout(); // Logout the user
        return redirect('/admin/login'); // Redirect to the home page or any desired page after logout
    }

  /**
   * forgetPwd
   */
  public function forgetPwd()
  { 
    return view('shop.shopForgetPwd');
  }

  /**
   * submitForgetPwd
   */
  public function submitForgetPwd(Request $r)
  { 
    $this->validate($r, [   
      'contact_no' => 'required|min:9', 
    ]); 
    dd('Forget password?');
  } 
}