<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\Input;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

use Mail;
use Lang;
use Excel;
use File;
use Carbon\Carbon;

use Session;
use Auth;
use App\Models\Gen;
use \App\Models;

class MemberController extends Controller
{
  public function home()
  {
    $bg = Models\Home::where('h_id', 1)->first();
    if ($bg && $bg->h_img) {
        $explodedImg = explode(',', $bg->h_img);
    } else {
        $explodedImg = [];
    }

    $popup = Models\PopUp::where('pu_id', 1)->first();
    return view('member.home', compact('bg', 'explodedImg', 'popup'));
  }

  public function signUp(Request $r)
  {
    try {
      $id = Session::get('user_id');
      $dt = Gen::currTime();

      if(!$r->email) return['status' => false, 'msg' => 'Please enter email address'];
      if(!$r->password) return['status' => false, 'msg' => 'Please enter password'];
      if(!$r->confirm_password) return['status' => false, 'msg' => 'Please enter repreat password'];

      if(strlen($r->password) < 8) {
        return['status' => false, 'msg' => 'Password length should contain atleast 8 characters'];
      } else if(!preg_match('/[A-Z]/', $r->password)){
        return['status' => false, 'msg' => 'Password should contain atlease 1 uppercase characters'];
      } else if(!preg_match('~[0-9]+~', $r->password)){
        return['status' => false, 'msg' => 'Password should contain atlease 1 number'];
      } else if(!preg_match('/[\'^£$%&*()}{@#~?><>,|=_+¬-]/', $r->password)){
        return['status' => false, 'msg' => 'Password should contain atlease 1 special character'];
      } else if($r->password != $r->confirm_password) {
        return['status' => false, 'msg' => 'Password and repeat password not same'];
      }

      $member = Models\User::getByEmail($r->email);
      if (isset($member)) {
        return ['status' => false, 'errMsg' => 'Email is already registered'];
      }

      if(isset($id)) {
        $u = Models\User::getById($id);

        if(isset($u)) {
          $u->email = $r->email;
          $u->password = encrypt($r->password);
          $u->updated_at = $dt;
          $u->save();
        } 
      } else{
        $u = Models\User::addUser($r);
      }

      if(isset($u)) {
        return['status' => true, 'msg' => 'Sign up successful'];
      }

    } catch(\Exception $e) {
      \Log::error($e);
    }
    return ['status' => false, 'msg' => Lang::get('lang.errSystem')];
  }

  public function addMainSubCat(Request $r)
  {
    try {
      $id = Session::get('user_id');

      if(!$r->coursemaincat) return['status' => false, 'msg' => 'Please select field of study'];
      if(!$r->coursesubcat) return['status' => false, 'msg' => 'Please select major'];

      if(isset($id)) {
        $u = Models\User::getById($id);

        if(isset($u)) {
          $u->pref_main_cat = $r->coursemaincat;
          $u->pref_sub_cat = $r->coursesubcat;
          $u->save();
        } 
      } else{
        $u = Models\User::addUser($r);
      }

      if(isset($u)) {
        return['status' => true, 'msg' => 'Preferred course submitted'];
      }

    } catch(\Exception $e) {
      \Log::error($e);
    }
    return ['status' => false, 'msg' => Lang::get('lang.errSystem')];
  }

  public function login(Request $r)
  {
    $validator = \Validator::make(request()->all(), [
      'email' => 'required|exists:users,email',
      'password' => 'required'
    ]);

    $err = array();
    if ($validator->fails()) {
      foreach ($validator->errors()->all() as $v) {
        $err[] = $v;
      }
    }

    if (isset($err) and count($err) > 0) {
      return ['status' => false, 'msg' => $err[0]];
    }

    try{
      $login_id = request('email');

      if (!Auth::emailExist($login_id)) {
        return ['status' => false, 'msg' => 'Email address is not registered'];
      }

      if (Auth::attempt($login_id, $r->password)) {
        return ['status' => true, 'msg' => 'Login Successfully'];
      }

    } catch(\Exception $e) {
      \Log::error($e);
    }
    return ['status' => false, 'msg' => Lang::get('lang.errSystem')];
  }

  public function addName(Request $r)
  {
    try {
      if($r->name != null) {
        Session::put('name', $r->name);
        return['status' => true, 'msg' => 'Name added succesfully'];
      }
    } catch(\Exception $e) {
      \Log::error($e);
    }
    return ['status' => false, 'msg' => Lang::get('lang.errSystem')];
  }

  public function personality()
  {
    $desc = Models\Personality::getAllActive(1);
    $interest = Models\Personality::getAllActive(2);
    $name = Session::get('name');

    if(isset($name)) {
      return view('member.personality', compact('desc','interest'));
    } else{
      return redirect('/');
    }

  }

  public function getSubject(Request $r)
  {
    try{
      $return = $pre_selected = $final_return = [];
      if($r->level != null) {
        $subject = Models\Subject::getAllActiveByLevel($r->level);
        $grade = Models\SubjectGrade::getGrades($r->level);

        if(isset($subject) && count($subject) > 0) {
          foreach ($subject as $s) {
            if($s->s_is_preselect != 1) {
              $return[$s->sc_name][] = array(
                'id' => $s->s_id,
                'name' => $s->s_name
              );
            } else{
              $pre_selected[] = array(
                'id' => $s->s_id,
                'name' => $s->s_name
              );
            }
          }

          if(isset($return) && count($return) > 0){
            foreach ($return as $i => $r) {
              $final_return[] = array(
                'name' => $i,
                'data' => $r
              );
            }
          }

          return['status' => true, 'list' => $final_return, 'preSelected' => $pre_selected, 'grade' => $grade];
        }
      }
    } catch(\Exception $e) {
      \Log::error($e);
    }
    return ['status' => false, 'msg' => Lang::get('lang.errSystem')];
  }

  public function getMainCat()
  {
    try{
      $cat = Models\Category::getAllActive();
      if(isset($cat) && count($cat) > 0){
        return['status' => true, 'list' => $cat];
      }
    } catch(\Exception $e) {
      \Log::error($e);
    }
    return ['status' => false, 'msg' => Lang::get('lang.errSystem')];
  }

  public function getSubCat(Request $r)
  {
    try{
      if(isset($r->id)){
        $cat = Models\SubCat::getAllActive($r->id);
        if(isset($cat) && count($cat) > 0){
          return['status' => true, 'list' => $cat];
        }
      }
    } catch(\Exception $e) {
      \Log::error($e);
    }
    return ['status' => false, 'msg' => Lang::get('lang.errSystem')];
  }

  public function createNewUser(Request $r)
  {
    try{
      if(!isset($r->contact_no) &&  $r->contact_no == null){
        return['status' => false, 'msg' => 'Please enter phone number'];
      }

      if($r->gender == null){
        return['status' => false, 'msg' => 'Please select gender'];
      }

      if($r->dob_day == null){
        return['status' => false, 'msg' => 'Please enter dob date'];
      }

      if($r->dob_month == null){
        return['status' => false, 'msg' => 'Please enter dob month'];
      }

      if($r->dob_year == null){
        return['status' => false, 'msg' => 'Please enter dob year'];
      }

      if($r->is_malaysian == null){
        return['status' => false, 'msg' => 'Please select nationality'];
      }

      $contact_no = $r->contact_no;

      if (substr($contact_no, 0, 1) == '0') {
        $contact_no = substr($contact_no, 1);
      } else if (substr($contact_no, 0, 2) == '60') {
        $contact_no = substr($contact_no, 2);
      } else {
        $contact_no = $contact_no;
      }
      
      $id = Session::get('user_id');
      $name = Session::get('name');
      if(isset($id)) {
        $u = Models\User::getById($id);
      } else{
        $u = Models\User::byContact('60', $contact_no);
      }
      
      if(isset($u)){
        if($u->isActive == 1){
          Session::put('user_id', $u->id);
          return['status' => false, 'msg' => 'Phone number have been register. Please login to continue'];
        } else if($u->isActive == 0){
          $u->gender = $r->gender;
          $u->dob_day = $r->dob_day;
          $u->dob_month = $r->dob_month;
          $u->dob_year = $r->dob_year;
          $u->contact_code = '60';
          $u->contact_no = $contact_no;
          $u->is_malaysian = $r->is_malaysian;
          $u->username = $name;
          $u->character = null;
          $u->save();
          Session::put('user_id', $u->id);
          return['status' => true];
        }
      } else{
        $r->merge(['contact_code' => '60', 'contact_no' => $contact_no, 'password' => '123456789@', 'name' => $name]);
        $u = Models\User::addUser($r);

        if(isset($u)) {
          Session::put('user_id', $u->id);
          return['status' => true, 'msg' => 'User created successfully'];
        }
      }

    } catch(\Exception $e) {
      \Log::error($e);
    }
    return ['status' => false, 'msg' => Lang::get('lang.errSystem')];
  }

  public function addPersonality(Request $r)
  {
    try{
      if(!isset($r->desc) && count($r->desc) < 0){
        return['status' => false, 'msg' => 'Please select atleast 1 describe options'];
      }

      if(!isset($r->interest) && count($r->interest) < 0){
        return['status' => false, 'msg' => 'Please select atleast 1 interest options'];
      }

      if(isset($r->desc, $r->interest) && count($r->desc) > 0 && count($r->interest) > 0) {
        $desc = implode(',', $r->desc);
        $interest = implode(',', $r->interest);
        $final = $desc.','.$interest;
        $id = Session::get('user_id');
        
        $user = Models\User::getById($id);

        $user->personality = $desc;
        $user->interests = $interest;
        $user->save();

        return['status' => true, 'msg' => 'Personality added successfully'];
      }

    } catch(\Exception $e) {
      \Log::error($e);
    }
    return ['status' => false, 'msg' => Lang::get('lang.errSystem')];
  }

  public function addAcademic(Request $r)
  {
    try{
      if($r->school == null) {
        return['status' => false, 'msg' => 'Please enter school name'];
      }

      if($r->level != 1 && $r->level != 2 && $r->level != 3){
        return['status' => false, 'msg' => 'Please select educational level'];
      }

      if($r->level == 1 || $r->level == 2){
        if($r->qualification == null || $r->qualification == 0){
          return['status' => false, 'msg' => 'Please select qualification'];
        }
      }else if($r->level == 3){
        if($r->transcript == null){
          return['status' => false, 'msg' => 'Please enter result transcript'];
        }
      }

      if($r->qualification == 11) {
        if($r->transcript == null){
          return['status' => false, 'msg' => 'Please enter result transcript'];
        }
      }

      $id = Session::get('user_id');
      $r->merge(['user_id' => $id]);

      $exist = Models\Academic::getByUserId($id);

      if(isset($exist)){
        $dt = Gen::currTime();

        $exist->is_malaysian = $r->is_malaysian;
        $exist->a_level = $r->level;
        $exist->a_qualification = $r->qualification;
        $exist->a_school = $r->school;
        $exist->updated_at = $dt;

        if($r->level == 3 || $r->qualification == 11) {
          $attachment = "attachment-" . uniqid() . '.jpg';

          $r->transcript->move(
            public_path('/images/result'),
            $attachment
          );

          $exist->a_transcript = $attachment;

        }

        $exist->save();
      } else{
        $exist = Models\Academic::addAcademic($r);
      }

      if(isset($exist)){
        return['status' => true, 'msg' => 'successfully added academics'];
      }

    } catch(\Exception $e) {
      \Log::error($e);
    }
    return ['status' => false, 'msg' => Lang::get('lang.errSystem')];
  }

