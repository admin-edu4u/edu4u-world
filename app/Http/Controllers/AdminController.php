<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\Input;
use Illuminate\Http\Request;

use Mail;
use Lang;
use Excel;
use Carbon\Carbon;

use Session;
use Auth;
use App\Models\Gen;
use \App\Models;

use function PHPSTORM_META\map;

class AdminController extends Controller {

  public function login()
  {
    return view('admin.sign-in');
  }

  public function adminLogin()
  {
      try{
        if(request('username') !== null && request('password') !== null){
          if (Auth::attemptUsername(request('username'), request('password'))) {
  
              if (Auth::isAdmin(request('username'))) { //if admin, redirect to admin page
                  return redirect('/admin/dashboard');
              }
            }
        }
      }catch(\Exception $e) {
        \Log::error($e);
      }
      return redirect('/admin/sign-in')->with('error', 'Invalid credentials');  
  }

  public function adminLogout()
  {
        Auth::logout(); // Log out the authenticated user
        return redirect('/admin/sign-in'); // Redirect to the login page or any other desired page after logout
  }

  public function dashboard()
  {
    return view('admin.dashboard');
  }

  public function popup()
  {
    return view('admin.popup');
  }

  public function updatePopUp(Request $r)
  {
    try{
      if($r->img == null){
        return redirect()->back()->withInput()->with('errMsg', 'Please upload banner');
      }

      if($r->pu_url == null){
        return redirect()->back()->withInput()->with('errMsg', 'Please enter banner url');
      }

      $bg = Models\PopUp::updatePopUp($r);

      if(isset($bg)){
        return redirect()->back()->with('msg', 'Pop up have been updated successfully');
      }

    }catch(\Exception $e) {
      \Log::error($e);
    }
    return redirect()->back()->withInput()->with('errMsg', Lang::get('lang.errSystem'));
  }

  public function home()
  {
    return view('admin.home');
  }

  public function updateBg(Request $r)
  {
    try{
      if($r->bg == null){
        return redirect()->back()->withInput()->with('errMsg', 'Please upload image');
      }

      $bg = Models\Home::updateBg($r);

      if(isset($bg)){
        return redirect()->back()->with('msg', 'Background have been updated successfully');
      }

    }catch(\Exception $e) {
      \Log::error($e);
    }
    return redirect()->back()->withInput()->with('errMsg', Lang::get('lang.errSystem'));
  }

  public function event()
  {
    $event = Models\Event::getAll();
    return view('admin.event', compact('event'));
  }

  public function blog()
  {
    $blog = Models\Blog::getAll();
    $blogcat = Models\BlogCat::getAll();
    return view('admin.blog', compact('blog', 'blogcat'));
  }

  public function pinBlog(Request $r)
  {
      try {
          if ($r->b_id != null) {
              $b = Models\Blog::find($r->b_id);

              if ($b) {
                  $b->b_is_star = $b->b_is_star == 1 ? 0 : 1;
                  $b->save();
                  return redirect()->back()->with('msg', 'Blog pin status updated successfully!');
              }
          }
      } catch (\Exception $e) {
          \Log::error($e);
      }
      return redirect()->back()->withInput()->with('errMsg', Lang::get('lang.errSystem'));
  }

  public function blogDetails($url)
  {
    try {
        $blogData = Models\Blog::getByUrlCode($url);
        $url = url('/blog-details/'.$url);
        return view('member.blogDetails', compact('blogData','url'));
    } catch(\Exception $e) {
        \Log::error($e);
    }
  }

  public function editblogform()
  {
    $blogcat = Models\BlogCat::getAll();
    return view('admin.editblogform', compact('blogcat'));
  }

  public function editBlog(Request $r){
    try{
      if($r->title == null){
        return redirect()->back()->withInput()->with('errMsg', 'Please add title');
      }

      $edit = Models\Blog::editBlog($r);

      if(isset($edit)){
        return redirect()->back()->with('msg', 'Blog have been updated successfully');
      }

    }catch(\Exception $e) {
      \Log::error($e);
    }
    return redirect()->back()->withInput()->with('errMsg', Lang::get('lang.errSystem'));
  }

  public function blogcat()
  {
    $blogcat = Models\BlogCat::getAll();
    return view('admin.blogcat', compact('blogcat'));
  }

  public function addBlogCat(Request $r)
  {
    try{
      if($r->title == null){
        return redirect()->back()->withInput()->with('errMsg', 'Please enter category');
      }

      $add = Models\BlogCat::addBlogCat($r);

      if(isset($add)){
        return redirect()->back()->with('msg', 'Category have been added successfully');
      }

    }catch(\Exception $e) {
      \Log::error($e);
    }
    return redirect()->back()->withInput()->with('errMsg', Lang::get('lang.errSystem'));
  }

  public function deleteBlogCat(Request $r)
  {
    try {
        if ($r->bc_id != null) {
            $bc = Models\BlogCat::find($r->bc_id);

            if ($bc) {
                $bc->bc_status = 99;
                $bc->save();
                return redirect()->back()->with('msg', 'Blog Category has been deleted successfully');
            }
        }
    } catch (\Exception $e) {
        \Log::error($e);
    }
    return redirect()->back()->withInput()->with('errMsg', Lang::get('lang.errSystem'));
  }

  public function addEvent(Request $r){
    try{
      if($r->title == null){
        return redirect()->back()->withInput()->with('errMsg', 'Please add title');
      }

      if($r->img == null){
        return redirect()->back()->withInput()->with('errMsg', 'Please add event image');
      }
      if($r->desc == null){
        return redirect()->back()->withInput()->with('errMsg', 'Please add event description');
      }

      $add = Models\Event::addEvent($r);

      if(isset($add)){
        return redirect()->back()->with('msg', 'Event have been added successfully');
      }

    }catch(\Exception $e) {
      \Log::error($e);
    }
    return redirect()->back()->withInput()->with('errMsg', Lang::get('lang.errSystem'));
  }

  public function editeventform()
  {
    return view('admin.editeventform');
  }

  public function editEvent(Request $r){
    try{
      if($r->title == null){
        return redirect()->back()->withInput()->with('errMsg', 'Please add title');
      }

      $edit = Models\Event::editEvent($r);

      if(isset($edit)){
        return redirect()->back()->with('msg', 'Event have been updated successfully');
      }

    }catch(\Exception $e) {
      \Log::error($e);
    }
    return redirect()->back()->withInput()->with('errMsg', Lang::get('lang.errSystem'));
  }

  public function deleteEvent(Request $r)
  {
       try{
         if($r->e_id != null){
           $ev = Models\Event::getById($r->e_id);

           if(isset($ev)){
             $ev->e_status = 99;
             $ev->save();
             return redirect()->back()->with('msg', 'Event have been deleted successfully');
           }
         }
       } catch(\Exception $e) {
         \Log::error($e);
       }
       return redirect()->back()->withInput()->with('errMsg', Lang::get('lang.errSystem'));
  }

  public function addBlog(Request $r){
    try{
      if($r->title == null){
        return redirect()->back()->withInput()->with('errMsg', 'Please add title');
      }

      if($r->img == null){
        return redirect()->back()->withInput()->with('errMsg', 'Please add blog image');
      }

      $add = Models\Blog::addBlog($r);

      if(isset($add)){
        return redirect()->back()->with('msg', 'Blog have been added successfully');
      }

    }catch(\Exception $e) {
      \Log::error($e);
    }
    return redirect()->back()->withInput()->with('errMsg', Lang::get('lang.errSystem'));
  }

  public function deleteBlog(Request $r)
     {
       try{
         if($r->b_id != null){
           $b = Models\Blog::getById($r->b_id);

           if(isset($b)){
             $b->b_status = 99;
             $b->save();
             return redirect()->back()->with('msg', 'Blog have been deleted successfully');
           }
         }
       } catch(\Exception $e) {
         \Log::error($e);
       }
       return redirect()->back()->withInput()->with('errMsg', Lang::get('lang.errSystem'));
  }

  public function job()
  {
    $categories = Models\CareerCategory::all()->where('cc_status', 1);
    $job = Models\Career::getAll();
    return view('admin.job', compact('categories', 'job'));
  }

  public function jobcat()
  {
    $jobcat = Models\CareerCategory::all()->where('cc_status', 1);
    return view('admin.jobcat', compact('jobcat'));
  }

  public function deleteJobCategory(Request $r)
  {
    try {
      if ($r->cc_id != null) {
          $cc = Models\CareerCategory::getById($r->cc_id);

          if (isset($cc)) {
              $cc->cc_status = 99;
              $cc->save();
              return redirect()->back()->with('msg', 'Career category has been deleted successfully');
          }
      }
    } catch (\Exception $e) {
        \Log::error($e);
    }
    return redirect()->back()->withInput()->with('errMsg', Lang::get('lang.errSystem'));
  }

  public function addJobCategory(Request $r)
  {
    try{
      if($r->title == null){
        return redirect()->back()->withInput()->with('errMsg', 'Please enter category');
      }

      $add = Models\CareerCategory::addCategory($r);

      if(isset($add)){
        return redirect()->back()->with('msg', 'Category have been added successfully');
      }

    }catch(\Exception $e) {
      \Log::error($e);
    }
    return redirect()->back()->withInput()->with('errMsg', Lang::get('lang.errSystem'));
  }

  public function addJob(Request $r)
  {
    try{
      if($r->title == null){
        return redirect()->back()->withInput()->with('errMsg', 'Please job title');
      }

      if($r->category == null){
         return redirect()->back()->withInput()->with('errMsg', 'Please add job category');
      }

      if($r->type == null){
        return redirect()->back()->withInput()->with('errMsg', 'Please add job type');
      }

      if($r->state == null){
        return redirect()->back()->withInput()->with('errMsg', 'Please add job state');
      }

      if($r->minsalary == null){
        return redirect()->back()->withInput()->with('errMsg', 'Please add min salary');
      }

      if($r->maxsalary == null){
        return redirect()->back()->withInput()->with('errMsg', 'Please add max salary');
      }

      $add = Models\Career::addJob($r);

      if(isset($add)){
        return redirect()->back()->with('msg', 'Job have been added successfully');
      }

    }catch(\Exception $e) {
      \Log::error($e);
    }
    return redirect()->back()->withInput()->with('errMsg', Lang::get('lang.errSystem'));
  }