  public function addStream(Request $r)
  {
    try{
      $id = Session::get('user_id');
      $r->merge(['user_id' => $id]);

      $exist = Models\User::getById($id);

      if(isset($exist)){
        $dt = Gen::currTime();

        $exist->stream = $r->stream;
        $exist->updated_at = $dt;

        $exist->save();
      }

      if(isset($exist)){
        return['status' => true, 'msg' => 'successfully added stream'];
      }

    } catch(\Exception $e) {
      \Log::error($e);
    }
    return ['status' => false, 'msg' => Lang::get('lang.errSystem')];
  }

  public function addResult(Request $r)
  {
    try {
      $id = Session::get('user_id');
      $academic = Models\Academic::getByUserId($id);

      if(isset($academic)){
        $exist = Models\Result::getByUser($id);

        if(isset($exist) && count($exist) > 0){
          foreach ($exist as $e) {
            $e->r_status = 99;
            $e->save();
          }
        }

        if($academic->a_qualification == 5 || $academic->a_qualification == 6 || $academic->a_qualification == 8 || $academic->a_qualification == 9 || $academic->a_qualification == 10 || $academic->a_qualification == 11 || $academic->a_qualification == 13 || $academic->a_qualification == 14 || $academic->a_qualification == 15 || $academic->a_qualification == 16 || $academic->a_qualification == 17) {
          $r->merge(['user_id' => $id]);
          $add = Models\Result::addCgpa($r);
        } else{
          if(isset($r->subject, $r->grades) && count($r->subject) > 0 && count($r->grades) > 0)
          {
            foreach ($r->subject as $i => $s) {
              $is_subject = Models\Subject::getByLevelSubject($academic->a_qualification, $s);

              if(isset($is_subject)) {
                $r->merge(['user_id' => $id, 'subject_id' => $s, 'grade' => $r->grades[$i]]);
                $add = Models\Result::addResult($r);
              }
            }
          }
        }

        if(isset($add)){
          return['status' => true, 'msg' => 'Result added succesfully'];
        }
      }
    } catch(\Exception $e) {
      \Log::error($e);
    }
    return ['status' => false, 'msg' => Lang::get('lang.errSystem')];
  }

  public function addAchievement(Request $r)
  {
    try {
      $id = Session::get('user_id');
      // $id = 4;
      $error = false;
      $achievement = Models\Achievement::getByUserId($id);

      if($r->is_district == null || $r->is_talent == null || $r->is_leadership == null){
        return['status' => false, 'msg' => 'Please select all option to continue'];
        $error = true;
      } else{
        if($r->is_district == 1){
          if($r->level_sport == null && $r->sport_represent == null){
            return['status' => false, 'msg' => 'Please select sport represent level and please enter which sport you have represent'];
            $error = true;
          }
        }

        if($r->is_talent == 1){
          if($r->level_talent == null && $r->talent_represent == null){
            return['status' => false, 'msg' => 'Please select competition & community project level and please enter which project you have completed/volunteered in'];
            $error = true;
          }
        }

        if($r->is_leadership == 1){
          if($r->level_leadership == null && $r->leadership_represent == null && $r->role == null){
            return['status' => false, 'msg' => 'Please select most impactful role and enter which socity/event you have represent and select which level you have represent'];
            $error = true;
          }
        }
      }

      if(!$error) {

        if(isset($achievement) && count($achievement)){
          foreach ($achievement as $a) {
            if($a->a_type == 1) {
              $a->a_is_represent = $r->is_district;
              $a->a_level = $r->is_district == 1 ? $r->level_sport : 0;
              $a->a_represent = $r->is_district == 1 ? $r->sport_represent : null;
            }

            if($a->a_type == 2) {
              $a->a_is_represent = $r->is_talent;
              $a->a_level = $r->is_talent == 1 ? $r->level_talent : 0;
              $a->a_represent = $r->is_talent == 1 ? $r->talent_represent : null;
            }

            if($a->a_type == 3) {
              $a->a_is_represent = $r->is_leadership;
              $a->a_level = $r->is_leadership == 1 ? $r->level_leadership : 0;
              $a->a_represent = $r->is_leadership == 1 ? $r->leadership_represent : null;
              $a->a_role = $r->is_leadership == 1 ? $r->role : 0;
            }

            $a->save();
          }

          if(isset($achievement)){
            return['status' => true, 'msg' => 'Achievement added succesfully'];
          }

        } else{
          if($r->is_district == 1){
            $r->merge(['type' => 1, 'user_id' => $id, 'is_represent' => $r->is_district, 'level' => $r->level_sport, 'represent' => $r->sport_represent]);
            $add = Models\Achievement::addAchievement($r);
          } else{
            $r->merge(['type' => 1, 'user_id' => $id, 'is_represent' => $r->is_district, 'level' => $r->level_sport, 'represent' => $r->sport_represent]);
            $add = Models\Achievement::addAchievement($r);
          }
  
          if($r->is_talent == 1){
            $r->merge(['type' => 2, 'user_id' => $id, 'is_represent' => $r->is_talent, 'level' => $r->level_talent, 'represent' => $r->talent_represent]);
            $add = Models\Achievement::addAchievement($r);
          } else {
            $r->merge(['type' => 2, 'user_id' => $id, 'is_represent' => $r->is_talent, 'level' => $r->level_talent, 'represent' => $r->talent_represent]);
            $add = Models\Achievement::addAchievement($r);
          }
  
          if($r->is_leadership == 1){
            $r->merge(['type' => 3, 'user_id' => $id, 'is_represent' => $r->is_leadership, 'level' => $r->level_leadership, 'represent' => $r->leadership_represent]);
            $add = Models\Achievement::addAchievement($r);
          } else {
            $r->merge(['type' => 3, 'user_id' => $id, 'is_represent' => $r->is_leadership, 'level' => $r->level_leadership, 'represent' => $r->leadership_represent]);
            $add = Models\Achievement::addAchievement($r);
          }
        }
        if(isset($add)){
          return['status' => true, 'msg' => 'Achievement added succesfully'];
        }
      }
    } catch(\Exception $e) {
      \Log::error($e);
    }
    return ['status' => false, 'msg' => Lang::get('lang.errSystem')];
  }

  public function addFamily(Request $r)
  {
    try{
      // $id = 4;
      $id = Session::get('user_id');
      if($r->income != null && $r->budget != null && $r->bumiputera != null) {
        $r->merge(['user_id' => $id]);
        $exists = Models\Family::getByUserId($id);
        if(isset($exists)) {
          $exists->f_income = $r->income;
          $exists->f_budget = $r->budget;
          $exists->f_is_bumiputera = $r->bumiputera;

          $exists->save();
        } else{
          $exists = Models\Family::addFamily($r);
        }

        if(isset($exists)){
          return['status' => true, 'msg' => 'Family details have been added successfully'];
        }
      } else{
        return['status' => false, 'msg' => 'Please select all options to continue'];
      }
    } catch(\Exception $e) {
      \Log::error($e);
    }
    return ['status' => false, 'msg' => Lang::get('lang.errSystem')];
  }

  public  function updatePreferredCourse(Request $r)
  {
    try{
      if($r->id !=  null) {
        // $id = 3;
        $id = Session::get('user_id');

        $u = Models\User::getById($id);

        if(isset($u)) {
          $u->preferred_course = $r->id;
          $u->save();

          return['status' => true, 'msg' => 'Preferred course added successfully'];
        }
      }
    } catch(\Exception $e) {
      \Log::error($e);
    }
    return ['status' => false, 'msg' => Lang::get('lang.errSystem')];
  }

  public function addPathway(Request $r)
  {
    try{
      if($r->pathway != null || $r->start != null) {
        // $id = 4;
        $id = Session::get('user_id');
        $exist = Models\UniversityPreference::getByUser($id);

        if(isset($exist)) {
          $exist->up_pathway = $r->pathway;
          $exist->up_start = $r->start;
          $exist->save();

        }else {
          $r->merge(['user_id' => $id]);
          $exist = Models\UniversityPreference::addUniversityPreference($r);
        }

        if(isset($exist) && $exist != null) {
          return['status' => true, 'msg' => 'Pathway added successfully'];
        }
      }
    } catch(\Exception $e) {
      \Log::error($e);
    }
    return ['status' => false, 'msg' => Lang::get('lang.errSystem')];
  }

  public function addStudyLocation(Request $r)
  {
    try{
      $error = false;
      // $id = 4;
      $id = Session::get('user_id');

      if($r->location == 1) {
        if($r->state == null) {
          $error = true;
        }
      } else if($r->location == 2) {
        if($r->state == null || $r->country == null) {
          $error = true;
        }
      } else if ($r->location == 3) {
        if($r->country == null) {
          $error = true;
        }
      }

      if(!$error) {
        $exist = Models\UniversityPreference::getByUser($id);

        if(isset($exist)) {
          $state = implode(',', $r->state);

          $exist->up_location = $r->location;
          $exist->up_state = $r->location == 3 ? 0 : $state;
          $exist->up_country = $r->location == 1 ? 0 : $r->country;
          $exist->save();

        }else {
          $r->merge(['user_id' => $id]);
          $exist = Models\UniversityPreference::addUniversityPreference($r);
        }

        if(isset($exist)) {
          return['status' => true, 'msg' => 'Study location added successfully'];
        }
      } else{
        return['status' => false, 'msg' => 'Please select all the option to continue'];
      }
    } catch(\Exception $e) {
      \Log::error($e);
    }
    return ['status' => false, 'msg' => Lang::get('lang.errSystem')];
  }

  public function addUniversityType(Request $r)
  {
    try {
      $error = false;
      // $id = 4;
      $id = Session::get('user_id');

      if($r->type == null) {
        $error = true;
      }

      if($r->paid == null) {
        $error = true;
      } else{
        if($r->paid == 1) {
          if($r->uni_name == null || $r->uni_name == '') {
            $error = true;
          }
        }
      }

      if(!$error) {
        $exist = Models\UniversityPreference::getByUser($id);

        if(isset($exist)) {
          $exist->up_type = $r->type;
          $exist->up_is_fee_paid = $r->paid;
          $exist->up_uni_name = $r->paid == 1 ? $r->uni_name : null;
          $exist->save();
        } else{
          $r->merge(['user_id' => $id]);
          $exist = Models\UniversityPreference::addUniversityPreference($r);
        }

        if(isset($exist)) {
          return['status' => true, 'msg' => 'University type added successfully'];
        }
      } else{
        return['status' => false, 'msg' => 'Please select all the option to continue'];
      }
    } catch(\Exception $e) {
      \Log::error($e);
    }
    return ['status' => false, 'msg' => Lang::get('lang.errSystem')];
  }
  
  public function getSchool(Request $r)
  {
    try {
      $school = Models\Schools::searchSchool($r->search);
      $return = [];

      if(isset($school) && count($school) > 0) {
        foreach ($school as $s) {
          $level = explode(',', $s->s_qualification);

          // if(in_array($r->level, $level)) {
            if(count($return) <= 3) {
              $return[] = array(
                'id' => $s->s_id,
                'name' => $s->s_name,
              );
            }
          // }
        }
        return['status' => true, 'list' => $return];
      }
    } catch(\Exception $e) {
      \Log::error($e);
    }
    return ['status' => false, 'msg' => Lang::get('lang.errSystem')];
  }

  public function explore(Request $r)
  {
    $tab = 'university';

    if($r->tab != null) {
      $tab = $r->tab;
    }

    $study_field = Models\Category::getAllActive();
    $level = Models\Course::levelArr();
    $state = Models\State::getAllActive(129);
    $month = Models\Course::monthArr();
    $uni = Models\University::getAll();
    $type = Models\Scholarship::typeArr();
    $sponser = Models\Scholarship::sponserArr();
    $coverage = Models\Scholarship::coverageArr();
    $requirement = Models\Scholarship::requirementArr();
    $interview = Models\Scholarship::interviewArr();
    $k12Level = Models\InternationalSchool::levelArr();
    return view('member.explore', compact('study_field','level','state','month','uni','type','sponser','coverage','requirement','interview','k12Level','tab'));
  }
  
  public function getUniversityJs(Request $r)
  {
    try {
      $uni = Models\University::getUniList($r);
      $return = [];

      if(isset($uni) && count($uni) > 0) {
        foreach($uni as $u) {
          $ttl_course = Models\Course::getTotalCoursesByUni($u->u_id);
          $ttl_scholarship = Models\Scholarship::getTotalScholarshipByUni($u->u_id);
          $branch = Models\UniversityBranch::getById($u->u_id);
          $branch_name = '';
          $state_name = '';

          // if(isset($branch) && count($branch) > 0) {
          //   if($branch[0]['b_country'] == 129) {
          //     $branch_name = Models\State::getNameById($branch[0]['b_state']);
          //   } else{
          //     $branch_name = Models\Country::getNameById($branch[0]['b_country']);
          //   }
          // }

          if($u->u_country == 129) {
            $state_name = Models\State::getNameById($u->u_state);
          } else{
            $state_name = Models\Country::getNameById($u->u_country);
          }

          $return[] = array(
            'id' => $u->u_id,
            'url' => $u->u_url_code,
            'name' => $u->u_name,
            'img' => url('images/university/logo/'.$u->u_logo),
            'ttl_course' => $ttl_course,
            'ttl_scholarship' => $ttl_scholarship,
            'has_branch' => $u->u_has_branch == 1 ? true : false,
            'ttl_branch' => $u->u_has_branch == 1 ? count($branch) : 0,
            'branch_name' => $branch_name,
            'state' => $state_name
          );
        }
        return['status' => true, 'list' => $return];
      } 
    } catch(\Exception $e) {
      \Log::error($e);
    }
    return ['status' => false, 'msg' => Lang::get('lang.errSystem')];
  }

  public function getCoursesJs(Request $r)
  {
    try { 
      $course = Models\Course::getCourses($r);
      $months = Models\Course::monthArr();
      $id = Session::get('user_id');
      // $id = 4;
      $u = Models\User::getById($id);
      if(isset($u)){
        $character = json_decode($u->character);
      }
      $return = $character = [];
      $has_rate = false;
      $rate = 0;

      if(isset($course) && count($course) > 0) {
        foreach ($course as $c) {

          if(isset($character) && count($character) > 0) {
            foreach($character as $ch) {
              $has_rate = false;
              $rate = Models\SubCat::getRate($ch->id, $c->c_sub_cat);
              
              if(isset($rate)) {
                $has_rate = true;
                $rate = $ch->percentage;
              }
            }
          }

          $intake = explode(',',  $c->c_intake);
          $list = '';
          $has_intake = false;

          foreach ($intake as $i) {
            $list .= date('M', mktime(0, 0, 0, $i, 10)).' ';

            if($r->month != null) {
              if($i == $r->month) {
                $has_intake = true;
              }
            } else{
              $has_intake = true;
            }
          }

          $duration = $c->c_duration / 12;
          if ($duration == intval($duration)) {
              $durationString = intval($duration) . ' Years';
          } else {
              $durationString = number_format($duration, 1) . ' Years';
          }

          if($has_intake) {
            $return[] = array (
              'id' => $c->c_id,
              'url_code' => $c->c_url_code,
              'name' => $c->c_name,
              'uni_name' => $c->u_name,
              'uni_logo' => url('images/university/logo/'.$c->u_logo),
              'duration' => $durationString,
              'intake' => trim($list, ' '),
              'ttl_fees' => Gen::moneyFormat($c->c_amount),
              'deals' => '0',
              'scholarship' => '?',
              'ttl_amt' => Gen::moneyFormat($c->c_amount),
              'has_rate' => $has_rate,
              'rate_amt' => $rate
            );
          }
        }
        return['status' => true, 'list' => $return];
      }
    } catch(\Exception $e) {
      \Log::error($e);
    }
    return ['status' => false, 'msg' => Lang::get('lang.errSystem')];
  }

  public function getScholarship(Request $r)
  {
    try {
      $scholarship = Models\Scholarship::getScholarship($r);
      $sub = Models\SubCat::getAll();
      $requirement = Models\Scholarship::requirementArr();
      $max_discount = 0;
      $return_max_discount = '';
      $return = [];

      if(isset($scholarship) && count($scholarship) > 0) {
        foreach($scholarship as $s) {
          $applicable = '';
          $has_field = '';
          $field = explode(',', $s->s_study_field);

          if(isset($field) && count($field) > 0) {
            foreach ($field as $f) {
              $name = Models\SubCat::getNameById($f);
              $applicable .= $name.', ';

              if($r->field != null) {
                $main = Models\SubCat::getMainCat($r->field);

                if(isset($main) && count($main) > 0) {
                  foreach ($main as $m) {
                    if($f == $m->sc_id) {
                      $has_field = true;
                    }
                  }
                }

                // if($r->major != null) {
                //   if($f == $r->major) {
                //     $has_field = true;
                //   }
                // }
              } else{
                $has_field = true;
              }

            }
          }

          $req = explode(',', $s->s_requirement);
          $r_list = '';

          if(isset($req) && count($req) > 0) {
            foreach($req as $re) {
              if($s->s_requirement != 0) {
                $r_list .= $requirement[$re].', ';
              }
            }
          }
          if($has_field) {
            $return[] = array(
              'id' => $s->s_id,
              'url_code' => $s->s_url_code,
              'name' => $s->s_title,
              'uni_name' => $s->s_is_corporate == 0 ? $s->u_name : $s->s_c_name,
              'uni_logo' => $s->s_is_corporate == 0 ? url('images/university/logo/'.$s->u_logo) : url('images/scholarships/'.$s->s_c_logo),
              'applicable' => trim($applicable, ', '),
              'has_all_cat' => count($sub) == count($field) ? true : false,
              'has_interview' => $s->s_has_interview == 1 ? 'Yes' : 'No',
              'requirement' => trim($r_list, ', '),
              'max_discount' => $s->sd_is_amt == 1 ? 'MYR '.Gen::moneyFormat($s->max_amount) : $s->max_percentage.'%',

            );
          }

        }

        return['status' => true, 'list' => $return];
      }
    } catch(\Exception $e) {
      \Log::error($e);
    }
    return ['status' => false, 'msg' => Lang::get('lang.errSystem')];
  }

  public function getK12School(Request $r)
  {
    try {
      $school = Models\InternationalSchool::getSchoolList($r);
      $branch_name = null;
      $return = [];

      if(isset($school) && count($school) > 0) {
        foreach($school as $s) {
          $branch = Models\InternationalSchoolBranch::getById($s->int_id);
          $ttl_scholarship = Models\IntScholarship::getTotalScholarshipBySchool($s->int_id);
          $level = explode(',', $s->int_level);
          $has_level = false;

          if($r->level != null) {
            foreach ($level as $l) {
              if($l == $r->level) {
                $has_level = true;
              }
            }
          } else{
            $has_level = true;
          }

          if(isset($branch) && count($branch) > 0) {
            if($branch[0]['int_b_country'] == 129) {
              $branch_name = Models\State::getNameById($branch[0]['int_b_state']);
            } else{
              $branch_name = Models\Country::getNameById($branch[0]['int_b_country']);
            }
          }
          
          if($s->int_country == 129) {
            $state_name = Models\State::getNameById($s->int_state);
          } else{
            $state_name = Models\Country::getNameById($s->int_country);
          }

          if($has_level) {
            $return[] = array(
              'id' => $s->int_id,
              'url' => $s->int_url_code,
              'name' => $s->int_name,
              'img' => url('images/internationalschool/logo/'.$s->int_logo),
              'ttl_scholarship' => $ttl_scholarship,
              'has_branch' => $s->int_has_branch == 1 ? true : false,
              'ttl_branch' => $s->int_has_branch == 1 ? count($branch) - 1 : 0,
              'branch_name' => $branch_name,
              'state' => $state_name
            );
          }
        }
        return['status' => true, 'list' => $return];
      }
    }catch(\Exception $e) {
      \Log::error($e);
    }
    return ['status' => false, 'msg' => Lang::get('lang.errSystem')];
  }

  public function getSubCatByMainCat(Request $r)
  {
    try {
      if($r->id != null) {
        $sub = Models\SubCat::getAllActive($r->id);

        if(isset($sub) && count($sub) > 0) {
          return['status' => true, 'list' => $sub];
        }
      }
    } catch(\Exception $e) {
      \Log::error($e);
    }
    return ['status' => false, 'msg' => Lang::get('lang.errSystem')];
  }

  public function university($code)
  {
    try {
      if(isset($code)) {
        $uni = Models\University::getByUrl($code);
        
        if(isset($uni)) {
          $img = $intake_return = $category = [];
          $images = explode(',', $uni->u_img);
          $time = explode('~', $uni->u_hours);
          $intake = Models\Course::getByIntakeByUni($uni->u_id);
          $level = Models\Course::levelArr();
          $month = Models\Course::monthArr();
          $categories = Models\Course::getCourseCatByUni($uni->u_id);
          // dd($categories);
          if(isset($images) && count($images) > 0) {
            foreach($images as $i) {
              $img[] = url('images/university/images/'.$i);
            }
          }

          if(isset($intake) && count($intake) > 0) {
            foreach($intake as $i) {
              $intake_return[$level[$i->c_level]][] = $i->c_intake;
            }

            if(isset($intake_return) && count($intake_return) > 0) {
              foreach($intake_return as $i => $int) {
                $intake_month = '';
                foreach($int as $it) {
                  $intake_month .= $it.',';
                  $intake_arr = trim($intake_month, ',');
                  $intake_arr = explode(',', $intake_arr);
                  $intake_arr = array_unique($intake_arr);
                  $int_month = [];
                  
                  foreach ($intake_arr as $ia) {
                    $int_month[] = date('M', mktime(0, 0, 0, $ia, 10)).' '.date("y");
                  }
                  $intake_return[$i] = $int_month; 
                }
              }
            }
            $uni->intake_months = $intake_return;
          }

          $uni->img = $img;
          $uni->address = $uni->u_addr.', '.Models\State::getNameById($uni->u_state).', '.Models\Country::getNameById($uni->u_country);
          $uni->operating_hours = date("g:i a", strtotime($time[0])).' - '.date("g:i a", strtotime($time[1]));
          $uni->u_logo = url('images/university/logo/'.$uni->u_logo);

          return view('member.university', compact('uni','categories'));
        }
      }
    } catch(\Exception $e) {
      \Log::error($e);
    }

    abort(404);
  }