  public function editJob(Request $r)
  {
    try {
        // Validate the incoming request data as needed

        // Check if c_id is provided for updating
        if ($r->has('c_id')) {
            $edit = Models\Career::editJob($r);

            // Check if the job was updated successfully
            if ($edit) {
                return redirect()->back()->with('msg', 'Job has been updated successfully');
            }
        }

        // Handle the case where c_id is not provided or the job is not found
        return redirect()->back()->withInput()->with('errMsg', 'Job not found or update failed');
    } catch (\Exception $e) {
        \Log::error($e);

        return redirect()->back()->withInput()->with('errMsg', Lang::get('lang.errSystem'));
    }
  }

  public function deleteJob(Request $r)
  {
    try {
        if ($r->c_id != null) {
            // Use the find method to directly retrieve the job by its primary key
            $j = Models\Career::find($r->c_id);

            if(isset($j)){
              $j->c_status = 99;
              $j->save();
              return redirect()->back()->with('msg', 'Job have been deleted successfully');
            }
        }
    } catch (\Exception $e) {
        \Log::error($e);
    }

    return redirect()->back()->withInput()->with('errMsg', Lang::get('lang.errSystem'));
  }

  public function university()
  {
    $university = Models\University::admingetAll();
    return view('admin.university', compact('university'));
  }

  public function getUniBranches(Request $r)
  {
    $mainCategoryId = $r->main_category_id;

    $subcategories = Models\UniversityBranch::getById($mainCategoryId);

    if (isset($subcategories) && count($subcategories) > 0 ) {;
      return ['status' => true, 'list' => $subcategories];
    }

    // return response()->json(['message' => 'No subcategories found for the selected main category.'], 404);
    return ['status' => false, 'msg' => 'Error'];
  }

  public function addUniversityEmail(Request $r)
  {
    try{
      if($r->uni == null){
        return redirect()->back()->withInput()->with('errMsg', 'Please select university');
      }

      if($r->unibranch == null){
        return redirect()->back()->withInput()->with('errMsg', 'Please select university branch');
      }

      if($r->email == null){
        return redirect()->back()->withInput()->with('errMsg', 'Please enter university email');
      }

      $add = Models\UniversityEmail::updateUniversityEmail($r);

      if(isset($add)){
        return redirect()->back()->with('msg', 'University email have been updated successfully');
      }

    }catch(\Exception $e) {
      \Log::error($e);
    }
    return redirect()->back()->withInput()->with('errMsg', Lang::get('lang.errSystem'));
  }

  public function universitytype()
  {
    $universitytype = Models\UniversityType::getAll();
    return view('admin.universitytype', compact('universitytype'));
  }

  public function addUniversityType(Request $r)
  {
    try{
      if($r->unitype == null){
        return redirect()->back()->withInput()->with('errMsg', 'Please enter university type');
      }

      $add = Models\UniversityType::addUniversityType($r);

      if(isset($add)){
        return redirect()->back()->with('msg', 'University type have been added successfully');
      }

    }catch(\Exception $e) {
      \Log::error($e);
    }
    return redirect()->back()->withInput()->with('errMsg', Lang::get('lang.errSystem'));
  }

  public function deleteUniversityType(Request $r)
  {
    try {
        if ($r->ut_id != null) {
            $ut = Models\UniversityType::find($r->ut_id);

            if ($ut) {
                $ut->ut_status = 99;
                $ut->save();
                return redirect()->back()->with('msg', 'University type has been deleted successfully');
            }
        }
    } catch (\Exception $e) {
        \Log::error($e);
    }
    return redirect()->back()->withInput()->with('errMsg', Lang::get('lang.errSystem'));
  }

  public function edituniversityform()
  {
    $ut = Models\UniversityType::getAll();
    $state = Models\State::getAllState();
    $country = Models\Country::getAll();
    return view('admin.edituniversityform', compact('ut', 'state', 'country'));
  }

  public function getUniversityDetails($id)
  {
    $university = Models\University::find($id);

    if ($university) {
        return response()->json(['logo' => "/images/university/logo/logo-$id.jpg"]);
    } else {
        return response()->json(['error' => 'University not found.'], 404);
    }
  }

  public function editUniversity(Request $r)
  {
    try {
      // dd($r->all());
          if ($r->has('u_id')) {
              $edit = Models\University::editUniversity($r);

              if ($edit) {

                  Models\UniversityBranch::editBranch($r, $edit->u_id);
                  Models\UniversityBranch::deleteBranch($r, $edit->u_id, $edit->u_has_branch);

                  return redirect()->back()->with('msg', 'University has been updated successfully');
              } else{
                return redirect()->back()->withInput()->with('errMsg', Lang::get('lang.errSystem'));
              }
          }
          return redirect()->back()->withInput()->with('errMsg', 'University not found or update failed');
          } catch (\Exception $e) {
          \Log::error($e);

          return redirect()->back()->withInput()->with('errMsg', Lang::get('lang.errSystem'));
        }
  }

  public function viewuniversity(Request $r)
  {
    $university = Models\UniversityIntake::getAll();
    return view('admin.viewuniversity', compact('university'));
  }

  public function viewuniversityemail(Request $r)
  {
    $university = Models\University::getAll();
    $universitybranch = Models\UniversityEmail::getAll();
    return view('admin.viewuniversityemail', compact('university', 'universitybranch'));
  }

  public function addIntake(Request $r){
    try {
        if ($r->u_id == null) {
            return redirect()->back()->withInput()->with('errMsg', 'Please select university');
        }

        if ($r->level == null) {
            return redirect()->back()->withInput()->with('errMsg', 'Please select level');
        }

        if ($r->intake == null) {
            return redirect()->back()->withInput()->with('errMsg', 'Please select intake month');
        }

        $add = Models\UniversityIntake::addIntake($r);

        if (isset($add)) {
            return redirect()->back()->with('msg', 'Intake has been added successfully');
        }
    } catch(\Exception $e) {
        \Log::error($e);
    }
    
    return redirect()->back()->withInput()->with('errMsg', Lang::get('lang.errSystem'));
  }

  public function deleteIntake(Request $r)
  {
    try {
        if ($r->ui_id != null) {
            // Use the find method to directly retrieve the job by its primary key
            $u = Models\UniversityIntake::find($r->ui_id);

            if(isset($u)){
              $u->ui_status = 99;
              $u->save();
              return redirect()->back()->with('msg', 'Intake have been deleted successfully');
            }
        }
    } catch (\Exception $e) {
        \Log::error($e);
    }

    return redirect()->back()->withInput()->with('errMsg', Lang::get('lang.errSystem'));
  }

  public function adduniversityform()
  {
    $ut = Models\UniversityType::getAll();
    $state = Models\State::getAllState();
    $country = Models\Country::getAll();
    return view('admin.adduniversityform', compact('ut', 'state', 'country'));
  }

  public function getStates()
  {
    $getstates = Models\State::getAllState();

    if (isset($getstates) && count($getstates) > 0 ) {
      return ['status' => true, 'list' => $getstates];
    }

    return ['status' => false, 'msg' => 'Error'];
  }

  public function getCountries()
  {
    $getcountries = Models\Country::getAll();

    if (isset($getcountries) && count($getcountries) > 0 ) {
      return ['status' => true, 'list' => $getcountries];
    }

    return ['status' => false, 'msg' => 'Error'];
  }

  public function getStatesByCountry(Request $r)
  {
    $countryId = $r->countryId;

    $states = Models\State::getByCountryId($countryId);

    if (isset($states) && count($states) > 0 ) {
      // return response()->json($subcategories);
      return ['status' => true, 'list' => $states];
    }

    // return response()->json(['message' => 'No subcategories found for the selected main category.'], 404);
    return ['status' => false, 'msg' => 'Error'];
  }

  public function addUniversity(Request $r)
  {
    try{
      if($r->title == null){
        return redirect()->back()->withInput()->with('errMsg', 'Please add university name');
      }

      if($r->logo == null){
        return redirect()->back()->withInput()->with('errMsg', 'Please add university logo');
      }
    
      if($r->img == null){
        return redirect()->back()->withInput()->with('errMsg', 'Please add university image');
      }

      if($r->student == null){
        return redirect()->back()->withInput()->with('errMsg', 'Please add number of student');
      }

      if($r->staff == null){
        return redirect()->back()->withInput()->with('errMsg', 'Please add number of staff');
      }

      if($r->year == null){
        return redirect()->back()->withInput()->with('errMsg', 'Please add founded year');
      }

      if($r->desc == null){
        return redirect()->back()->withInput()->with('errMsg', 'Please add university description');
      }

      if($r->address == null){
        return redirect()->back()->withInput()->with('errMsg', 'Please add university address');
      }

      if($r->state == null){
        return redirect()->back()->withInput()->with('errMsg', 'Please add university state');
      }

      if($r->country == null){
        return redirect()->back()->withInput()->with('errMsg', 'Please add university country');
      }

      if($r->hours1 == null){
        return redirect()->back()->withInput()->with('errMsg', 'Please add university open hour');
      }

      if($r->hours2 == null){
        return redirect()->back()->withInput()->with('errMsg', 'Please add university close hour');
      }

      $add = Models\University::addUniversity($r);

      if ($add) {
              // Add course grades
              Models\UniversityBranch::addBranch($r, $add->u_id);

              return redirect()->back()->with('msg', 'University has been added successfully');
          } else {
              return redirect()->back()->withInput()->with('errMsg', Lang::get('lang.errSystem'));
          }
      } catch(\Exception $e) {
          \Log::error($e);
          return redirect()->back()->withInput()->with('errMsg', Lang::get('lang.errSystem'));
      }
  }

  public function deleteUniversity(Request $r)
  {
       try{
         if($r->u_id != null){
           $u = Models\University::getById($r->u_id);

           if(isset($u)){
             $u->u_status = 99;
             $u->save();

             $uniBranch = Models\UniversityBranch::getById($r->u_id);
             if ($uniBranch) {
                 foreach ($uniBranch as $ub) {
                     $ub->b_status = 99;
                     $ub->save();
                 }
             }

             return redirect()->back()->with('msg', 'University have been deleted successfully');
           }
         }
       } catch(\Exception $e) {
         \Log::error($e);
       }
       return redirect()->back()->withInput()->with('errMsg', Lang::get('lang.errSystem'));
  }