  public function getCoursesByUni(Request $r)
  {
    try {
      $return = [];

      if($r->cat_id != '' || $r->uni_id != '') {
        $course = Models\Course::getCourseByMainCat($r->cat_id, $r->uni_id);

        if(isset($course) && count($course) > 0) {
          foreach($course as $c) {
            $intake = explode(',',  $c->c_intake);
            $list = '';

            foreach ($intake as $i) {
              $list .= date('M', mktime(0, 0, 0, $i, 10)).' ';
            }

            $duration = $c->c_duration / 12;
            if ($duration != intval($duration)) {
                $durationString = number_format($duration, 1);
            } else {
                $durationString = $duration;
            }

            $return[] = array (
              'id' => $c->c_id,
              'url_code' => $c->c_url_code,
              'name' => $c->c_name,
              'uni_name' => $c->u_name,
              'uni_logo' => url('images/university/logo/'.$c->u_logo),
              'duration' => $durationString .' Years',
              'intake' => trim($list, ' '),
              'ttl_fees' => Gen::moneyFormat($c->c_amount),
              'deals' => '0',
              'scholarship' => '?',
              'ttl_amt' => Gen::moneyFormat($c->c_amount),
            );
          }
          return['status' => true, 'list' => $return];
        }
      }
    } catch(\Exception $e) {
      \Log::error($e);
    }
    return ['status' => false, 'msg' => Lang::get('lang.errSystem')];
  }

  public function getScholarshipByUni(Request $r)
  {
    try {
      $scholarship = Models\Scholarship::getByUniId($r->uni_id);
      $sub = Models\SubCat::getAll();
      $requirement = Models\Scholarship::requirementArr();
      $return = [];

      if(isset($scholarship) && count($scholarship) > 0) {
        foreach($scholarship as $s) {
          $discount = Models\ScholarshipDiscount::getByScholarshipId($s->s_id);
          $applicable = '';
          $has_field = '';
          $type = $max_discount = 0;
          $return_max_discount = '';
          $field = explode(',', $s->s_study_field);

          if(isset($field) && count($field) > 0) {
            foreach ($field as $f) {
              $name = Models\SubCat::getNameById($f);
              $applicable .= $name.', ';
            }
          }

          if(isset($discount) && count($discount) > 0) {
            $max_discount = 0;
            foreach ($discount as $d) {
              if($d->sd_is_amt == 1) {
                if($d->sd_amt > $max_discount) {
                  $max_discount = $d->sd_amt;
                  $return_max_discount = 'MYR '.Gen::moneyFormat($max_discount);
                }
                $type = 1;
              } else {
                if($d->sd_percentage > $max_discount) {
                  $max_discount = $d->sd_percentage;
                  $return_max_discount = $max_discount.'%';
                }
                $type = 2;
              }
            }
          }

          $return[] = array(
            'id' => $s->s_id,
            'url_code' => $s->s_url_code,
            'name' => $s->s_title,
            'uni_name' => $s->u_name,
            'uni_logo' => url('images/university/logo/'.$s->u_logo),
            'applicable' => trim($applicable, ', '),
            'has_all_cat' => count($sub) == count($field) ? true : false,
            'has_interview' => $s->s_has_interview == 1 ? 'Yes' : 'No',
            'requirement' => $s->s_requirement != 0 ? $requirement[$s->s_requirement] : null,
            'max_discount' => $return_max_discount,
            'type' => $type,
            'amount' => isset($max_discount) ? $max_discount : 0,
          );
        }

        return['status' => true, 'list' => $return];
      }
    } catch(\Exception $e) {
      \Log::error($e);
    }
    return ['status' => false, 'msg' => Lang::get('lang.errSystem')];
  }

  public function visitCampus()
  {
    try {
      // if(isset($code)) {
        $uni = Models\University::getUniWithBranch();
        // $curr_uni = Models\University::getByUrl($code);
        $time = Models\VisitCampus::timeArr();
        $university = [];

        if(isset($uni) && count($uni) > 0) {
          foreach ($uni as $u) {

            // if($u->b_status != 99) {
              $university[] = array(
                'id' => $u->u_id,
                'name' => $u->u_has_branch == 1 ? $u->u_name.' - '.$u->b_addr : $u->u_name,
                'branch_id' => isset($u->b_id) ? $u->b_id : 0,
              );
            // }
          }
        }
        return view('member.visitCampus', compact('time','university'));
      // }
    } catch(\Exception $e) {
      \Log::error($e);
    }

    abort(404);
  }

  public function getBranchByUni(Request $r)
  {
    try {
      if($r->id != null) {
        $branch = Models\UniversityBranch::getById($r->id);
        $has_branch = true;
        $return = [];

        if(isset($branch) && count($branch) > 0){
          foreach ($branch as $b) {
            $return[] = array(
              'id' => $b->b_id,
              'name' => $b->state_name,
            );
          }
        } else{
          $has_branch = false;
          $return[] = array(
            'id' => 0,
            'name' => 'Main Branch'
          );
        }
        return['status' => true, 'branch' => $return, 'has_branch' => $has_branch];
      }

    } catch(\Exception $e) {
      \Log::error($e);
    }
    return['status' => false];
  }

  public function submitVisitCampus(Request $r) 
  { 
    try {
      \Log::info($r->all());
      $time = Models\VisitCampus::timeArr();
      $error = false;
      $msg = '';

      // if($r->campus == null || $r->campus == '') {
      //   $error = true;
      //   $msg = 'Please select campus to continue';
      // } else if($r->full_name == null || $r->full_name == '') {
      //   $error = true;
      //   $msg = 'Please enter full name to continue';
      // } else if($r->ic_num == null || $r->ic_num == '') {
      //   $error = true;
      //   $msg = 'Please enter ic number to continue';
      // } else if($r->contact_no == null || $r->contact_no == '') {
      //   $error = true;
      //   $msg = 'Please enter contact number to continue';
      // } else if($r->pax == null || $r->pax == '') {
      //   $error = true;
      //   $msg = 'Please enter total amount of pax to continue';
      // } else if($r->date == null || $r->date == '') {
      //   $error = true;
      //   $msg = 'Please enter the day you would like to visit';
      // } else if($r->month == null || $r->month == '') {
      //   $error = true;
      //   $msg = 'Please enter the month you would like to visit';
      // } else if($r->year == null || $r->year == '') {
      //   $error = true;
      //   $msg = 'Please enter the year you would like to visit';
      // } else if($r->time == null || $r->time == '') {
      //   $error = true;
      //   $msg = 'Please select the preferred time you would like to visit';
      // }

      if($error) {
        return['status' => false, 'msg' => $msg];
      } else{
        $branch = explode(',', $r->branch);
        if(isset($r->university) && count($r->university) > 0) {
          foreach ($r->university as $i => $u) {
            $uni[] = array(
              'uni_id' => $u,
              'branch' => $branch[$i]
            );
          }
        }
        $r->merge(['uni_id' => json_encode($uni)]);
        $add = Models\VisitCampus::addNewVisit($r);

        if(isset($add)) {
          $preferred_uni = '';
          $add->uni_name = Models\University::getNameById($add->vc_uni_id);
          $add->date = $add->vc_date.' '.date('M', mktime(0, 0, 0, $add->vc_month, 10)).' '.$add->vc_year;
          $add->time = $time[$add->vc_time];

          $data = [
            'fullname' => $add->vc_salutation.' '.$add->vc_name,
            'ic_number' => $add->vc_ic_num,
            'contact_no' => $add->vc_contact_no,
            'pax' => $add->vc_pax,
            'date' => $add->vc_date.' '.date('M', mktime(0, 0, 0, $add->vc_month, 10)).' '.$add->vc_year,
            'time' => $time[$add->vc_time],
            'submission' => Carbon::parse($add->created_at)->format('d/m/Y H:i:s'),
          ];

          if(isset($add->vc_uni_id)) {
            $vc_uni = json_decode($add->vc_uni_id);
            foreach ($vc_uni as $uni) {
              $email = Models\UniversityEmail::getUniEmail($uni->uni_id,$uni->branch);
              \Log::info($email);
              \Log::info($uni->uni_id);
              \Log::info($uni->branch);
              if(isset($email)) {
                \Mail::to($email)->send(new \App\Mail\visitCampus($email,$data));
                \Mail::to('jaskeeratsinghgill@gmail.com')->send(new \App\Mail\visitCampus('jaskeeratsinghgill@gmail.com',$data));
              }
              $name = Models\University::getNameById($uni->uni_id);
              $branch = Models\UniversityBranch::getBranchById($uni->branch);
              
              if($uni->branch != 0) {
                $preferred_uni .= $name.' - '.$branch->state_name.', ';
              } else{
                $preferred_uni .= $name.', ';
              }
            }
            $add->uni = trim($preferred_uni, ', ');
          }
          return['status' => true, 'msg' => 'Visit record ave been added successfully', 'data' => $add];
        }
      }

    } catch(\Exception $e) {
      \Log::error($e);
    }
    return ['status' => false, 'msg' => Lang::get('lang.errSystem')];
  }

  public function scholarship($url)
  {
    try{
      if(isset($url)) {
        $scholarship = Models\Scholarship::getByUrlCode($url);
        $level = Models\Course::qlevelArr();
        $s_level = Models\Course::levelArr();
        $sub = Models\SubCat::getAll();
        $max_discount = 0;
        $return_max_discount = '';
        $return_discount = [];

        if(isset($scholarship)) {
          $applicable = '';
          $field = explode(',', $scholarship->s_study_field);

          if(isset($field) && count($field) > 0) {
            foreach ($field as $f) {
              $name = Models\SubCat::getNameById($f);
              $applicable .= $name.', ';
            }
          }

          $scholarship->applicable = trim($applicable, ', ');
          $scholarship->logo = $scholarship->s_is_corporate == 0 ? url('images/university/logo/'.$scholarship->u_logo) : url('images/scholarships/'.$scholarship->s_c_logo);

          $s_level_id = $scholarship->s_level_id; // Assuming $scholarship->s_level_id contains a string
          $s_level_ids = explode(',', $s_level_id);

          $levels = [];
          foreach ($s_level_ids as $id) {
              if (isset($s_level[$id])) {
                  $levels[] = $s_level[$id];
              }
          }
          $scholarship->level = $levels;
          $scholarship->has_all_cat = count($sub) == count($field) ? true : false;
          $discount = Models\ScholarshipDiscount::getByScholarshipId($scholarship->s_id);

          if(isset($discount) && count($discount) > 0) {
            foreach ($discount as $d) {
              $grade = Models\SubjectGrade::getGradeValue($d->sd_grade);

              if($d->sd_is_amt == 1) {
                if($d->sd_amt > $max_discount) {
                  // $max_discount = 'MYR '.Gen::moneyFormat($d->sd_amt);
                  $max_discount = $d->sd_amt;
                  $return_max_discount = 'MYR '.Gen::moneyFormat($max_discount);
                }
              } else {
                if($d->sd_percentage > $max_discount) {
                  // $max_discount = $d->sd_percentage.'%';
                  $max_discount = $d->sd_percentage;
                  $return_max_discount = $max_discount.'%';
                }
              }

              if($d->sd_is_manual == 0) {
                $type = 0;
                $return_discount[$level[$d->sd_level_id]][] = array(
                  'total' => $d->sd_is_above == 1 ? $d->sd_count.$grade.' and above' : $d->sd_count.$grade,
                  'grade' => $grade,
                  'amount' => $d->sd_is_amt == 1 ? 'MYR '.Gen::moneyFormat($d->sd_amt) : $d->sd_percentage.'%',
                );
              } else{
                $type = 1;
                $return_discount = $d;
              }
        
            }
          }
          // dd($return_discount);
          return view('member.scholarship', compact('scholarship','return_discount','return_max_discount', 'type'));
        }
      }
    } catch(\Exception $e) {
      \Log::error($e);
    }

    abort(404);
  }

  function cmp($a, $b) {
    if ($a == $b) {
        return 0;
    }
    return ($a > $b) ? -1 : 1;
  }

  public function characters()
  {
    try {
      // $id = 4;
      $id = Session::get('user_id');
      $u = Models\User::getById($id);
      $return_character = [];

      if(isset($u)){
        if($u->character == null || $u->character == '') {
          $character = explode(',', $u->personality);
          $personality_str = '';
          if(isset($character) && count($character) > 0) {
            foreach ($character as $c) {
              $personality = Models\Personality::getById($c);
              $personality_str .= $personality->p_character.',';
            }
            $personality_str = trim($personality_str, ',');
            $personality_arr = explode(',', $personality_str);
            $vals = array_count_values($personality_arr);
            uasort($vals, array($this, 'cmp'));
            $final = '';
            $save = [];

            if(isset($vals) && count($vals) > 0) {
              foreach($vals as $i => $v) {
                if(count($return_character) <= 2) {
                  $c = Models\Characters::getById($i);
                  $final .= $i.',';
                  $percentage = 0;

                  if(count($return_character) == 0) {
                    $num = rand(90,99);
                    $percentage = $num.'.'.rand(1,9);
                  }else if(count($return_character) == 1) {
                    $num = rand(89,85);
                    $percentage = $num.'.'.rand(1,9);
                  }else if(count($return_character) == 2) {
                    $num = rand(80,84);
                    $percentage = $num.'.'.rand(1,9);
                  }

                  $return_character[] = array(
                    'id' => $i,
                    'name' => $c->c_name,
                    'cat_name' => $c->sc_name,
                    'avatar' => $u->gender == 1 ? url('images/characters/'.$c->c_img_male) : url('images/characters/'.$c->c_img_female),
                    'desc' => $c->c_desc,
                    'percentage' => $percentage,
                    'avg_salary' => Gen::moneyFormat($c->c_aver_salary),
                  );

                  $save[] = array(
                    'id' => $i,
                    'percentage' => $percentage
                  );
                }
              }
            }
          }
          $final = trim($final, ',');
          $u->character = json_encode($save);
          $u->save();
        } else{
          // $character = explode(',', $u->character);
          $character = json_decode($u->character);

          if(isset($character) && count($character) > 0) {
            foreach($character as $ch) {
              $c = Models\Characters::getById($ch->id);

              $return_character[] = array(
                'id' => $c->c_id,
                'name' => $c->c_name,
                'cat_name' => $c->sc_name,
                'avatar' => $u->gender == 1 ? url('images/characters/'.$c->c_img_male) : url('images/characters/'.$c->c_img_female),
                'desc' => $c->c_desc,
                'percentage' => $ch->percentage,
                'avg_salary' => Gen::moneyFormat($c->c_aver_salary),
              );
            }
          }
        }
        // dd($return_character);
        $category = Models\Category::getAllActive();
        return view('member.characters', compact('return_character', 'category'));
      }
    } catch(\Exception $e) {
      \Log::error($e);
    }

    abort(404);
  }

  public function getSubcategories(Request $r)
  {
    $mainCategoryId = $r->main_category_id;

    $subcategories = Models\SubCat::getAllActive($mainCategoryId);

    if (isset($subcategories) && count($subcategories) > 0 ) {
      // return response()->json($subcategories);
      return ['status' => true, 'list' => $subcategories];
    }

    // return response()->json(['message' => 'No subcategories found for the selected main category.'], 404);
    return ['status' => false, 'msg' => 'Error'];
  }

  public function getRecommendedCourse(Request $r)
  {
    try {
      // $id = 26;
      $id = Session::get('user_id');
      $u = Models\User::getById($id);
      $course = Models\Course::getRecommendCourseJs($r);
      $academic = Models\Academic::getByUserId($id);
      $family = Models\Family::getByUserId($id);
      $result = Models\Result::getByUser($id);
      $return = $university = [];
      $ttl_course = $ttl_uni = 0;
      $slice = false;

      if(isset($course) && count($course) > 0) {
        $ttl_course = count($course);

        foreach ($course as $c) {
          $intake = explode(',',  $c->c_intake);
          $scholarship = Models\Scholarship::getAvailableScholarship($id, $c);
          // $list = '';
          $ptpn_amt = $ttl_scholarship = $ttl_pay_amount = 0;
          $total_s_amt = $list = [];

          foreach ($intake as $i) {
            // $list .= date('M', mktime(0, 0, 0, $i, 10)).' ';
            $list[] = date('M', mktime(0, 0, 0, $i, 10));
          }

          if($family->f_income == 1) {
            $ptpn_amt = $c->c_amount;
            $ptpn_percentage = '100%';
          } else if($family->f_income == 2) {
            $ptpn_amt = $c->c_amount * 0.75;
            $ptpn_percentage = '75%';
          } if($family->f_income == 3) {
            $ptpn_amt = $c->c_amount * 0.5;
            $ptpn_percentage = '50%';
          }

          if(isset($scholarship) && count($scholarship) > 0) {
            foreach ($scholarship as $s) {
              $field = explode(',', $s->s_study_field);
              $available = false;
              $count = 0;

              foreach($field as $f) {
                if($c->c_sub_cat == $f) {
                  $available = true;
                }
              }

              if($available) {
                if($s->sd_is_manual == 1) {
                  if($s->sd_is_amt == 1) {
                    $total_s_amt[] = $s->sd_amt;
                  } else if($s->sd_is_amt == 0) {
                    $total_s_amt[] = $c->c_amount * $s->sd_percentage / 100;
                  }
                } else if($s->sd_is_manual == 0) {
                  if($academic->a_qualification == $s->sd_level_id) {
                    foreach($result as $re) {
                      if($s->sd_is_above == 1) {
                        if($re->r_grade <= $s->sd_grade) {
                          $count++;
                        }
                      } else if($s->sd_is_above == 0) {
                        if($re->r_grade == $s->sd_grade) {
                          $count++;
                        }
                      }
                    }

                    if($count >= $s->sd_count) {
                      if($s->sd_is_amt == 1) {
                        $total_s_amt[] = $s->sd_amt;
                      } else if($s->sd_is_amt == 0) {
                        $total_s_amt[] = $c->c_amount * $s->sd_percentage / 100;
                      }
                    }
                  }
                }
              }
            }
          }
          
          if(isset($total_s_amt) && count($total_s_amt) > 0) {
            foreach ($total_s_amt as $a) {
              if($a > $ttl_scholarship) {
                $ttl_scholarship = $a;
              }
            }
          }

          $ttl_pay_amount = $c->c_amount - $ptpn_amt - $ttl_scholarship;

          $university[$c->u_id] = $c;
          $duration = $c->c_duration / 12;
          if ($duration != intval($duration)) {
              $durationString = number_format($duration, 1);
          } else {
              $durationString = $duration;
          }

          $return[] = array(
            'id' => $c->c_id,
            'url_code' => $c->c_url_code,
            'name' => $c->c_name,
            'uni_name' => $c->u_name,
            'uni_img' => url('images/university/logo/'.$c->u_logo),
            'intake' => $list,
            'duration' => $durationString . ' Years',
            'course_ttl' => Gen::moneyFormat($c->c_amount),
            'ptpn_ttl' => Gen::moneyFormat($ptpn_amt),
            'ptpn_percentage' => $ptpn_percentage,
            'household_income' => Models\Family::incomeArr($family->f_income),
            'ttl_scholarship' => Gen::moneyFormat($ttl_scholarship),
            'price_per_year' => $ttl_pay_amount < 1 ? 0 : Gen::moneyFormat($ttl_pay_amount / $duration),
            'total_pay_amt' => $ttl_pay_amount < 1 ? 0 : Gen::moneyFormat($ttl_pay_amount),
          );
        }

        if($u->email == null || $u->email == '') {
          $slice = true;
        }

        if($slice) {
          $return_array = array_slice($return, 0,3);
        } else{
          $return_array = $return;
        }
        
        $ttl_uni = count($university);
        return['status' => true, 'list' => $return_array, 'ttl_course' => $ttl_course, 'ttl_uni' => $ttl_uni, 'slice' => $slice];
      }
    }  catch(\Exception $e) {
      \Log::error($e);
    }

    return ['status' => false, 'msg' => Lang::get('lang.errSystem')];
  }

  public function getAllRecommendedCourse(Request $r)
  {
    try {
      // $id = 26;
      $id = Session::get('user_id');
      $u = Models\User::getById($id);
      $course = Models\Course::getAllRecommendCourseJs($r);
      $academic = Models\Academic::getByUserId($id);
      $family = Models\Family::getByUserId($id);
      $result = Models\Result::getByUser($id);
      $return = $university = [];
      $ttl_course = $ttl_uni = 0;
      $slice = false;

      if(isset($course) && count($course) > 0) {
        $ttl_course = count($course);

        foreach ($course as $c) {
          $intake = explode(',',  $c->c_intake);
          $scholarship = Models\Scholarship::getAvailableScholarship($id, $c);
          // $list = '';
          $ptpn_amt = $ttl_scholarship = $ttl_pay_amount = 0;
          $total_s_amt = $list = [];

          foreach ($intake as $i) {
            // $list .= date('M', mktime(0, 0, 0, $i, 10)).' ';
            $list[] = date('M', mktime(0, 0, 0, $i, 10));
          }

          if($family->f_income == 1) {
            $ptpn_amt = $c->c_amount;
            $ptpn_percentage = '100%';
          } else if($family->f_income == 2) {
            $ptpn_amt = $c->c_amount * 0.75;
            $ptpn_percentage = '75%';
          } if($family->f_income == 3) {
            $ptpn_amt = $c->c_amount * 0.5;
            $ptpn_percentage = '50%';
          }

          if(isset($scholarship) && count($scholarship) > 0) {
            foreach ($scholarship as $s) {
              $field = explode(',', $s->s_study_field);
              $available = false;
              $count = 0;

              foreach($field as $f) {
                if($c->c_sub_cat == $f) {
                  $available = true;
                }
              }

              if($available) {
                if($s->sd_is_manual == 1) {
                  if($s->sd_is_amt == 1) {
                    $total_s_amt[] = $s->sd_amt;
                  } else if($s->sd_is_amt == 0) {
                    $total_s_amt[] = $c->c_amount * $s->sd_percentage / 100;
                  }
                } else if($s->sd_is_manual == 0) {
                  if($academic->a_qualification == $s->sd_level_id) {
                    foreach($result as $re) {
                      if($s->sd_is_above == 1) {
                        if($re->r_grade <= $s->sd_grade) {
                          $count++;
                        }
                      } else if($s->sd_is_above == 0) {
                        if($re->r_grade == $s->sd_grade) {
                          $count++;
                        }
                      }
                    }

                    if($count >= $s->sd_count) {
                      if($s->sd_is_amt == 1) {
                        $total_s_amt[] = $s->sd_amt;
                      } else if($s->sd_is_amt == 0) {
                        $total_s_amt[] = $c->c_amount * $s->sd_percentage / 100;
                      }
                    }
                  }
                }
              }
            }
          }
          
          if(isset($total_s_amt) && count($total_s_amt) > 0) {
            foreach ($total_s_amt as $a) {
              if($a > $ttl_scholarship) {
                $ttl_scholarship = $a;
              }
            }
          }

          $ttl_pay_amount = $c->c_amount - $ptpn_amt - $ttl_scholarship;

          $university[$c->u_id] = $c;
          $duration = $c->c_duration / 12;
          if ($duration != intval($duration)) {
              $durationString = number_format($duration, 1);
          } else {
              $durationString = $duration;
          }

          $return[] = array(
            'id' => $c->c_id,
            'url_code' => $c->c_url_code,
            'name' => $c->c_name,
            'uni_name' => $c->u_name,
            'uni_img' => url('images/university/logo/'.$c->u_logo),
            'intake' => $list,
            'duration' => $durationString . ' Years',
            'course_ttl' => Gen::moneyFormat($c->c_amount),
            'ptpn_ttl' => Gen::moneyFormat($ptpn_amt),
            'ptpn_percentage' => $ptpn_percentage,
            'household_income' => Models\Family::incomeArr($family->f_income),
            'ttl_scholarship' => Gen::moneyFormat($ttl_scholarship),
            'price_per_year' => $ttl_pay_amount < 1 ? 0 : Gen::moneyFormat($ttl_pay_amount / $duration),
            'total_pay_amt' => $ttl_pay_amount < 1 ? 0 : Gen::moneyFormat($ttl_pay_amount),
          );
        }

        if($u->email == null || $u->email == '') {
          $slice = true;
        }

        if($slice) {
          $return_array = array_slice($return, 0,3);
        } else{
          $return_array = $return;
        }
        
        $ttl_uni = count($university);
        return['status' => true, 'list' => $return_array, 'ttl_course' => $ttl_course, 'ttl_uni' => $ttl_uni, 'slice' => $slice];
      }
    }  catch(\Exception $e) {
      \Log::error($e);
    }

    return ['status' => false, 'msg' => Lang::get('lang.errSystem')];
  }