  public function hideUniversity(Request $r)
  {
      try {
          if ($r->u_id != null) {
              $u = Models\University::getById($r->u_id);

              if (isset($u)) {
                  // Toggle the status
                  if ($u->u_status == 1) {
                      $u->u_status = 98;
                  } else {
                      $u->u_status = 1;
                  }
                  $u->save();

                  $uniBranch = Models\UniversityBranch::getById($r->u_id);
                  if ($uniBranch) {
                      foreach ($uniBranch as $ub) {
                          // Toggle the branch status to match the university status
                          if ($u->u_status == 1) {
                              $ub->b_status = 1;
                          } else {
                              $ub->b_status = 98;
                          }
                          $ub->save();
                      }
                  }

                  return redirect()->back()->with('msg', 'University status has been updated successfully');
              }
          }
      } catch (\Exception $e) {
          \Log::error($e);
      }
      return redirect()->back()->withInput()->with('errMsg', Lang::get('lang.errSystem'));
  }

  public function deleteUniversityEmail(Request $r)
  {
       try{
         if($r->ue_id != null){
           $u = Models\UniversityEmail::getById($r->ue_id);

           if(isset($u)){
             $u->ue_status = 99;
             $u->save();

             return redirect()->back()->with('msg', 'University email have been deleted successfully');
           }
         }
       } catch(\Exception $e) {
         \Log::error($e);
       }
       return redirect()->back()->withInput()->with('errMsg', Lang::get('lang.errSystem'));
  }

  public function internationalschool()
  {
    $k12 = Models\InternationalSchool::getAll();
    return view('admin.internationalschool', compact('k12'));
  }

  public function addinternationalschoolform()
  {
    $state = Models\State::getAllState();
    $country = Models\Country::getAll();
    return view('admin.addinternationalschoolform', compact('state', 'country'));
  }

  public function editinternationalschoolform()
  {
    $state = Models\State::getAllState();
    $country = Models\Country::getAll();
    return view('admin.editinternationalschoolform', compact('state', 'country'));
  }

  public function addInternationalSchool(Request $r)
  {
    try{
      if($r->k12name == null){
        return redirect()->back()->withInput()->with('errMsg', 'Please add K-12 study name');
      }

      if($r->logo == null){
        return redirect()->back()->withInput()->with('errMsg', 'Please add university logo');
      }
    
      if($r->img == null){
        return redirect()->back()->withInput()->with('errMsg', 'Please add university image');
      }

      if($r->student == null){
        return redirect()->back()->withInput()->with('errMsg', 'Please add number of student');
      }

      if($r->staff == null){
        return redirect()->back()->withInput()->with('errMsg', 'Please add number of staff');
      }

      if($r->year == null){
        return redirect()->back()->withInput()->with('errMsg', 'Please add founded year');
      }

      if($r->desc == null){
        return redirect()->back()->withInput()->with('errMsg', 'Please add university description');
      }

      if($r->address == null){
        return redirect()->back()->withInput()->with('errMsg', 'Please add university address');
      }

      if($r->state == null){
        return redirect()->back()->withInput()->with('errMsg', 'Please add university state');
      }

      if($r->country == null){
        return redirect()->back()->withInput()->with('errMsg', 'Please add university country');
      }

      if($r->hours1 == null){
        return redirect()->back()->withInput()->with('errMsg', 'Please add university open hour');
      }

      if($r->hours2 == null){
        return redirect()->back()->withInput()->with('errMsg', 'Please add university close hour');
      }

      $add = Models\InternationalSchool::addInternationalSchool($r);

      if ($add) {
              Models\InternationalSchoolBranch::addInternationalSchoolBranch($r, $add->int_id);

              return redirect()->back()->with('msg', 'K-12 study has been added successfully');
          } else {
              return redirect()->back()->withInput()->with('errMsg', Lang::get('lang.errSystem'));
          }
      } catch(\Exception $e) {
          \Log::error($e);
          return redirect()->back()->withInput()->with('errMsg', Lang::get('lang.errSystem'));
      }
  }

  public function editInternationalSchool(Request $r)
  {
    try {
          if ($r->has('int_id')) {
              $edit = Models\InternationalSchool::editInternationalSchool($r);

              if ($edit) {

                  Models\InternationalSchoolBranch::editInternationalSchoolBranch($r, $edit->int_id);

                  return redirect()->back()->with('msg', 'K-12 study has been updated successfully');
              } else{
                return redirect()->back()->withInput()->with('errMsg', Lang::get('lang.errSystem'));
              }
          }
          return redirect()->back()->withInput()->with('errMsg', 'K-12 study not found or update failed');
          } catch (\Exception $e) {
          \Log::error($e);

          return redirect()->back()->withInput()->with('errMsg', Lang::get('lang.errSystem'));
        }
  }

  public function deleteInternationalSchool(Request $r)
  {
       try{
         if($r->int_id != null){
           $int = Models\InternationalSchool::getById($r->int_id);

           if(isset($int)){
             $int->int_status = 99;
             $int->save();

             $intBranch = Models\InternationalSchoolBranch::getById($r->int_id);
             if ($intBranch) {
                 foreach ($intBranch as $intb) {
                     $intb->int_b_status = 99;
                     $intb->save();
                 }
             }

             return redirect()->back()->with('msg', 'K-12 study have been deleted successfully');
           }
         }
       } catch(\Exception $e) {
         \Log::error($e);
       }
       return redirect()->back()->withInput()->with('errMsg', Lang::get('lang.errSystem'));
  }

  public function course()
  {
    $course = Models\Course::admingetAll();
    return view('admin.course', compact('course'));
  }

  public function addcourseform()
  {
    $level = Models\Course::levelArr();
    $qlevel = Models\Course::qlevelArr();
    $university = Models\University::getAll();
    $category = Models\Category::getAllActive();
    $stream = Models\Course::streamArr();
    return view('admin.addcourseform', compact('level', 'qlevel', 'university', 'category', 'stream'));
  }

  public function adddescform()
  {
    $course = Models\Course::getAllWithUni();
    $qlevel = Models\Course::qlevelArr();
    return view('admin.adddescform', compact('course', 'qlevel'));
  }