  public function registerInterest()
  {
    $state = Models\State::getAllActive(129);
    $banner = Models\Banner::allActive();
    return view('member.registerInterest', compact('state','banner'));
  }

  public function courseDetails($code)
  {
    try {
      if(isset($code)) {
        $course = Models\Course::getByUrlCode($code);

        if(isset($course)) {
          // $grade = Models\CourseGrade::getById($course->c_id);
          $fees = Models\CourseFee::getAllFee($course->c_id);
          $deposit = Models\CourseFee::getAllDeposit($course->c_id);
          $grade = Models\CourseLevelDesc::getById($course->c_id);
          $level = Models\Course::qlevelArr();
          $recommendation = Models\Course::getCourseByMainCatLimit($course->c_main_cat);
          $intake = explode(',',  $course->c_intake);
          $cat = Models\Category::getById($course->c_main_cat);
          $list = '';
          // $return_grade = [];
          $return_gradeDesc = [];

          foreach ($intake as $i) {
            $list .= date('M', mktime(0, 0, 0, $i, 10)).', ';
          }

          if($course->c_has_internship == 1) {
            $intern_duration = explode('.', $course->c_internship_duration);

            // if($intern_duration[0] == 0) {
            //   $course->intership = 'Yes, '.$intern_duration[1].' months';
            // } else{
            //   $course->intership = 'Yes, '.$intern_duration[0].' months';
            // }
            $course->intership = 'Yes, '.$intern_duration[0].' months';
          } else{
            $course->intership = 'No';
          }

          // if(isset($grade) && count($grade) > 0) {
          //   foreach ($grade as $g) {
          //     $subject = json_decode($g->cg_grades);
          //     $subject_list = '';
          //     if(isset($subject) && count($subject) > 0) {
          //       foreach ($subject as $s) {
          //         $subject_list .= Models\Subject::getSubjectName($s->subject).' '.Models\SubjectGrade::getGradeValue($s->grades).', ';
          //       }
          //       $subject_list = trim($subject_list, ', ');
          //     }

          //     $return_grade[] = array(
          //       'id' => $g->cg_id,
          //       'level' => $level[$g->cg_level],
          //       'grade' => $g->cg_min.' '.$g->sg_grade,
          //       'subject' => $subject_list
          //     );
          //   }
          // }
          if(isset($grade) && count($grade) > 0) {
            $sortedGrade = $grade->sortBy('c_level_id');
            foreach ($sortedGrade as $g) {
              $levelName = Models\Course::qLevelArr($g->c_level_id);
              $return_gradeDesc[] = array(
                'id' => $g->c_level_desc_id,
                'level' => $levelName,
                'desc' => $g->c_level_desc,
              );
            }
          }

          $course->uni_logo = url('images/university/logo/'.$course->u_logo);
          $course->intake = trim($list, ', ');
          $course->duration = $course->c_duration.' Months';
          $course->background_img = url('images/category/'.$cat->c_img);

          $course->core = json_decode($course->c_core);
          $course->elective = json_decode($course->c_elective);
          $course->other = json_decode($course->c_other);

          if(isset($recommendation) && count($recommendation) > 0) {
            foreach($recommendation as $r) {
              $r_intake = explode(',',  $r->c_intake);
              $r_list = '';
              $duration = $r->c_duration / 12;
              if ($duration != intval($duration)) {
                  $durationString = number_format($duration, 1);
              } else {
                  $durationString = $duration;
              }

              foreach ($r_intake as $i) {
                $r_list .= date('M', mktime(0, 0, 0, $i, 10)).' ';
              }
              $r->intake = trim($r_list, ' ');
              $r->duration = $durationString .' Years';
              $r->uni_logo  = url('images/university/logo/'.$r->u_logo);
              $r->ttl_fees = Gen::moneyFormat($r->c_amount);
              $r->deals = '0.00';
              $r->scholarship = '0.00';
              $r->ttl_amt = Gen::moneyFormat($r->c_amount);
            }
          }
          // dd($course);
          return view('member.courseDetails', compact('course', 'recommendation', 'return_gradeDesc', 'fees', 'deposit'));
        }
      }
    } catch(\Exception $e) {
      \Log::error($e);
    }

    abort(404);
  }

  public function getCoursePrice(Request $r)
  {
    try {
      if($r->id) {
        $fees = Models\CourseFee::getAllFee($r->id);
        $deposit = Models\CourseFee::getAllDeposit($r->id);
        $c = Models\Course::getById($r->id);
        $return_fees = $return_deposit = [];
        $uni_fee = $total = $total_fee = 0;
        $type = 0;

        if($r->type != null) {
          $type = $r->type;
        }

        if(isset($fees) && count($fees) > 0) {
          foreach ($fees as $f) {
            $fee_amount = 0;
            if($type == 1) {
              $fee_amount = $f->cf_amount;
            } else if($type == 2) {
              $fee_amount = $f->cf_amount_bumi;
            } else if($type == 3) {
              $fee_amount = $f->cf_amount_international;
            }
            $total_fee += $fee_amount;

            $return_fees[] = array(
              'id' => $f->cf_id,
              'name' => $f->cf_name,
              'amt' => $type != 0 ? 'MYR '.Gen::moneyFormat($fee_amount) : '-',
            );
          }
        } 

        if(isset($deposit) && count($deposit) > 0) {
          foreach ($deposit as $d) {
            $deposit_amount = 0;
            if($type == 1) {
              $deposit_amount = $d->cf_amount;
            } else if($type == 2) {
              $deposit_amount = $d->cf_amount_bumi;
            } else if($type == 3) {
              $deposit_amount = $d->cf_amount_international;
            }
            
            $return_deposit[] = array(
              'id' => $d->cf_id,
              'name' => $d->cf_name,
              'amt' => $type != 0 ? 'MYR '.Gen::moneyFormat($deposit_amount).' / '.Models\CourseFee::frequencyArr($d->cf_payment_frequency) : '-',
            );
          }
        }

        if($type == 1) {
          $uni_fee = $c->c_amount;
        } else if($type == 2) {
          $uni_fee = $c->c_amount_bumi;
        } else if($type == 3) {
          $uni_fee = $c->c_amount_international;
        }

        $total = $uni_fee + $total_fee;

        return['status' => true, 'fees' => $return_fees, 'deposit' => $return_deposit, 'uni_fee' => 'MYR '.Gen::moneyFormat($uni_fee), 'total' => $total];
      }
    } catch(\Exception $e) {
      \Log::error($e);
    }
    return ['status' => false, 'msg' => Lang::get('lang.errSystem')];
  }

  public function k12Details($code)
  {
    try {
      if(isset($code)) {
        $uni = Models\InternationalSchool::getByUrl($code);
        \Log::info($uni);
        if(isset($uni)) {
          $img = $intake_return = $category = [];
          $images = explode(',', $uni->int_img);
          $time = explode('~', $uni->int_hours);
          $level = Models\InternationalSchool::levelArr();
          $month = Models\Course::monthArr();

          if(isset($images) && count($images) > 0) {
            foreach($images as $i) {
              $img[] = url('images/internationalschool/images/'.$i);
            }
          }

          $uni->img = $img;
          $uni->address = $uni->int_addr.', '.Models\State::getNameById($uni->int_state).', '.Models\Country::getNameById($uni->int_country);
          $uni->operating_hours = date("g:i a", strtotime($time[0])).' - '.date("g:i a", strtotime($time[1]));
          $uni->u_logo = url('images/internationalschool/logo/'.$uni->int_logo);

          return view('member.k12Details', compact('uni'));
        }
      }
    } catch(\Exception $e) {
      \Log::error($e);
    }

    abort(404);
  }

  public function getScholarshipBySchool(Request $r)
  {
    try{
      if(isset($r->id)) {
        $scholarship = Models\IntScholarship::getByUniId($r->id);
        // $sub = Models\SubCat::getAll();
        $requirement = Models\Scholarship::requirementArr();
        $return = [];

        if(isset($scholarship) && count($scholarship) > 0) {
          foreach($scholarship as $s) {
            $discount = Models\IntScholarshipDiscount::getByScholarshipId($s->s_id);
            $type = $max_discount = 0;
            $return_max_discount = '';

            if(isset($discount) && count($discount) > 0) {
              $max_discount = 0;
              foreach ($discount as $d) {
                if($d->sd_is_amt == 1) {
                  if($d->sd_amt > $max_discount) {
                    $max_discount = $d->sd_amt;
                    $return_max_discount = 'MYR '.Gen::moneyFormat($max_discount);
                  }
                  $type = 1;
                } else {
                  if($d->sd_percentage > $max_discount) {
                    $max_discount = $d->sd_percentage;
                    $return_max_discount = $max_discount.'%';
                  }
                  $type = 2;
                }
              }
            }

            $req = explode(',', $s->s_requirement);
            $r_list = '';

            if(isset($req) && count($req) > 0) {
              foreach($req as $re) {
                if($s->s_requirement != 0) {
                  $r_list .= $requirement[$re].', ';
                }
              }
            }

            $return[] = array(
              'id' => $s->s_id,
              'url_code' => $s->s_url_code,
              'name' => $s->s_title,
              'uni_name' => $s->int_name,
              'uni_logo' => url('images/internationalschool/logo/'.$s->int_logo),
              'has_interview' => $s->s_has_interview == 1 ? 'Yes' : 'No',
              'requirement' => trim($r_list,', '),
              'max_discount' => $return_max_discount,
              'type' => $type,
              'amount' => isset($max_discount) ? $max_discount : 0,
            );
          }

          return['status' => true, 'list' => $return];
        }
      }
    } catch(\Exception $e) {
      \Log::error($e);
    }
    return ['status' => false, 'msg' => Lang::get('lang.errSystem')];
  }
  
  public function getk12ScholarshipDetails($url)
  {
    try{
      if(isset($url)) {
        $scholarship = Models\IntScholarship::getByUrlCode($url);
        $level = Models\Course::qlevelArr();
        $s_level = Models\Course::levelArr();
        $sub = Models\SubCat::getAll();
        $max_discount = 0;
        $return_max_discount = '';
        $return_discount = [];

        if(isset($scholarship)) {

          $scholarship->logo = $scholarship->s_is_corporate == 0 ? url('images/internationalschool/logo/'.$scholarship->int_logo) : url('images/scholarships/'.$scholarship->s_c_logo);
          $discount = Models\IntScholarshipDiscount::getByScholarshipId($scholarship->s_id);

          if(isset($discount) && count($discount) > 0) {
            foreach ($discount as $d) {
              $grade = Models\SubjectGrade::getGradeValue($d->sd_grade);

              if($d->sd_is_amt == 1) {
                if($d->sd_amt > $max_discount) {
                  // $max_discount = 'MYR '.Gen::moneyFormat($d->sd_amt);
                  $max_discount = $d->sd_amt;
                  $return_max_discount = 'MYR '.Gen::moneyFormat($max_discount);
                }
              } else {
                if($d->sd_percentage > $max_discount) {
                  // $max_discount = $d->sd_percentage.'%';
                  $max_discount = $d->sd_percentage;
                  $return_max_discount = $max_discount.'%';
                }
              }

              if($d->sd_is_manual == 0) {
                $type = 0;
                $return_discount[$level[$d->sd_level_id]][] = array(
                  'total' => $d->sd_is_above == 1 ? $d->sd_count.$grade.' and above' : $d->sd_count.$grade,
                  'grade' => $grade,
                  'amount' => $d->sd_is_amt == 1 ? 'MYR '.Gen::moneyFormat($d->sd_amt) : $d->sd_percentage.'%',
                );
              } else{
                $type = 1;
                $return_discount = $d;
              }
        
            }
          }
          // dd($return_discount);
          return view('member.k12ScholarshipDetails', compact('scholarship','return_discount','return_max_discount', 'type'));
        }
      }
    } catch(\Exception $e) {
      \Log::error($e);
    }

    abort(404);
  }

  public function getUserProfileJs() 
  {
    try {
      // $id = 4;
      $id = Session::get('user_id');
      $u = Models\User::getById($id);
      $result_return = Models\Result::getUserGrades($id);
      $character = json_decode($u->character);
      $document = Models\UserDocument::getUserDocument($id);
      $return = $return_character = $profile = $resume = $result = $ic_front = $ic_back = [];

      if(isset($u)) {
        if(isset($result_return) && count($result_return) > 0) {
          foreach($result_return as $r) {
            if($r->r_grade_type == 1) {
              $return[] = array(
                'id' => $r->r_id,
                'name' => $r->s_name,
                'grade' => $r->sg_grade,
              );
            } else if($r->r_grade_type == 2) {
              $return[] = array(
                'id' => $r->r_id,
                'name' => 'CGPA',
                'grade' => $r->r_cgpa,
              );
            }
          }
        }

        if(isset($character) && count($character) > 0) {
          foreach ($character as $c) {
            $ch = Models\Characters::getById($c->id);
            $return_character[] = array(
              'id' => $ch->c_id,
              'name' => $ch->c_name,
              'img' => $u->gender == 1 ? url('images/characters/'.$ch->c_img_male) : url('images/characters/'.$ch->c_img_female),
            );
          }
        }

        if(isset($document) && count($document) > 0) {
          foreach ($document as $d) {
            if($d->ud_type == 1) {
              $ic_front[] = array(
                'img' => $d->ud_document,
                'status' => $d->ud_status,
              );
            }
            if($d->ud_type == 2) {
              $ic_back[] = array(
                'img' => $d->ud_document,
                'status' => $d->ud_status,
              );
            }
            if($d->ud_type == 3) {
              $result[] = array(
                'id' => $d->ud_id,
                'name' => $d->ud_document,
                'img' => url('/images/document/'.$d->ud_document),
                'size' => File::size(public_path('/images/document/'.$d->ud_document)),
              );
            }
            if($d->ud_type == 4) {
              $resume[] = array(
                'id' => $d->ud_id,
                'name' => $d->ud_document,
                'img' => url('/images/document/'.$d->ud_document),
                'size' => File::size(public_path('/images/document/'.$d->ud_document)),
              );
            }
          }
        }

        $profile = [
          'id' => $u->id,
          'name' => $u->username,
          'contact_no' => $u->contact_code.$u->contact_no,
          'email' => $u->email,
          'profile_pic' => $u->profile_pic,
          'ic_front' => $ic_front,
          'ic_back' => $ic_back,
          'result' => $result,
          'resume' => $resume,
        ];
        \Log::info($return);
        return['status' => true, 'result' => $return, 'character' => $return_character, 'details' => $profile];
      }

    }catch(\Exception $e) {
      \Log::error($e);
    }
    return ['status' => false, 'msg' => Lang::get('lang.errSystem')];
  }

  public function forgetPassword(Request $r)
  {
    try {
      $id = Session::get('user_id');
      $u = Models\User::getById($id);

      if(isset($u)) {
        if($r->curr_pass != null && $r->pass != null && $r->confirm_pass != null) {
          $curr_pass = decrypt($u->password);

          if(strlen($r->pass) < 8) {
            return['status' => false, 'msg' => 'Password length should contain atleast 8 characters'];
          } else if(!preg_match('/[A-Z]/', $r->pass)){
            return['status' => false, 'msg' => 'Password should contain atlease 1 uppercase characters'];
          } else if(!preg_match('~[0-9]+~', $r->pass)){
            return['status' => false, 'msg' => 'Password should contain atlease 1 number'];
          } else if(!preg_match('/[\'^£$%&*()}{@#~?><>,|=_+¬-]/', $r->pass)){
            return['status' => false, 'msg' => 'Password should contain atlease 1 special character'];
          }

          if($curr_pass == $r->curr_pass) {
            if($r->pass == $r->confirm_pass) {
              $u->password = encrypt($r->pass);
              $u->save();

              return['status' => true, 'msg' => 'Password have been updated successfully'];
            }else{
              return ['status' => false, 'msg' => 'Password and repreat password does not match'];
            }
          }else{
            return['status' => false, 'msg' => 'Current password is incorrect'];
          }
        }
      }
    } catch(\Exception $e) {
      \Log::error($e);
    }
    return ['status' => false, 'msg' => Lang::get('lang.errSystem')];
  }

  public function updateProfile(Request $r)
  {
    try{
      $id = Session::get('user_id');
      $u = Models\User::getById($id);

      if(isset($u)) {
        $contact_no = $r->contact_no;

        if (substr($contact_no, 0, 1) == '0') {
          $contact_no = substr($contact_no, 1);
        } else if (substr($contact_no, 0, 2) == '60') {
          $contact_no = substr($contact_no, 2);
        } else {
          $contact_no = $contact_no;
        }

        $u->username = $r->name;
        $u->contact_no = $contact_no;

        if($r->profile_pic != null) {
          $img = "profile-" . uniqid() . '.jpg';

          $r->profile_pic->move(
            public_path('/images/profile'), $img
          );
          
          $u->profile_pic = $img;
        }

        $u->save();

        return['status' => true, 'msg' => 'User details have been updated'];
      }
    } catch(\Exception $e) {
      \Log::error($e);
    }
    return ['status' => false, 'msg' => Lang::get('lang.errSystem')];
  }

  public function uploadDocument(Request $r)
  {
    // \Log::info('test 1');
    // \Log::info($r->all());
  }
  
  public function submitUserDocument(Request $r)
  {
    try {
      $add = Models\UserDocument::addDocument($r);

      if(isset($add)) {
        return['status' => true, 'msg' => 'User document have been added successfully'];
      }
    } catch(\Exception $e) {
      \Log::error($e);
    }
    return ['status' => false, 'msg' => Lang::get('lang.errSystem')];
  }

  public function deleteUserDocument(Request $r)
  {
    try{
      if(isset($r->id) && $r->id != 'undefined') {
        $doc = Models\UserDocument::getById($r->id);

        if(isset($doc)) {
          $doc->ud_status = 99;
          $doc->save();

          return['status' => true, 'msg' => 'File removed successfully'];
        }
      }
    } catch(\Exception $e) {
      \Log::error($e);
    }
    return ['status' => false, 'msg' => Lang::get('lang.errSystem')];
  }

  public function courseApplication($url)
  {
    try {
      if(isset($url)) {
        $c = Models\Course::getByUrlCode($url);
        $qualification = Models\Course::qlevelArr();

        if(isset($c)) {
          $c->amt = 'MYR '.Gen::moneyFormat($c->c_amount);
          return view('member.courseApplication', compact('c','qualification'));
        }
      }
    } catch(\Exception $e) {
      \Log::error($e);
    }
    abort(404);
  }

  public function submitCourseApplication(Request $r)
  {
    try {
      \Log::info($r->all());
      $error = false;
      $msg = '';

      // if($r->name == null) {
      //   $error = true;
      //   $msg = 'Please enter your name';
      // } else if($r->ic_num == null) {
      //   $error = true;
      //   $msg = 'Please enter your IC number';
      // } else if($r->contact_no == null) {
      //   $error = true;
      //   $msg = 'Please enter your contact number';
      // } else if($r->school_name == null) {
      //   $error = true;
      //   $msg = 'Please enter your school name';
      // }

      if(!$error) {
        // $add = Models\CourseApplication::addApplication($r);

        // if(isset($add)) {
          // $data = Models\CourseApplication::getById($add->ca_id);
          $data = Models\CourseApplication::getById(3);
          $explode_dob = explode('-', $data->ca_dob);

          $data->amt = 'MYR '.Gen::moneyFormat($data->c_amount);
          $data->gender = Models\CourseApplication::genderArr($data->ca_gender);
          $data->nationality = Models\CourseApplication::nationalityArr($data->ca_nationality);
          $data->martial_status = Models\CourseApplication::martialArr($data->ca_martial_status);
          $data->ethnicity = Models\CourseApplication::ethnicityArr($data->ca_ethnicity);
          $data->religion = Models\CourseApplication::religionArr($data->ca_religion);
          $data->relationship = Models\CourseApplication::relationshipArr($data->ca_e_relationship);
          $data->credit = Models\CourseApplication::creditArr($data->ca_credit_transfer);
          $data->family = Models\CourseApplication::familyArr($data->ca_first_family);
          $data->e_qualification = Models\CourseApplication::englishQualificationArr($data->ca_e_qualification);
          $data->income = Models\CourseApplication::incomeArr($data->ca_h_income);
          $data->disability = Models\CourseApplication::disabilityArr($data->ca_has_disability);
          $data->qualification = Models\Course::qlevelArr($data->ca_qualification);
          $data->dob = $explode_dob[0].' / '.$explode_dob[1].' / '.$explode_dob[2];
          $data->intake_month = date('M', mktime(0, 0, 0, $data->ca_programme_month, 10));

          return['status' => true, 'data' => $data];
        // }
      } else{
        return['status' => false, 'msg' => $msg];
      }
    } catch(\Exception $e) {
      \Log::error($e);
    }
    return ['status' => false, 'msg' => Lang::get('lang.errSystem')];
  }