  public function addDesc(Request $r)
  {
    try{
      // dd($r->all());
      if($r->course == null){
        return redirect()->back()->withInput()->with('errMsg', 'Please select course');
      }

      if($r->level == null){
        return redirect()->back()->withInput()->with('errMsg', 'Please select level');
      }
    
      if($r->desc == null){
        return redirect()->back()->withInput()->with('errMsg', 'Please enter desc');
      }

      Models\CourseLevelDesc::addDesc($r);

    } catch(\Exception $e) {
        \Log::error($e);
        return redirect()->back()->withInput()->with('errMsg', Lang::get('lang.errSystem'));
    }
    return redirect()->back()->with('msg', 'Desc has been added successfully');
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

  public function getQlevel(Request $r)
  {
    $qLevelId = $r->qualification_level_id;

    $subjects = Models\Subject::getSubjects($qLevelId);

    if (isset($subjects) && count($subjects) > 0 ) {
      return ['status' => true, 'list' => $subjects];
    }

    return ['status' => false, 'msg' => 'Error'];
  }

  public function getMingrade(Request $r)
  {
    $qLevelId = $r->qualification_level_id;

    $grades = Models\SubjectGrade::getGrades($qLevelId);

    if (isset($grades) && count($grades) > 0 ) {
      return ['status' => true, 'list' => $grades];
    }

    return ['status' => false, 'msg' => 'Error'];
  }

  public function getQgrade(Request $r)
  {
    $qLevelId = $r->qualification_level_id;

    $grades = Models\SubjectGrade::getGrades($qLevelId);

    if (isset($grades) && count($grades) > 0 ) {
      return ['status' => true, 'list' => $grades];
    }

    return ['status' => false, 'msg' => 'Error'];
  }

  public function addCourse(Request $r)
  {
    try{
      // dd($r->all());
      if($r->coursename == null){
        return redirect()->back()->withInput()->with('errMsg', 'Please enter course name');
      }

      if($r->courseuni == null){
        return redirect()->back()->withInput()->with('errMsg', 'Please enter course university');
      }
    
      if($r->courselevel == null){
        return redirect()->back()->withInput()->with('errMsg', 'Please select course level');
      }

      if($r->coursemonth == null){
        return redirect()->back()->withInput()->with('errMsg', 'Please select course intake month');
      }

      if($r->courseaward == null){
        return redirect()->back()->withInput()->with('errMsg', 'Please enter course award');
      }

      if($r->courseinternship == null){
        return redirect()->back()->withInput()->with('errMsg', 'Please select course internship');
      }

      if($r->courseoutline == null){
        return redirect()->back()->withInput()->with('errMsg', 'Please enter course outline');
      }

      if($r->courseisfor == null){
        return redirect()->back()->withInput()->with('errMsg', 'Please select course is for');
      }

      if($r->coursesportsrepresent == null){
        return redirect()->back()->withInput()->with('errMsg', 'Please select sports represent');
      }

      if($r->coursetalentrepresent == null){
        return redirect()->back()->withInput()->with('errMsg', 'Please select talent represent');
      }

      if($r->courseleadership == null){
        return redirect()->back()->withInput()->with('errMsg', 'Please select leadership');
      }

      if($r->courseamount == null){
        return redirect()->back()->withInput()->with('errMsg', 'Please enter course amount');
      }
      
      if($r->coursebumiputra == null){
        return redirect()->back()->withInput()->with('errMsg', 'Please select bumiputra');
      }

      if($r->coursemaincat == null){
        return redirect()->back()->withInput()->with('errMsg', 'Please select course main category');
      }

      if($r->coursesubcat == null){
        return redirect()->back()->withInput()->with('errMsg', 'Please select course sub category');
      }

      $courseData = Models\Course::addCourse($r);

        if ($courseData) {
            // Add course grades
            Models\CourseGrade::addCourseGrades($r, $courseData->c_id);

            if ($r->extrafees == 1) {
              Models\CourseFee::addExtraFees($r, $courseData->c_id);
            }

            return redirect()->back()->with('msg', 'Course has been added successfully');
        } else {
            return redirect()->back()->withInput()->with('errMsg', Lang::get('lang.errSystem'));
        }
    } catch(\Exception $e) {
        \Log::error($e);
        return redirect()->back()->withInput()->with('errMsg', Lang::get('lang.errSystem'));
    }
  }

  public function editcourseform()
  {
    $level = Models\Course::levelArr();
    $qlevel = Models\Course::qlevelArr();
    $university = Models\University::getAll();
    $category = Models\Category::getAllActive();
    $subcategory = Models\SubCat::getAll();
    $stream = Models\Course::streamArr();

    return view('admin.editcourseform', compact('level', 'qlevel', 'university', 'category', 'subcategory', 'stream'));
  }

  public function editCourse(Request $r)
  {
    // dd($r->all());
    try {

    if ($r->has('c_id')) {
      
        $edit = Models\Course::editCourse($r);

        if ($edit) {

          Models\CourseGrade::editCourseGrades($r, $edit->c_id);

          if ($r->extrafees == 1) {

            Models\CourseFee::editExtraFees($r, $edit->c_id);

          } else if ($r->extrafees == 0) {

            $courseFees = Models\CourseFee::getById($r, $edit->c_id);

            if ($courseFees) {

              foreach ($courseFees as $cf) {

                $cf->cf_status = 99;
                $cf->save();
              }
            }
          }

            return redirect()->back()->with('msg', 'Course has been updated successfully');
        } else{
          return redirect()->back()->withInput()->with('errMsg', Lang::get('lang.errSystem'));
        }
    }

    return redirect()->back()->withInput()->with('errMsg', 'Course not found or update failed');
    } catch (\Exception $e) {
    \Log::error($e);

    return redirect()->back()->withInput()->with('errMsg', Lang::get('lang.errSystem'));
    }
  }


  public function deleteCourse(Request $r)
  {
       try{
         if($r->c_id != null){
           $c = Models\Course::getById($r->c_id);

           if(isset($c)){
             $c->c_status = 99;
             $c->save();

             $courseGrades = Models\CourseGrade::getById($r->c_id);
             if ($courseGrades) {
                 foreach ($courseGrades as $cg) {
                     $cg->cg_status = 99;
                     $cg->save();
                 }
             }

             return redirect()->back()->with('msg', 'Course have been deleted successfully');
           }
         }
       } catch(\Exception $e) {
         \Log::error($e);
       }
       return redirect()->back()->withInput()->with('errMsg', Lang::get('lang.errSystem'));
  }

  public function hideCourse(Request $r)
  {
      try {
          if ($r->c_id != null) {
              $c = Models\Course::getById($r->c_id);

              if (isset($c)) {
                  // Toggle the status
                  if ($c->c_status == 1) {
                      $c->c_status = 98;
                  } else {
                      $c->c_status = 1;
                  }
                  $c->save();

                  // $courseGrades = Models\CourseGrade::getByCourseId($r->c_id);
                  // if ($courseGrades) {
                  //     foreach ($courseGrades as $cg) {
                  //         // Toggle the course grade status to match the course status
                  //         if ($c->c_status == 1) {
                  //             $cg->cg_status = 1;
                  //         } else {
                  //             $cg->cg_status = 98;
                  //         }
                  //         $cg->save();
                  //     }
                  // }

                  return redirect()->back()->with('msg', 'Course status has been updated successfully');
              }
          }
      } catch (\Exception $e) {
          \Log::error($e);
      }
      return redirect()->back()->withInput()->with('errMsg', Lang::get('lang.errSystem'));
  }

  public function category()
  {
    $category = Models\Category::getAllActive();
    return view('admin.category', compact('category'));
  }

  public function addCategory(Request $r)
  {
    try{
      if($r->maincat == null){
        return redirect()->back()->withInput()->with('errMsg', 'Please enter main category name');
      }

      if($r->img == null){
        return redirect()->back()->withInput()->with('errMsg', 'Please add main category image');
      }
      $add = Models\Category::addCategory($r);

      if(isset($add)){
        return redirect()->back()->with('msg', 'Main Category have been added successfully');
      }

    }catch(\Exception $e) {
      \Log::error($e);
    }
    return redirect()->back()->withInput()->with('errMsg', Lang::get('lang.errSystem'));
  }

  public function editCategory(Request $r)
  {
    try {
      if ($r->has('c_id')) {
          $edit = Models\Category::editCategory($r);

          if ($edit) {
              return redirect()->back()->with('msg', 'Main category has been updated successfully');
          }
      }
      return redirect()->back()->withInput()->with('errMsg', 'Main category not found or update failed');
    } catch (\Exception $e) {
        \Log::error($e);

        return redirect()->back()->withInput()->with('errMsg', Lang::get('lang.errSystem'));
    }
  }

  public function deleteCategory(Request $r)
  {
    try {
        if ($r->c_id != null) {
            $c = Models\Category::getById($r->c_id);

            if (isset($c)) {
                $c->c_status = 99;
                $c->save();
                return redirect()->back()->with('msg', 'Main category has been deleted successfully');
            }
        }
    } catch (\Exception $e) {
        \Log::error($e);
    }
    return redirect()->back()->withInput()->with('errMsg', Lang::get('lang.errSystem'));
  }

  public function viewmaincat(Request $r)
  {
    $c_id = $r->query('c_id');
 
    if(isset($c_id)) {
      $category = Models\Category::where([
                  ['c_id', '=', $c_id],
                  ['c_status', '=', 1],
                  ])
                  ->first();
      } else {
        $category = []; // Initialize an empty array if $c_id is not set
    }
    $id = $r->c_id;
    $subcats = Models\SubCat::getAllActive($id);
    $characters = Models\Characters::getAll();

    return view('admin.viewmaincat', compact('category','subcats', 'characters'));
  }

  public function addSubCat(Request $r)
  {
    try{
      if($r->subcat == null){
        return redirect()->back()->withInput()->with('errMsg', 'Please enter sub category');
      }

      if($r->subcatchar == null){
        return redirect()->back()->withInput()->with('errMsg', 'Please select character');
      }

      $add = Models\SubCat::addSubCat($r);

      if(isset($add)){
        return redirect()->back()->with('msg', 'Sub category have been added successfully');
      }

    }catch(\Exception $e) {
      \Log::error($e);
    }
    return redirect()->back()->withInput()->with('errMsg', Lang::get('lang.errSystem'));
  }

  public function editSubCat(Request $r)
  {
    try {
      if ($r->has('sc_id')) {
          $edit = Models\SubCat::editSubCat($r);

          if ($edit) {
              return redirect()->back()->with('msg', 'Sub category has been updated successfully');
          }
      }
      return redirect()->back()->withInput()->with('errMsg', 'Sub category not found or update failed');
    } catch (\Exception $e) {
        \Log::error($e);

        return redirect()->back()->withInput()->with('errMsg', Lang::get('lang.errSystem'));
    }
  }

  public function deleteSubCat(Request $r)
  {
    try {
        if ($r->sc_id != null) {
            $sc = Models\SubCat::getById($r->sc_id);

            if (isset($sc)) {
                $sc->sc_status = 99;
                $sc->save();
                return redirect()->back()->with('msg', 'Sub category has been deleted successfully');
            }
        }
    } catch (\Exception $e) {
        \Log::error($e);
    }
    return redirect()->back()->withInput()->with('errMsg', Lang::get('lang.errSystem'));
  }

  public function scholarship()
  {
    $scholarship = Models\Scholarship::getAll();
    return view('admin.scholarship', compact('scholarship'));
  }

  public function addscholarshipform()
  {
    $level = Models\Course::qlevelArr();
    $university = Models\University::getAll();
    $subcategory = Models\SubCat::getAll();
    return view('admin.addscholarshipform', compact('level', 'university', 'subcategory'));
  }

  public function addScholarship(Request $r)
  {
    try{
      // dd($r->all());
      if($r->title == null){
        return redirect()->back()->withInput()->with('errMsg', 'Please enter scholarship title');
      }

      if($r->criteria == null){
        return redirect()->back()->withInput()->with('errMsg', 'Please enter criteria');
      }

      if($r->details == null){
        return redirect()->back()->withInput()->with('errMsg', 'Please enter details');
      }

      if($r->application == null){
        return redirect()->back()->withInput()->with('errMsg', 'Please enter applicaitons');
      }

      $add= Models\Scholarship::addScholarship($r);

        if ($add) {
            Models\ScholarshipDiscount::addScholarshipDiscount($r, $add->s_id);

            return redirect()->back()->with('msg', 'Scholarship has been added successfully');
        } else {
            return redirect()->back()->withInput()->with('errMsg', Lang::get('lang.errSystem'));
        }
    } catch(\Exception $e) {
        \Log::error($e);
        return redirect()->back()->withInput()->with('errMsg', Lang::get('lang.errSystem'));
    } 
  }

  public function editscholarshipform()
  {
    $qlevel = Models\Course::qlevelArr();
    $university = Models\University::getAll();
    $subcategory = Models\SubCat::getAll();
    return view('admin.editscholarshipform', compact('qlevel', 'university', 'subcategory'));
  }

  public function editScholarship(Request $r)
  {
    try {
      // dd($r->all());
 
      if ($r->has('s_id')) {
        
          $edit = Models\Scholarship::editScholarship($r);
  
          if ($edit) {
            Models\ScholarshipDiscount::editScholarshipDiscount($r, $edit->s_id);
  
              return redirect()->back()->with('msg', 'Scholarship has been updated successfully');
          } else{
            return redirect()->back()->withInput()->with('errMsg', Lang::get('lang.errSystem'));
          }
      }
  
      return redirect()->back()->withInput()->with('errMsg', 'Scholarship not found or update failed');
      } catch (\Exception $e) {
      \Log::error($e);
  
      return redirect()->back()->withInput()->with('errMsg', Lang::get('lang.errSystem'));
      }
  }

  public function deleteScholarship(Request $r)
  {
       try{
         if($r->s_id != null){
           $s = Models\Scholarship::getById($r->s_id);

           if(isset($s)){
             $s->s_status = 99;
             $s->save();

             if ($s) {
                  $scholarshipDiscounts = Models\ScholarshipDiscount::getById($r->s_id);
                  foreach ($scholarshipDiscounts as $sd) {
                      $sd->sd_status = 99;
                      $sd->save();
                  }
              }

             return redirect()->back()->with('msg', 'Scholarship have been deleted successfully');
           }
         }
       } catch(\Exception $e) {
         \Log::error($e);
       }
       return redirect()->back()->withInput()->with('errMsg', Lang::get('lang.errSystem'));
  }

  public function k12scholarship()
  {
    $k12s = Models\IntScholarship::getAll();
    return view('admin.k12scholarship', compact('k12s'));
  }

  public function addk12scholarshipform()
  {
    $k12 = Models\InternationalSchool::getAll();
    $level = Models\Course::qlevelArr();
    $subcategory = Models\SubCat::getAll();
    return view('admin.addk12scholarshipform', compact('k12', 'level', 'subcategory'));
  }

  public function addK12Scholarship(Request $r)
  {
    try{
      // dd($r->all());
      if($r->title == null){
        return redirect()->back()->withInput()->with('errMsg', 'Please enter scholarship title');
      }

      if($r->criteria == null){
        return redirect()->back()->withInput()->with('errMsg', 'Please enter criteria');
      }

      if($r->details == null){
        return redirect()->back()->withInput()->with('errMsg', 'Please enter details');
      }

      if($r->application == null){
        return redirect()->back()->withInput()->with('errMsg', 'Please enter applicaitons');
      }

      $add= Models\IntScholarship::addK12Scholarship($r);

        if ($add) {
            Models\IntScholarshipDiscount::addK12ScholarshipDiscount($r, $add->s_id);

            return redirect()->back()->with('msg', 'Scholarship has been added successfully');
        } else {
            return redirect()->back()->withInput()->with('errMsg', Lang::get('lang.errSystem'));
        }
    } catch(\Exception $e) {
        \Log::error($e);
        return redirect()->back()->withInput()->with('errMsg', Lang::get('lang.errSystem'));
    } 
  }

  public function editk12scholarshipform()
  {
    $qlevel = Models\Course::qlevelArr();
    $k12 = Models\InternationalSchool::getAll();
    $subcategory = Models\SubCat::getAll();
    return view('admin.editk12scholarshipform', compact('qlevel', 'k12', 'subcategory'));
  }

  public function editK12Scholarship(Request $r)
  {
    try {
      if ($r->has('s_id')) {
        
          $edit = Models\IntScholarship::editK12Scholarship($r);
  
          if ($edit) {
            Models\IntScholarshipDiscount::editK12ScholarshipDiscount($r, $edit->s_id);
  
              return redirect()->back()->with('msg', 'Scholarship has been updated successfully');
          } else{
            return redirect()->back()->withInput()->with('errMsg', Lang::get('lang.errSystem'));
          }
      }
  
      return redirect()->back()->withInput()->with('errMsg', 'Scholarship not found or update failed');
      } catch (\Exception $e) {
      \Log::error($e);
  
      return redirect()->back()->withInput()->with('errMsg', Lang::get('lang.errSystem'));
      }
  }

  public function deleteK12Scholarship(Request $r)
  {
      try {
          if($r->s_id != null){
              // Delete scholarship
              $scholarship = Models\IntScholarship::find($r->s_id);
              if($scholarship){
                  $scholarship->s_status = 99;
                  $scholarship->save();
                  
                  // Delete scholarship discount(s)
                  $discounts = Models\IntScholarshipDiscount::where('sd_s_id', $r->s_id)->get();
                  foreach ($discounts as $discount) {
                      $discount->sd_status = 99;
                      $discount->save();
                  }

                  return redirect()->back()->with('msg', 'Scholarship and related discounts have been deleted successfully');
              } else {
                  return redirect()->back()->with('errMsg', 'Scholarship not found');
              }
          }
      } catch(\Exception $e) {
          \Log::error($e);
      }
      
      return redirect()->back()->withInput()->with('errMsg', Lang::get('lang.errSystem'));
  }

  public function visitcampus()
  {
    $visitcampus = Models\VisitCampus::getAll();
    $time = Models\VisitCampus::timeArr();
    return view('admin.visitcampus', compact('visitcampus', 'time'));
  }

  public function viewvisitcampus()
  {
    $time = Models\VisitCampus::timeArr();
    $university = Models\University::getAll();
    return view('admin.viewvisitcampus', compact('time', 'university'));
  }

  public function exportCampusVisit()
  {
    $campusvisit = Models\VisitCampus::all();

        $headers = array(
            "Content-type" => "text/csv",
            "Content-Disposition" => "attachment; filename=campusvisit.csv",
            "Pragma" => "no-cache",
            "Cache-Control" => "must-revalidate, post-check=0, pre-check=0",
            "Expires" => "0"
        );

        $handle = fopen('php://temp', 'w');

        fputcsv($handle, [
            'No.',
            'University',
            'Salutation',
            'Full Name',
            'Contact No.',
            'IC No.',
            'Pax',
            'Date Time',
            'Remark',
        ]);

        foreach ($campusvisit as $index => $vc) {
          $uniBranchArray = json_decode($vc->vc_uni_id, true);
          $uniBranchNames = [];
  
          foreach ($uniBranchArray as $uniBranch) {
              $uni = Models\University::find($uniBranch['uni_id']);
              if ($uni) {
                  $branch = Models\UniversityBranch::getBranchById($uniBranch['branch']);
                  $stateName = $branch ? $branch->state_name : 'Main Branch';
                  $uniBranchNames[] = $uni->u_name . '-' . $stateName;
              }
          }
  
          $uniBranch = implode(', ', $uniBranchNames);


          $date = $vc->vc_date . '-' . $vc->vc_month . '-' . $vc->vc_year;
          $time = '';

          if ($vc->vc_time == 1) {
              $time = '9am - 12pm';
          } elseif ($vc->vc_time == 2) {
              $time = '1pm - 5pm';
          }

          $contactNo = "{$vc->vc_contact_no}";

          $row = [
              $index + 1,
              $uniBranch,
              $vc->vc_salutation,
              $vc->vc_name,
              "'" . $contactNo,
              "'" . $vc->vc_ic_num,
              $vc->vc_pax,
              $date . ' ' . $time,
              $vc->vc_remarks,
          ];    

          fputcsv($handle, $row);
        }

        rewind($handle);
        
        $csvContent = stream_get_contents($handle);

        fclose($handle);

        return response($csvContent, 200)->withHeaders($headers);
  }

  public function courseapplication()
  {
    $application = Models\CourseApplication::getAll();
    $course = Models\Course::getAll();
    return view('admin.courseapplication', compact('application', 'course'));
  }

  public function viewcourseapplication(Request $r)
  {
    $personality = Models\CourseApplication::getAll();
    $course = Models\Course::getAll();
    $gender = Models\CourseApplication::genderArr();
    $nationality = Models\CourseApplication::nationalityArr();
    $martial = Models\CourseApplication::martialArr();
    $ethnicity = Models\CourseApplication::ethnicityArr();
    $religion = Models\CourseApplication::religionArr();
    $relationship = Models\CourseApplication::relationshipArr();
    $credit = Models\CourseApplication::creditArr();
    $family = Models\CourseApplication::familyArr();
    $english = Models\CourseApplication::englishQualificationArr();
    $income = Models\CourseApplication::incomeArr();
    $disability = Models\CourseApplication::disabilityArr();
    $qlevel = Models\Course::qlevelArr();
    return view('admin.viewcourseapplication', compact('personality', 'course', 'gender', 'nationality', 'martial', 'ethnicity', 'religion', 'relationship', 'credit', 'family', 'english', 'income', 'disability', 'qlevel'));
  }

  public function exportCourseApplication()
  {
    $courseapplication = Models\CourseApplication::all();

        $headers = array(
            "Content-type" => "text/csv",
            "Content-Disposition" => "attachment; filename=courseapplication.csv",
            "Pragma" => "no-cache",
            "Cache-Control" => "must-revalidate, post-check=0, pre-check=0",
            "Expires" => "0"
        );

        $handle = fopen('php://temp', 'w');

        fputcsv($handle, [
            'No.',
            'Course',
            'Salutation',
            'Full Name',
            'Date of Birth',
            'IC No.',
            'Gender',
            'Nationality',
            'Martial Status',
            'Ethnicity',
            'Religion',
            'Home Contact No.',
            'Contact No.',
            'Email',
            'Emergency Contact Name',
            'Emergency Contact No.',
            'Emergency Contact Email',
            'Emergency Contact Relationship',
            'Permanent Address',
            'City',
            'State',
            'Country',
            'Post Code',
            'Mailing Address',
            'City',
            'State',
            'Country',
            'Post Code',
            'Academic Qualification',
            'School',
            'Year of Completion',
            'Credit Transfer',
            'First family to attend university',
            'English Qualification',
            'Score',
            'Year Taken',
            'Household Income',
            'Programme Name',
            'Intake Month',
            'Intake Year',
            'Disability',
            'Please Specify',
            'Documents',
        ]);

        foreach ($courseapplication as $index => $ca) {
          $courseName = Models\Course::find($ca->ca_c_id)->c_name ?? 'N/A';

          $gender_mapping = Models\CourseApplication::genderArr();
          $gender = $gender_mapping[$ca->ca_gender];

          $nationality_mapping = Models\CourseApplication::nationalityArr();
          $nationality = $nationality_mapping[$ca->ca_nationality];

          $martial_mapping = Models\CourseApplication::martialArr();
          $martialstatus = $martial_mapping[$ca->ca_martial_status];

          $ethnicity_mapping = Models\CourseApplication::ethnicityArr();
          $ethnicity = $ethnicity_mapping[$ca->ca_ethnicity];

          $religion_mapping = Models\CourseApplication::religionArr();
          $religion = $religion_mapping[$ca->ca_religion];

          $relationship_mapping = Models\CourseApplication::relationshipArr();
          $relationship = $relationship_mapping[$ca->ca_e_relationship];

          $credit_mapping = Models\CourseApplication::creditArr();
          $credit = $credit_mapping[$ca->ca_credit_transfer];

          $family_mapping = Models\CourseApplication::familyArr();
          $family = $family_mapping[$ca->ca_first_family];

          $english_mapping = Models\CourseApplication::englishQualificationArr();
          $english = $english_mapping[$ca->ca_e_qualification];

          $income_mapping = Models\CourseApplication::incomeArr();
          $income = $income_mapping[$ca->ca_h_income];

          $disability_mapping = Models\CourseApplication::disabilityArr();
          $disability = $disability_mapping[$ca->ca_has_disability];

          $qlevel_mapping = Models\Course::qlevelArr();
          $qlevel = $qlevel_mapping[$ca->ca_qualification];
          
          $transcripts = explode(',', $ca->ca_transcript);
          $resumes = explode(',', $ca->ca_resume);

          $row = [
              $index + 1,
              $courseName,
              $ca->ca_salutation,
              $ca->ca_name,
              $ca->ca_dob,
              "'" . $ca->ca_ic_num,
              $gender,
              $nationality,
              $martialstatus,
              $ethnicity,
              $religion,
              "'" . $ca->ca_home_phone,
              "'" . $ca->ca_phone,
              $ca->ca_email,
              $ca->ca_e_name,
              "'" . $ca->ca_e_contact,
              $ca->ca_e_email,
              $relationship,
              $ca->ca_p_addr,
              $ca->ca_p_city,
              $ca->ca_p_state,
              $ca->ca_p_country,
              $ca->ca_p_postcode,
              $ca->ca_m_addr,
              $ca->ca_m_city,
              $ca->ca_m_state,
              $ca->ca_m_country,
              $ca->ca_m_postcode,
              $qlevel,
              $ca->ca_qualification_school_name,
              $ca->ca_qualification_year,
              $credit,
              $family,
              $english,
              "'" . $ca->ca_e_score,
              $ca->ca_e_year,
              $income,
              $ca->ca_programme_name,
              $ca->ca_programme_month,
              $ca->ca_programme_year,
              $disability,
              $ca->ca_disability_desc,
          ];

          foreach ($transcripts as $transcript) {
              $transcriptUrl = 'https://edu4u.world/images/application/' . $transcript;
              $row[] = '=HYPERLINK("' . $transcriptUrl . '", "Transcript")';
          }

          foreach ($resumes as $resume) {
            $resumeUrl = 'https://edu4u.world/images/application/' . $resume;
            $row[] = '=HYPERLINK("' . $resumeUrl . '", "Resume")';
          }

          fputcsv($handle, $row);
        }

        rewind($handle);
        
        $csvContent = stream_get_contents($handle);

        fclose($handle);

        return response($csvContent, 200)->withHeaders($headers);
  }

  public function counsellor()
  {
    $counsellor = Models\ConsultCounsellor::getAll();
    $course = Models\Course::getAll();
    return view('admin.counsellor', compact('counsellor', 'course'));
  }

  public function viewconsultcounsellor(Request $r)
  {
    $counsellor = Models\ConsultCounsellor::getAll();
    $course = Models\Course::getAll();
    $gender = Models\ConsultCounsellor::genderArr();
    $qlevel = Models\Course::qlevelArr();
    return view('admin.viewconsultcounsellor', compact('counsellor', 'course', 'gender', 'qlevel'));
  }

  public function deletecounsellor(Request $r)
     {
       try{
         if($r->cc_id != null){
           $cc = Models\ConsultCounsellor::getById($r->cc_id);

           if(isset($cc)){
             $cc->cc_status = 99;
             $cc->save();
             return redirect()->back()->with('msg', 'Record have been deleted successfully');
           }
         }
       } catch(\Exception $e) {
         \Log::error($e);
       }
       return redirect()->back()->withInput()->with('errMsg', Lang::get('lang.errSystem'));
  }

  public function exportCounsellor()
  {
    $counsellor = Models\ConsultCounsellor::all();

        $headers = array(
            "Content-type" => "text/csv",
            "Content-Disposition" => "attachment; filename=counsellor.csv",
            "Pragma" => "no-cache",
            "Cache-Control" => "must-revalidate, post-check=0, pre-check=0",
            "Expires" => "0"
        );

        $handle = fopen('php://temp', 'w');

        fputcsv($handle, [
            'No.',
            'Course',
            'Salutation',
            'Full Name',
            'Contact No.',
            'Home Contact No.',
            'Date of Birth',
            'School',
            'Email',
            'Gender',
            'Preferred Course',
            'Qualification',
            'Remark',
            'Transcript',
        ]);

        foreach ($counsellor as $index => $c) {
          $courseName = Models\Course::find($c->cc_c_id)->c_name ?? 'N/A';
          $transcripts = explode(',', $c->cc_transcript);

          $gender_mapping = Models\ConsultCounsellor::genderArr();
          $gender = $gender_mapping[$c->cc_gender] ?? '-';

          $qlevel_mapping = Models\Course::qlevelArr();
          $qlevel = $qlevel_mapping[$c->cc_qualification] ?? '-';

          $row = [
              $index + 1,
              $courseName,
              $c->cc_salutation,
              $c->cc_name,
              "'" . $c->cc_contact_no,
              "'" . $c->cc_home_contact_no,
              $c->cc_dob,
              $c->cc_school_name,
              $c->cc_email,
              $gender,
              $c->cc_course,
              $qlevel,
              $c->cc_remarks,
          ];

          foreach ($transcripts as $transcript) {
            $transcriptUrl = 'https://edu4u.world/images/application/' . $transcript;
            $row[] = '=HYPERLINK("' . $transcriptUrl . '", "Transcript")';
        }        

          fputcsv($handle, $row);
        }

        rewind($handle);
        
        $csvContent = stream_get_contents($handle);

        fclose($handle);

        return response($csvContent, 200)->withHeaders($headers);
  }

  public function enquiries()
  {
    $enquiries = Models\Enquiry::getAll();
    return view('admin.enquiry', compact('enquiries'));
  }

  public function deleteenquiries(Request $r)
     {
       try{
         if($r->e_id != null){
           $e = Models\Enquiry::getById($r->e_id);

           if(isset($e)){
             $e->e_status = 99;
             $e->save();
             return redirect()->back()->with('msg', 'Record have been deleted successfully');
           }
         }
       } catch(\Exception $e) {
         \Log::error($e);
       }
       return redirect()->back()->withInput()->with('errMsg', Lang::get('lang.errSystem'));
  }

  public function exportEnquiries()
  {
    $enquiry = Models\Enquiry::all();

        $headers = array(
            "Content-type" => "text/csv",
            "Content-Disposition" => "attachment; filename=enquiries.csv",
            "Pragma" => "no-cache",
            "Cache-Control" => "must-revalidate, post-check=0, pre-check=0",
            "Expires" => "0"
        );

        $handle = fopen('php://temp', 'w');

        fputcsv($handle, [
            'No.',
            'Full Name',
            'Email',
            'Phone No.',
            'Message',
            'Date',
        ]);

        foreach ($enquiry as $index => $e) {
          $contactNo = "{$e->e_phone_num}";
          $formattedDate = date('d-m-Y', strtotime($e->created_at));

          $row = [
              $index + 1,
              $e->e_name,
              $e->e_email,
              $contactNo,
              $e->e_message,
              $formattedDate,
          ];    

          fputcsv($handle, $row);
        }

        rewind($handle);
        
        $csvContent = stream_get_contents($handle);

        fclose($handle);

        return response($csvContent, 200)->withHeaders($headers);
  }

  public function subjects()
  {
    $level = Models\Course::qlevelArr();
    $subject = Models\Subject::getAllActive();
    return view('admin.subjects', compact('level', 'subject'));
  }

  public function viewsubjects(Request $r)
  {
    $levels = Models\Course::qlevelArr();
    $level = isset($levels[$r->id]) ? $levels[$r->id] : null;
    $value = $r->id;
    if(isset($r->id)) {
    $subjects = Models\Subject::where([
                ['s_level_id', '=', $r->id],
                ['s_status', '=', 1],
                ])
                ->leftJoin('subject_categories as a', 'a.sc_id', '=', 's_subject_cat_id')
                ->get();
    }

    $subjectcategories = Models\SubjectCategory::getAllActive();

    return view('admin.viewsubjects', compact('value', 'level', 'subjects', 'subjectcategories'));
  }

  public function addQualificationLevel(Request $r)
  {
    try{
      if($r->qlevel == null){
        return redirect()->back()->withInput()->with('errMsg', 'Please insert qualification level');
      }

      $add = Models\QualificationLevel::addQualificationLevel($r);

      if(isset($add)){

        for ($i = 0; $i < 20; $i++) {
        Models\SubjectGrade::addGrade($r, $add->q_id);
        }

        return redirect()->back()->with('msg', 'Level have been added successfully');
      }

    }catch(\Exception $e) {
      \Log::error($e);
    }
    return redirect()->back()->withInput()->with('errMsg', Lang::get('lang.errSystem'));
  }

  public function deleteQualificationLevel(Request $r)
  {
    try {
        if ($r->q_id != null) {
            $l = Models\QualificationLevel::getById($r->q_id);

            if (isset($l)) {
                $l->q_status = 99;
                $l->save();
                return redirect()->back()->with('msg', 'Level has been deleted successfully');
            }
        }
    } catch (\Exception $e) {
        \Log::error($e);
    }
    return redirect()->back()->withInput()->with('errMsg', Lang::get('lang.errSystem'));
  }

  public function addSubject(Request $r)
  {
    // dd($r->all());
    try{
      if($r->subjectId == null){
        return redirect()->back()->withInput()->with('errMsg', 'Please insert subject id');
      }

      if($r->subjectCategory == null){
         return redirect()->back()->withInput()->with('errMsg', 'Please select subject category');
      }

      if($r->subjectTitle == null){
        return redirect()->back()->withInput()->with('errMsg', 'Please add subject title');
      }

      if($r->preselected == null){
        return redirect()->back()->withInput()->with('errMsg', 'Please select yes or no');
      }

      $add = Models\Subject::addSubject($r);

      if(isset($add)){
        return redirect()->back()->with('msg', 'Subject have been added successfully');
      }

    }catch(\Exception $e) {
      \Log::error($e);
    }
    return redirect()->back()->withInput()->with('errMsg', Lang::get('lang.errSystem'));
  }

  public function editSubject(Request $r)
  {
    try {
        // Validate the incoming request data as needed

        // Check if c_id is provided for updating
        if ($r->has('s_id')) {
            $edit = Models\Subject::editSubject($r);

            // Check if the job was updated successfully
            if ($edit) {
                return redirect()->back()->with('msg', 'Subject has been updated successfully');
            }
        }

        // Handle the case where c_id is not provided or the job is not found
        return redirect()->back()->withInput()->with('errMsg', 'Job not found or update failed');
    } catch (\Exception $e) {
        \Log::error($e);

        return redirect()->back()->withInput()->with('errMsg', Lang::get('lang.errSystem'));
    }
  }

  public function deleteSubject(Request $r)
  {
    try {
        if ($r->s_id != null) {
            // Use the find method to directly retrieve the job by its primary key
            $sub = Models\Subject::find($r->s_id);

            if(isset($sub)){
              $sub->s_status = 99;
              $sub->save();
              return redirect()->back()->with('msg', 'Subject have been deleted successfully');
            }
        }
    } catch (\Exception $e) {
        \Log::error($e);
    }

    return redirect()->back()->withInput()->with('errMsg', Lang::get('lang.errSystem'));
  }

  public function subjectscategories()
  {
    $subcat = Models\SubjectCategory::getAllActive();
    return view('admin.subjectscategories', compact('subcat'));
  }

  public function addsubjectCategory(Request $r)
  {
    try{
      if($r->title == null){
        return redirect()->back()->withInput()->with('errMsg', 'Please enter category');
      }

      $add = Models\SubjectCategory::addsubjectCategory($r);

      if(isset($add)){
        return redirect()->back()->with('msg', 'Category have been added successfully');
      }

    }catch(\Exception $e) {
      \Log::error($e);
    }
    return redirect()->back()->withInput()->with('errMsg', Lang::get('lang.errSystem'));
  }

  public function deletesubjectCategory(Request $r)
  {
    try {
        if ($r->sc_id != null) {
            $subcat = Models\SubjectCategory::find($r->sc_id);

            if ($subcat) {
                $subcat->sc_status = 99;
                $subcat->save();
                return redirect()->back()->with('msg', 'Subject Category has been deleted successfully');
            }
        }
    } catch (\Exception $e) {
        \Log::error($e);
    }
    return redirect()->back()->withInput()->with('errMsg', Lang::get('lang.errSystem'));
  }

  public function users()
  {
    $users = Models\User::getAll();
    $personality = Models\Personality::getAll();
    return view('admin.users', compact('users', 'personality'));
  }

  public function exportUsers()
  {
    $users = Models\User::where('isAdmin', '!=', 1)->get();

        $headers = array(
            "Content-type" => "text/csv",
            "Content-Disposition" => "attachment; filename=users.csv",
            "Pragma" => "no-cache",
            "Cache-Control" => "must-revalidate, post-check=0, pre-check=0",
            "Expires" => "0"
        );

        $handle = fopen('php://temp', 'w');

        fputcsv($handle, [
            'Edu4u ID',
            'Name',
            'Gender',
            'Email',
            'Contact No.',
            'Date of birth',
            'Nationality',
            'Personality',
            'Interests',
            'Education Level',
            'Qualificaiton Level',
            'School',
            // 'Result Type',
            // 'Results',
            'Represent In Sports',
            'Level',
            'Sports',
            'Represent In Talents',
            'Level',
            'Talents',
            'Leadership Role',
            'Role',
            'Level',
            'Society / Event',
            'Family Monthly Income',
            'Budget For Course',
            'Bumiputera',
            'Preferred Course',
            'Preferred Pathway',
            'Planning Start Date',
            'Preferred Location',
            'Preferred State',
            'Preferred Country',
            'Preferred University Type',
            'Paid Register Fee',
            'Registered University',
            'Character',
        ]);

        foreach ($users as $user) {
          $personalityNames = [];
          $interestsNames = [];
          $characterNames = [];

          $academic = Models\Academic::find($user->id);
          $malaysianStatus = $academic->is_malaysian ?? null;
          $malaysianStatusValue = ($malaysianStatus == 1) ? 'Malaysian' : (($malaysianStatus == 2) ? 'Foreigner' : null);
          $educationallevel = $academic->a_level ?? null;
          $educationalLevels = [
              1 => 'Secondary',
              2 => 'Pre-University',
              3 => 'Others',
          ];
          $educationalLevelValue = $educationalLevels[$educationallevel] ?? 'Others';
          $qualification = $academic->a_qualification ?? null;
          $qualifications = [
              1 => 'SPM',
              2 => 'O-Level',
              3 => 'STPM',
              4 => 'UEC',
              5 => 'Foundation',
              6 => 'Diploma',
              7 => 'A-Level',
              8 => 'IB Diploma',
              9 => 'CPU/CIMP',
              10 => 'SACE/WACE',
          ];
          $qualificationValue = $qualifications[$qualification] ?? 'Others';
          $school = $academic->a_school ?? null;

          // $results = Models\Result::where('r_user_id', $user->id)->where('r_status', '1')->get();
          // $resultData = [];
          // foreach ($results as $result) {
          //     $subjectName = Models\Subject::find($result->r_subject_id)->s_name ?? '';
          //     $grade = Models\SubjectGrade::find($result->r_grade)->sg_grade ?? '';

          //     $resultData[] = "$subjectName: $grade";
          // }
          // $resultString = implode(', ', $resultData);
          // $result_type_mapping = [
          //     1 => 'Final Result',
          //     2 => 'Trial / Forecast Result',
          //     3 => 'Partial Results or Monthly / Semester Exam',
          // ];
          // $resultstype = $results->isNotEmpty() ? $result_type_mapping[$results->first()->r_type] : null;

          $achievements = Models\Achievement::where('a_user_id', $user->id)->get();
          $sports = $achievements->where('a_type', '1')->first();
          $sports_is_represent = $sports ? ($sports->a_is_represent == 1 ? 'Yes' : 'No') : null;
          $sports_level = $sports ? ($sports->a_level == 1 ? 'School' : ($sports->a_level == 2 ? 'District' : ($sports->a_level == 3 ? 'State' : ($sports->a_level == 4 ? 'National' : null)))) : null;
          $sports_represent = $sports ? $sports->a_represent : null;

          $talents = $achievements->where('a_type', '2')->first();
          $talents_is_represent = $talents ? ($talents->a_is_represent == 1 ? 'Yes' : 'No') : null;
          $talents_level = $talents ? ($talents->a_level == 1 ? 'School' : ($talents->a_level == 2 ? 'District' : ($talents->a_level == 3 ? 'State' : ($talents->a_level == 4 ? 'National' : null)))) : null;
          $talents_represent = $talents ? $talents->a_represent : null;

          $leaderships = $achievements->where('a_type', '3')->first();
          $leaderships_is_represent = $leaderships ? ($leaderships->a_is_represent == 1 ? 'Yes' : 'No') : null;
          $leaderships_role = $leaderships ? ($leaderships->a_role == 1 ? 'President' : ($leaderships->a_role == 2 ? 'Vice President' : ($leaderships->a_role == 3 ? 'Secretary' : ($leaderships->a_role == 4 ? 'Treasurer' : ($leaderships->a_role == 5 ? 'Director / Team Lead' : ($leaderships->a_role == 6 ? 'Others' : null)))))) : null;
          $leaderships_level = $leaderships ? ($leaderships->a_level == 1 ? 'School' : ($leaderships->a_level == 2 ? 'District' : ($leaderships->a_level == 3 ? 'State' : ($leaderships->a_level == 4 ? 'National' : null)))) : null;
          $leaderships_represent = $leaderships ? $leaderships->a_represent : null;



          $family = Models\Family::find($user->id);
          $income_mapping = [
              1 => 'Below MYR 4,000',
              2 => 'Below MYR 8,000',
              3 => 'Above MYR 8,000',
          ];
          $income = $family ? ($income_mapping[$family->f_income] ?? null) : null;
          $budget_mapping = [
              1 => 'Below MYR 20,000',
              2 => 'MYR 20,001 - MYR 40,000',
              3 => 'MYR 40,001 - MYR 60,000',
              4 => 'MYR 60,001 - MYR 80,000',
              5 => 'MYR 80,001 - MYR 100,000',
              6 => 'Above MYR 100,000',
          ];
          $budget = $family ? ($budget_mapping[$family->f_budget] ?? null) : null;
          $bumiputra_mapping = [
              1 => 'Yes',
              2 => 'No',
          ];
          $bumiputra = $family ? ($bumiputra_mapping[$family->f_is_bumiputera] ?? null) : null;


          $universitypreferences = Models\UniversityPreference::find($user->id);
          $pathway_mapping = [
              1 => 'Foundation',
              2 => 'International Pre-University',
              3 => 'Diploma',
              4 => 'Degree',
              5 => 'Master',
              6 => 'PHD',
          ];
          $plandate_mapping = [
              1 => 'Jan - Mar 24',
              2 => 'Apr - Jun 24',
              3 => 'Jul - Sep 24',
              4 => 'Oct - Dec 24',
              5 => 'Jan 25 Onwards',
          ];
          $location_mapping = [
              1 => 'Malaysia, for my entire studies',
              2 => 'Malaysia, but I plan to go overseas later',
              3 => 'Overseas, for my entire studies',
          ];
          $state_mapping = [
              1 => 'Johor',
              2 => 'Kedah',
              3 => 'Kelantan',
              4 => 'Melaka',
              5 => 'Negeri Sembilan',
              6 => 'Pahang',
              7 => 'Perak',
              8 => 'Perlis',
              9 => 'Pulau Pinang',
              10 => 'Selangor',
              11 => 'Terengganu',
              12 => 'Kuala Lumpur',
              13 => 'Putrajaya',
              14 => 'Sarawak',
              15 => 'Sabah',
              16 => 'Labuan',
          ];
          $country_mapping = [
              129 => 'Malaysia',
              192 => 'Singapore',
              13 => 'Australia',
              826 => 'United Kingdom',
              840 => 'United States',
          ];
          $consideruni_mapping = [
              1 => 'Private University',
              2 => 'Public University',
              3 => 'Both Private & Public University',
          ];
            $registerfee_mapping = [
              1 => 'Yes',
              2 => 'No',
          ];
        
          $uniname = $universitypreferences ? $universitypreferences->up_uni_name : null;
          $pathway = $universitypreferences ? ($pathway_mapping[$universitypreferences->up_pathway] ?? null) : null;
          $plandate = $universitypreferences ? ($plandate_mapping[$universitypreferences->up_start] ?? null) : null;
          $location = $universitypreferences ? ($location_mapping[$universitypreferences->up_location] ?? null) : null;
          $state = $universitypreferences ? ($state_mapping[$universitypreferences->up_state] ?? null) : null;
          $country = $universitypreferences ? ($country_mapping[$universitypreferences->up_country] ?? null) : null;
          $consideruni = $universitypreferences ? ($consideruni_mapping[$universitypreferences->up_type] ?? null) : null;
          $registerfee = $universitypreferences ? ($registerfee_mapping[$universitypreferences->up_is_fee_paid] ?? null) : null;

          if ($user->personality) {
              $personalities = explode(',', $user->personality);
              foreach ($personalities as $personalityId) {
                  $personality = Models\Personality::find($personalityId);
                  if ($personality) {
                      $personalityNames[] = $personality->p_name;
                  }
              }
          }

          if ($user->interests) {
              $interests = explode(',', $user->interests);
              foreach ($interests as $interestId) {
                  $interest = Models\Personality::find($interestId);
                  if ($interest) {
                      $interestsNames[] = $interest->p_name;
                  }
              }
          }

          if ($user->character) {
              $characters = json_decode($user->character);
              foreach ($characters as $character) {
                  $characterModel = Models\Characters::find($character->id);
                  if ($characterModel) {
                      $characterNames[] = $characterModel->c_name;
                  }
              }
          }

          fputcsv($handle, [
              $user->mlm_id,
              $user->fullname ?? '',
              $user->gender == 1 ? 'Male' : ($user->gender == 2 ? 'Female' : ''),
              $user->email ?? '',
              $user->contact_code && $user->contact_no ? '+' . $user->contact_code . ' ' . $user->contact_no : '',
              $user->dob_day . '-' . $user->dob_month . '-' . $user->dob_year,
              $malaysianStatusValue,
              implode(',', $personalityNames),
              implode(',', $interestsNames),
              $educationalLevelValue,
              $qualificationValue,
              $school,
              // $resultstype,
              // $resultString,
              $sports_is_represent,
              $sports_level,
              $sports_represent,
              $talents_is_represent,
              $talents_level,
              $talents_represent,
              $leaderships_is_represent,
              $leaderships_role,
              $leaderships_level,
              $leaderships_represent,
              $income,
              $budget,
              $bumiputra,
              Models\Category::find($user->preferred_course) ? Models\Category::find($user->preferred_course)->c_name : '',
              $pathway,
              $plandate,
              $location,
              $state,
              $country,
              $consideruni,
              $registerfee,
              $uniname,
              implode(',', $characterNames)
          ]);
        }

        rewind($handle);
        
        $csvContent = stream_get_contents($handle);

        fclose($handle);

        return response($csvContent, 200)->withHeaders($headers);
  }

  public function viewuser(Request $r)
  {
    $personality = Models\Personality::getAll();

    return view('admin.viewuser', compact('personality'));
  }

  public function characters()
  {
    $characters = Models\Characters::getAll();
    $stream = Models\Course::streamArr();
    return view('admin.characters', compact('characters', 'stream'));
  }

  public function addCharacter(Request $r)
  {
    try{
      if($r->title == null){
        return redirect()->back()->withInput()->with('errMsg', 'Please add title');
      }

      if($r->img1 == null){
        return redirect()->back()->withInput()->with('errMsg', 'Please add male character image');
      }

      if($r->img2 == null){
        return redirect()->back()->withInput()->with('errMsg', 'Please add female character image');
      }

      if($r->desc == null){
        return redirect()->back()->withInput()->with('errMsg', 'Please add character description');
      }

      $add = Models\Characters::addCharacter($r);

      if(isset($add)){
        return redirect()->back()->with('msg', 'Character have been added successfully');
      }

    }catch(\Exception $e) {
      \Log::error($e);
    }
    return redirect()->back()->withInput()->with('errMsg', Lang::get('lang.errSystem'));
  }

  public function getDesc(Request $r)
  {
    $c_id = $r->c_id;

    $character = Models\Characters::find($c_id);
    if ($character) {
        return response()->json($character->c_desc);
    } else {
        return response()->json(['error' => 'Character not found'], 404);
    }
  }

  public function editCharacter(Request $r)
  {
    try {
      if ($r->has('c_id')) {
          $edit = Models\Characters::editCharacter($r);

          if ($edit) {
              return redirect()->back()->with('msg', 'Character has been updated successfully');
          }
      }
      return redirect()->back()->withInput()->with('errMsg', 'Character not found or update failed');
    } catch (\Exception $e) {
        \Log::error($e);

        return redirect()->back()->withInput()->with('errMsg', Lang::get('lang.errSystem'));
    }
  }

  public function deleteCharacter(Request $r)
  {
    try {
        if ($r->c_id != null) {
            $c = Models\Characters::getById($r->c_id);

            if (isset($c)) {
                $c->c_status = 99;
                $c->save();
                return redirect()->back()->with('msg', 'Character has been deleted successfully');
            }
        }
    } catch (\Exception $e) {
        \Log::error($e);
    }
    return redirect()->back()->withInput()->with('errMsg', Lang::get('lang.errSystem'));
  }

  public function personality()
  {
    $personality = Models\Personality::getAll();
    $characters = Models\Characters::getAll();
    $editcharacters = Models\Characters::getAll();
    return view('admin.personality', compact('personality', 'characters', 'editcharacters'));
  }

  public function addPersonality(Request $r)
  {
    try {
        if ($r->type == null) {
            return redirect()->back()->withInput()->with('errMsg', 'Please select personality type');
        }

        if ($r->title == null) {
            return redirect()->back()->withInput()->with('errMsg', 'Please enter personality name');
        }

        if ($r->character == null) {
            return redirect()->back()->withInput()->with('errMsg', 'Please select personality character');
        }

        $add = Models\Personality::addPersonality($r);

        if (isset($add)) {
            return redirect()->back()->with('msg', 'Personality has been added successfully');
        }
    } catch(\Exception $e) {
        \Log::error($e);
    }
    
    return redirect()->back()->withInput()->with('errMsg', Lang::get('lang.errSystem'));
  }

  public function editPersonality(Request $r)
  {
    try {
        // Validate the incoming request data as needed

        if ($r->has('p_id')) {
            $edit = Models\Personality::editPersonality($r);

            if ($edit) {
                return redirect()->back()->with('msg', 'Personality has been updated successfully');
            }
        }

        // Handle the case where p_id is not provided or the personality is not found
        return redirect()->back()->withInput()->with('errMsg', 'Personality not found or update failed');
    } catch (\Exception $e) {
        \Log::error($e);

        return redirect()->back()->withInput()->with('errMsg', Lang::get('lang.errSystem'));
    }
  }

  public function deletePersonality(Request $r)
  {
       try{
         if($r->p_id != null){
           $p = Models\Personality::getById($r->p_id);

           if(isset($p)){
             $p->p_status = 99;
             $p->save();
             return redirect()->back()->with('msg', 'Personality have been deleted successfully');
           }
         }
       } catch(\Exception $e) {
         \Log::error($e);
       }
       return redirect()->back()->withInput()->with('errMsg', Lang::get('lang.errSystem'));
  }

  public function schools()
  {
    $schools = Models\Schools::getAll();
    return view('admin.schools', compact('schools'));
  }

  public function addSchools(Request $r)
  {
    try{
      if($r->schname == null){
        return redirect()->back()->withInput()->with('errMsg', 'Please enter school name');
      }

      if($r->qualification == null){
        return redirect()->back()->withInput()->with('errMsg', 'Please select qualification');
      }

      $add = Models\Schools::addSchools($r);

      if(isset($add)){
        return redirect()->back()->with('msg', 'School have been added successfully');
      }

    }catch(\Exception $e) {
      \Log::error($e);
    }
    return redirect()->back()->withInput()->with('errMsg', Lang::get('lang.errSystem'));
  }

  public function editSchools(Request $r)
  {
    try {
      if ($r->has('s_id')) {
          $edit = Models\Schools::editSchools($r);

          if ($edit) {
              return redirect()->back()->with('msg', 'School has been updated successfully');
          }
      }
      return redirect()->back()->withInput()->with('errMsg', 'School not found or update failed');
    } catch (\Exception $e) {
        \Log::error($e);

        return redirect()->back()->withInput()->with('errMsg', Lang::get('lang.errSystem'));
    }
  }

  public function deleteSchools(Request $r)
  {
    try {
        if ($r->s_id != null) {
            $s = Models\Schools::getById($r->s_id);

            if (isset($s)) {
                $s->s_status = 99;
                $s->save();
                return redirect()->back()->with('msg', 'School has been deleted successfully');
            }
        }
    } catch (\Exception $e) {
        \Log::error($e);
    }
    return redirect()->back()->withInput()->with('errMsg', Lang::get('lang.errSystem'));
  }

  public function subjectsgrades()
  {
    $level = Models\Course::qlevelArr();
    $grades = Models\SubjectGrade::getAll();
    return view('admin.subjectsgrades', compact('level', 'grades'));
  }

  public function viewgrades(Request $r)
  {
    $grades = Models\SubjectGrade::getAll();
    return view('admin.viewgrades', compact('grades'));
  }

  public function editGrade(Request $r)
  {
    try {
        if ($r->has('sg_id')) {
            $edit = Models\SubjectGrade::editGrade($r);

            if ($edit) {
                return redirect()->back()->with('msg', 'Grade has been updated successfully');
            }
        }

        return redirect()->back()->withInput()->with('errMsg', 'Grade not found or update failed');
    } catch (\Exception $e) {
        \Log::error($e);

        return redirect()->back()->withInput()->with('errMsg', Lang::get('lang.errSystem'));
    }
  }

}