  public function consultCounsellor($url)
  {
    try {
      if(isset($url)) {
        $c = Models\Course::getByUrlCode($url);
        $qualification = Models\Course::qlevelArr();

        if(isset($c)) {
          return view('member.consultCounsellor', compact('c','qualification'));
        }
      }
    } catch(\Exception $e) {
      \Log::error($e);
    }
    abort(404);
  }

  public function submitConsultConsellor(Request $r)
  {
    try {
      $error = false;
      $msg = '';

      // if($r->name == null) {
      //   $error = true;
      //   $msg = 'Please enter your name';
      // } else if($r->email == null) {
      //   $error = true;
      //   $msg = 'Please enter your email address';
      // } else if($r->contact_no == null) {
      //   $error = true;
      //   $msg = 'Please enter your contact number';
      // }

      if(!$error) {
        $add = Models\ConsultCounsellor::addConsult($r);

        if(isset($add)) {
          $data = Models\ConsultCounsellor::getById($add->cc_id);
          $data->qualification = Models\Course::qlevelArr($add->cc_qualification);
          $data->gender = Models\ConsultCounsellor::genderArr($add->cc_gender);
          $dob_explode = explode('-', $add->cc_dob);
          $data->dob = $dob_explode[0].' / '.$dob_explode[1].' / '.$dob_explode[2];

          return['status' => true, 'data' => $data];
        }

      } else{
        return['status' => false, 'msg' => $msg];
      }

    } catch(\Exception $e) {
      \Log::error($e);
    }
    return ['status' => false, 'msg' => Lang::get('lang.errSystem')];
  }

  public function compareCourse()
  {
    $uni = Models\University::getAll();

    return view('member.compareCourse', compact('uni'));
  }

  public function getCompareCourses(Request $r)
  {
    try {
      $course = Models\Course::getCompareCourse($r);
      $return = [];

      if(isset($course) && count($course) > 0) {
        foreach ($course as $c) {

          $intake = explode(',',  $c->c_intake);
          $list = '';

          foreach ($intake as $i) {
            $list .= date('M', mktime(0, 0, 0, $i, 10)).' ';
          }

          $duration = $c->c_duration / 12;
          if ($duration != intval($duration)) {
              $durationString = number_format($duration, 1);
          } else {
              $durationString = $duration;
          }

          $return[] = array(
            'id' => $c->c_id,
            'name' => $c->c_name,
            'uni_name' => $c->u_name,
            'uni_logo' => url('images/university/logo/'.$c->u_logo),
            'duration' => $durationString .' Years',
            'intake' => trim($list, ' '),
            'scholarship' => '?',
            'deals' => '0',
            'ttl_amt' => Gen::moneyFormat($c->c_amount),
          );
        }
        return['status' => true, 'list' => $return];
      }
    } catch(\Exception $e) {
      \Log::error($e);
    }
    return ['status' => false, 'msg' => Lang::get('lang.errSystem')];
  }

  public function getCourseInfo(Request $r)
  {
    $courseID = $r->input('c_id');

    try{
      $course = Models\Course::getCourseInfo($courseID);
      $return = [];

        if ($course) {
          
        $intake = explode(',', $course->c_intake);
        $monthArr = Models\Course::monthArr();
        $intakemonths = [];

          foreach ($intake as $i) {
            if (isset($monthArr[$i])) {
                $intakemonths[] = $monthArr[$i];
            } else {
                $monthNames[] = 'N/A';
            }
          }

          $intakeString = implode(', ', $intakemonths);

        $levelArr = Models\Course::levelArr();
        $levelId = $course->c_level;
        $levelName = $levelArr[$levelId];

        $qlevelString = explode(',', $course->grades);
        $qlevelArr = Models\Course::qlevelArr();
        $qlevelValues = array_map(function($grade) use ($qlevelArr) {
            return $qlevelArr[$grade] ?? null;
        }, $qlevelString);
        $entryreq = implode(', ', $qlevelValues);

        $englishReq = ($course->c_english_required == 1) ? 'Yes' : 'No';

        $sponsor = ($course->s_is_corporate == 1) ? 'Corporate' : 'University';

        $interview = ($course->s_has_interview == 1) ? 'Yes' : 'No';

        $type = explode(',', $course->s_type);

        $typeArr = Models\Scholarship::typeArr();
         foreach ($type as $t) {
          if (isset($typeArr[$t])) {
            $types[] = $typeArr[$t];
          } else {
            $types[] = 'N/A';
          }
         }

          $offerScholarships = $course->offer_scholarships;
          $finalPercentage = [];
          $finalAmount = [];

          foreach (explode(',', $offerScholarships) as $scholarshipId) {
              $scholarshipDiscount = Models\ScholarshipDiscount::where('sd_s_id', $scholarshipId)->first();

              if ($scholarshipDiscount) {
                  if ($scholarshipDiscount->sd_is_amt == 0) {
                      $finalPercentage[] = $scholarshipDiscount->sd_percentage;
                  }
                  elseif ($scholarshipDiscount->sd_is_amt == 1) {
                      $finalAmount[] = $scholarshipDiscount->sd_amt;
                  }
              }
          }

          $finalPercentageResult = implode(', ', $finalPercentage);
          $finalAmountResult = implode(', ', $finalAmount);

          $highestPercentage = !empty($finalPercentage) ? max($finalPercentage) : null;
          $highestAmount = !empty($finalAmount) ? max($finalAmount) : null;

          if ($highestPercentage !== null && $highestAmount !== null) {
                $coverage = 'Up to MYR ' . Gen::moneyFormat($highestAmount) . ' or ' . $highestPercentage . '%';
            } elseif ($highestPercentage !== null) {
                $coverage = 'Up to ' . $highestPercentage . '%';
            } elseif ($highestAmount !== null) {
                $coverage = 'Up to MYR ' . Gen::moneyFormat($highestAmount);
            } else {
                $coverage = 'No scholarship';
            }
          

        // Build the array with course information
        $return[] = [
            'id' => $course->c_id,
            'name' => $course->c_name,
            'url' => $course->c_url_code,
            'uni_name' => $course->u_name,
            'uni_url' => $course->u_url_code,
            'uni_logo' => url('images/university/logo/'.$course->u_logo),
            'country' => $course->country_name,
            'state' => $course->state_name,
            'maincat' => $course->category_name,
            'subcat' => $course->sc_name,
            'level' => $levelName,
            'intake' => $intakeString,
            'entryreq' => $entryreq,
            'english' => $englishReq,
            'duration' => ($course->c_duration / 12).' Years',
            'amtnonbumi' => 'MYR ' . Gen::moneyFormat($course->c_amount),
            'amtbumi' => 'MYR ' . Gen::moneyFormat($course->c_amount_bumi),
            'amtint' => 'MYR ' . Gen::moneyFormat($course->c_amount_international),
            'scholarship_award' => $course->c_award,
            // 'scholarship_title' => $course->s_title,
            // 'scholarship_interview' => $interview,
            // 'scholarship_type' => $types,
            'scholarship_coverage' => $coverage
        ];
    }

    return['status' => true, 'list' => $return];

    } catch(\Exception $e) {
      \Log::error($e);
    }
    return ['status' => false, 'msg' => Lang::get('lang.errSystem')];
  }

  public function blog()
  {
    return view('member.blog');
  }

  public function blogDetails()
  {
    return view('member.blogDetails');
  }

  public function events()
  {
    return view('member.events');
  }

  public function eventsDetails()
  {
    return view('member.eventsDetails');
  }

  public function terms()
  {
    return view('member.terms');
  }

  public function privacy()
  {
    return view('member.privacy');
  }

  public function careers()
  {
    return view('member.careers');
  }

  public function contactUs()
  {
    return view('member.contactUs');
  }

  public function submitContact(Request $r)
  {
    try {
      $error = false;
      $msg = '';

      if($r->name == null) {
        $error = true;
        $msg = 'Please enter your name';
      } else if($r->email == null) {
        $error = true;
        $msg = 'Please enter your email';
      } else if($r->contact_no == null) {
        $error = true;
        $msg = 'Please enter your contact number';
      } else if($r->message == null) {
        $error = true;
        $msg = 'Please enter your message';
      }

      if(!$error) {
        $add = Models\Enquiry::addEnquiry($r);

        if(isset($add)) {
          return['status' => true, 'msg' => 'Your enquiry have been recorded. We will get in touch with you soon'];
        }
      } else{
        return['status' => false, 'msg' => $msg];
      }
    } catch(\Exception $e) {
      \Log::error($e);
    }
    return ['status' => false, 'msg' => Lang::get('lang.errSystem')];
  }

  public function submitInterest(Request $r)
  {
    try{
      if($r->name == null){
        return['status' => false, 'msg' => 'Please enter fullname'];
      }

      if($r->email == null){
        return['status' => false, 'msg' => 'Please enter email address'];
      }

      if($r->contact == null){
        return['status' => false, 'msg' => 'Please enter contact number'];
      }

      if($r->birthday == null){
        return['status' => false, 'msg' => 'Please enter birthday'];
      }

      if($r->school == null){
        return['status' => false, 'msg' => 'Please enter secondary school name'];
      }

      if($r->state == null){
        return['status' => false, 'msg' => 'Please enter state of residence'];
      }

      if($r->course == null){
        return['status' => false, 'msg' => 'Please enter course interested'];
      }

      if($r->uni == null){
        return['status' => false, 'msg' => 'Please enter university interested'];
      }

      if($r->level == null){
        return['status' => false, 'msg' => 'Please enter level of study interested'];
      }

      $add = Models\RegisterInterest::addInterest($r);
      $level = Models\RegisterInterest::levelArr();

      if(isset($add)){

        $add->level = $level[$add->ri_level];

        return['status' => true, 'msg' => 'Your registered interested have been saved successfully', 'data' => $add];
      }
    } catch(\Exception $e) {
      \Log::error($e);
    }
    return ['status' => false, 'msg' => Lang::get('lang.errSystem')];
  }

  public function viewRegisterInterest()
  {
    return view('member.viewRegisterInterest');
  }

  public function getRegisterInterest(Request $r)
  {
    try{
      $list = Models\RegisterInterest::getInterestList($r);
      $total = Models\RegisterInterest::getTotalInterest($r);
      $level = Models\RegisterInterest::levelArr();

      if(isset($list) && count($list) > 0){
        foreach ($list as $l) {
          $l->level = $level[$l->ri_level];
        }
        return['Data' => $list, 'TotalRecords' => $total, 'RecordsFiltered' => $total];
      }
    } catch(\Exception $e) {
      \Log::error($e);
    }
    return ['Data' => array(), 'TotalRecords' => 0, 'RecordsFiltered' => 0];
  }

  public function addBanner()
  {
    return view('member.addBanner');
  }

  public function submitAddBanner(Request $r)
  {
    try{
      if($r->banner_img != null){
        $m = Models\Banner::allActive();

        if(isset($m)){
          $m->banner_status = 99;
          $m->save();
        }
        $add = Models\Banner::addBanner($r);

        if(isset($add)){
          return['status' => true, 'msg' => 'New Banner added successfully'];
        }
      }
    } catch(\Exception $e) {
      \Log::error($e);
    }
    return ['status' => false, 'msg' => Lang::get('lang.errSystem')];
  }

  public function logout()
  {
    Auth::logout();
    return redirect('/');
  }

  public function testEmail()
  {
    \Mail::to('jaskeeratsinghgill@gmail.com')->send(new \App\Mail\visitCampus('jaskeeratsinghgill@gmail.com'));
  }
}
