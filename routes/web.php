<?php

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/


/***************************************************************************
 * Page that can only be access after login
 ***************************************************************************/

use App\Http\Controllers\AdminController;

Route::group(['middleware' => ['isLogin', 'localization']], function() {

});



/***************************************************************************
 * Pages without authentication
 ***************************************************************************/

 Route::group(['middleware' => ['localization']], function () {

   //landing page
   Route::get('/', 'MemberController@home');
   Route::get('/personality', 'MemberController@personality');
   Route::get('/scholarship/{url}', 'MemberController@scholarship');
   Route::get('/blog', 'MemberController@blog');
   Route::get('/blog-details/{url}', 'AdminController@blogDetails');
   Route::get('/events', 'MemberController@events');
   Route::get('/event-details', 'MemberController@eventsDetails');
   Route::get('/terms', 'MemberController@terms');
   Route::get('/privacy', 'MemberController@privacy');
   Route::get('/careers', 'MemberController@careers');

   Route::get('/test-email', 'MemberController@testEmail');

   //language
   Route::get('/change-language/{locale}', 'LanguageController@index')->name('change.language');

    //on boarding 
    Route::post('/sign-up', 'MemberController@signUp');
    Route::post('/login', 'MemberController@login');
    
   //personality
   Route::post('/addName', 'MemberController@addName');
   Route::post('/getSubject', 'MemberController@getSubject');
   Route::post('/getMainCat', 'MemberController@getMainCat');
   Route::post('/getSubCat', 'MemberController@getSubCat');
   Route::post('/createNewUser', 'MemberController@createNewUser');
   Route::post('/addPersonality', 'MemberController@addPersonality');
   Route::post('/addAcademic', 'MemberController@addAcademic');
   Route::post('/addStream', 'MemberController@addStream');
   Route::post('/addResult', 'MemberController@addResult');
   Route::post('/addAchievement', 'MemberController@addAchievement');
   Route::post('/addFamily', 'MemberController@addFamily');
   Route::post('/updatePreferredCourse', 'MemberController@updatePreferredCourse');
   Route::post('/addPathway', 'MemberController@addPathway');
   Route::post('/addStudyLocation', 'MemberController@addStudyLocation');
   Route::post('/addUniversityType', 'MemberController@addUniversityType');
   Route::post('/getSchool', 'MemberController@getSchool');
   Route::get('/get-subcategories', 'MemberController@getSubcategories');
   Route::post('/add-main-sub-cat', 'MemberController@addMainSubCat');


   //explore
   Route::get('/explore', 'MemberController@explore');
   Route::post('/getUniversityJs', 'MemberController@getUniversityJs');
   Route::post('/getCoursesJs', 'MemberController@getCoursesJs');
   Route::post('/getSubCatByMainCat', 'MemberController@getSubCatByMainCat');
   Route::post('/getScholarship', 'MemberController@getScholarship');
   Route::post('/getK12School', 'MemberController@getK12School');

   //visit campus
   Route::get('/visit-campus', 'MemberController@visitCampus');
   Route::post('/submitVisitCampus', 'MemberController@submitVisitCampus');
   Route::post('/getBranchByUni', 'MemberController@getBranchByUni');

   //uni details
   Route::get('/university/{code}', 'MemberController@university');
   Route::post('/getCoursesByUni', 'MemberController@getCoursesByUni');
   Route::post('/getScholarshipByUni', 'MemberController@getScholarshipByUni');

   //user profile
   Route::post('/getUserProfileJs', 'MemberController@getUserProfileJs');
   Route::post('/forgetPassword', 'MemberController@forgetPassword');
   Route::post('/uploadDocument', 'MemberController@uploadDocument');
   Route::post('/updateProfile', 'MemberController@updateProfile');
   Route::post('/submitUserDocument', 'MemberController@submitUserDocument');
   Route::post('/deleteUserDocument', 'MemberController@deleteUserDocument');

   //get user character
   Route::get('/characters', 'MemberController@characters');
   Route::post('/getRecommendedCourse', 'MemberController@getRecommendedCourse');
   Route::post('/getAllRecommendedCourse', 'MemberController@getAllRecommendedCourse');

   //course 
   Route::get('/course-details/{url}', 'MemberController@courseDetails');
   Route::get('/course-application/{url}', 'MemberController@courseApplication');
   Route::post('/submitCourseApplication', 'MemberController@submitCourseApplication');
   Route::get('/consult-counsellor/{url}', 'MemberController@consultCounsellor');
   Route::post('/submitConsultConsellor', 'MemberController@submitConsultConsellor');
   Route::post('/getCoursePrice', 'MemberController@getCoursePrice');

   //k12 
   Route::get('/k12-details/{code}', 'MemberController@k12Details');
   Route::post('/getScholarshipBySchool', 'MemberController@getScholarshipBySchool');
   Route::get('/k12-scholarship-details/{url}', 'MemberController@getk12ScholarshipDetails');

   //contact 
   Route::get('/contact-us', 'MemberController@contactUs');
   Route::post('/submitContact', 'MemberController@submitContact');
  
   //compare courses 
   Route::get('/compare-course', 'MemberController@compareCourse');
   Route::post('/getCompareCourses', 'MemberController@getCompareCourses');
   Route::post('/getCourseInfo', 'MemberController@getCourseInfo');

   //logout 
   Route::get('/logout', 'MemberController@logout');
   
   Route::group(['middleware' => ['isAdmin', 'localization']], function() {
   Route::group(['prefix' => 'admin'], function(){
     Route::get('/dashboard', 'AdminController@dashboard');
     Route::get('/adminLogout', 'AdminController@adminLogout');

     Route::get('/popup', 'AdminController@popup');
     Route::post('/updatePopUp', 'AdminController@updatePopUp');
     
     Route::get('/home', 'AdminController@home');
     Route::post('/updateBg', 'AdminController@updateBg');

     Route::get('/blog', 'AdminController@blog');
     Route::post('/addBlog', 'AdminController@addBlog');
     Route::post('/pinBlog', 'AdminController@pinBlog');
     Route::get('/editblogform', 'AdminController@editblogform');
     Route::post('/editBlog', 'AdminController@editBlog');
     Route::post('/deleteBlog', 'AdminController@deleteBlog');
     Route::get('/blogcat', 'AdminController@blogcat');
     Route::post('/addBlogCat', 'AdminController@addBlogCat');
     Route::post('/deleteBlogCat', 'AdminController@deleteBlogCat');

     Route::get('/event', 'AdminController@event');
     Route::post('/addEvent', 'AdminController@addEvent');
     Route::get('/editeventform', 'AdminController@editeventform');
     Route::post('/editEvent', 'AdminController@editEvent');
     Route::post('/deleteEvent', 'AdminController@deleteEvent');

     Route::get('/job', 'AdminController@job');
     Route::get('/jobcat', 'AdminController@jobcat');
     Route::post('/addJobCategory', 'AdminController@addJobCategory');
     Route::post('/deleteJobCategory', 'AdminController@deleteJobCategory');
     Route::post('/addJob', 'AdminController@addJob');
     Route::post('/editJob', 'AdminController@editJob');
     Route::post('/deleteJob', 'AdminController@deleteJob');

     Route::get('/university', 'AdminController@university');
     Route::get('/get-uni-branches', 'AdminController@getUniBranches');
     Route::post('/addUniEmail', 'AdminController@addUniversityEmail');
     Route::get('/viewuniversity', 'AdminController@viewuniversity');
     Route::get('/viewuniversityemail', 'AdminController@viewuniversityemail');
     Route::post('/deleteUniversityEmail', 'AdminController@deleteUniversityEmail');
     Route::get('/adduniversityform', 'AdminController@adduniversityform');
     Route::get('/universitytype', 'AdminController@universitytype');
     Route::post('/addUniversityType', 'AdminController@addUniversityType');
     Route::post('/deleteUniversityType', 'AdminController@deleteUniversityType');
     Route::get('/get-countries', 'AdminController@getCountries');
     Route::get('/get-states', 'AdminController@getStates');
     Route::get('/get-states-by-country', 'AdminController@getStatesByCountry');
     Route::get('/edituniversityform', 'AdminController@edituniversityform');
     Route::get('/getUniversityDetails/{id}', 'UniversityController@getUniversityDetails');
     Route::post('/addUniversity', 'AdminController@addUniversity');
     Route::post('/editUniversity', 'AdminController@editUniversity');
     Route::post('/deleteUniversity', 'AdminController@deleteUniversity');
     Route::post('/hideUniversity', 'AdminController@hideUniversity');
     Route::post('/addIntake', 'AdminController@addIntake');
     Route::post('/deleteIntake', 'AdminController@deleteIntake');

     Route::get('/k-12', 'AdminController@internationalschool');
     Route::get('/addk12form', 'AdminController@addinternationalschoolform');
     Route::get('/editk12form', 'AdminController@editinternationalschoolform');
     Route::post('/addK12', 'AdminController@addInternationalSchool');
     Route::post('/editK12', 'AdminController@editInternationalSchool');
     Route::post('/deleteK12', 'AdminController@deleteInternationalSchool');

     Route::get('/visitcampus', 'AdminController@visitcampus');
     Route::get('/viewvisitcampus', 'AdminController@viewvisitcampus');
     Route::get('/courseapplication', 'AdminController@courseapplication');
     Route::get('/viewcourseapplication', 'AdminController@viewcourseapplication');
     Route::get('/counsellor', 'AdminController@counsellor');
     Route::get('/viewconsultcounsellor', 'AdminController@viewconsultcounsellor');
     Route::post('/deletecounsellor', 'AdminController@deletecounsellor');
     Route::get('/enquiries', 'AdminController@enquiries');
     Route::post('/deleteenquiries', 'AdminController@deleteenquiries');

     Route::get('/course', 'AdminController@course');
     Route::get('/addcourseform', 'AdminController@addcourseform');
     Route::get('/adddescform', 'AdminController@adddescform');
     Route::post('/addCourse', 'AdminController@addCourse');
     Route::post('/addDesc', 'AdminController@addDesc');
     Route::get('/get-subcategories', 'AdminController@getSubcategories');
     Route::get('/get-mingrade', 'AdminController@getMingrade');
     Route::get('/get-qlevel', 'AdminController@getQlevel');
     Route::get('/get-qlevelgrade', 'AdminController@getQgrade');
     Route::get('/editcourseform', 'AdminController@editcourseform');
     Route::get('/get-coursegrade', 'AdminController@getCoursegrade');
     Route::post('/editCourse', 'AdminController@editCourse');
     Route::post('/deleteCourse', 'AdminController@deleteCourse');
     Route::post('/hideCourse', 'AdminController@hideCourse');

     Route::get('/category', 'AdminController@category');
     Route::post('/addCategory', 'AdminController@addCategory');
     Route::post('/editCategory', 'AdminController@editCategory');
     Route::post('/deleteCategory', 'AdminController@deleteCategory');
     Route::get('/viewmaincat', 'AdminController@viewmaincat');
     Route::post('/addSubCat', 'AdminController@addSubCat');
     Route::post('/editSubCat', 'AdminController@editSubCat');
     Route::post('/deleteSubCat', 'AdminController@deleteSubCat');

     Route::get('/scholarship', 'AdminController@scholarship');
     Route::get('/addscholarshipform', 'AdminController@addscholarshipform');
     Route::post('/addScholarship', 'AdminController@addScholarship');
     Route::get('/editscholarshipform', 'AdminController@editscholarshipform');
     Route::post('/editScholarship', 'AdminController@editScholarship');
     Route::post('/deleteScholarship', 'AdminController@deleteScholarship');

     Route::get('/k12scholarship', 'AdminController@k12scholarship');
     Route::get('/addk12scholarshipform', 'AdminController@addk12scholarshipform');
     Route::post('/addK12Scholarship', 'AdminController@addK12Scholarship');
     Route::get('/editk12scholarshipform', 'AdminController@editk12scholarshipform');
     Route::post('/editK12Scholarship', 'AdminController@editK12Scholarship');
     Route::post('/deleteK12Scholarship', 'AdminController@deleteK12Scholarship');

     Route::get('/subjects', 'AdminController@subjects');
     Route::post('/addQualificationLevel', 'AdminController@addQualificationLevel');
     Route::post('/deleteQualificationLevel', 'AdminController@deleteQualificationLevel');
     Route::get('/viewsubjects', 'AdminController@viewsubjects');
     Route::post('/addSubject', 'AdminController@addSubject');
     Route::post('/editSubject', 'AdminController@editSubject');
     Route::post('/deleteSubject', 'AdminController@deleteSubject');

     Route::get('/subjectscategories', 'AdminController@subjectscategories');
     Route::post('/addsubjectCategory', 'AdminController@addsubjectCategory');
     Route::post('/deletesubjectCategory', 'AdminController@deletesubjectCategory');

     Route::get('/subjectsgrades', 'AdminController@subjectsgrades');
     Route::get('/viewgrades', 'AdminController@viewgrades');
     Route::post('/editGrade', 'AdminController@editGrade');

     Route::get('/users', 'AdminController@users');
     Route::get('/viewuser', 'AdminController@viewuser');
     Route::get('/characters', 'AdminController@characters');
     Route::post('/addCharacter', 'AdminController@addCharacter');
     Route::post('/editCharacter', 'AdminController@editCharacter');
     Route::get('/get-description', 'AdminController@getDesc');
     Route::post('/deleteCharacter', 'AdminController@deleteCharacter');

     Route::get('/personality', 'AdminController@personality');
     Route::post('/addPersonality', 'AdminController@addPersonality');
     Route::post('/editPersonality', 'AdminController@editPersonality');
     Route::post('/deletePersonality', 'AdminController@deletePersonality');

     Route::get('/schools', 'AdminController@schools');
     Route::post('/addSchools', 'AdminController@addSchools');
     Route::post('/editSchools', 'AdminController@editSchools');
     Route::post('/deleteSchools', 'AdminController@deleteSchools');

     Route::get('/users/export', 'AdminController@exportUsers')->name('export.users');
     Route::get('/courseapplication/export', 'AdminController@exportCourseApplication')->name('export.courseapplication');
     Route::get('/counsellor/export', 'AdminController@exportCounsellor')->name('export.counsellor');
     Route::get('/campusvisit/export', 'AdminController@exportCampusVisit')->name('export.campusvisit');
     Route::get('/enquiries/export', 'AdminController@exportEnquiries')->name('export.enquiries');

   });
  });

   Route::get('/admin/sign-in', 'AdminController@login');
   Route::post('/admin/adminLogin', 'AdminController@adminLogin');

   Route::get('/register-interest', 'MemberController@registerInterest');
   Route::post('/submitInterest', 'MemberController@submitInterest');
   Route::get('/view-register-interest', 'MemberController@viewRegisterInterest');
   Route::post('/getRegisterInterest', 'MemberController@getRegisterInterest');
   Route::get('/add-banner', 'MemberController@addBanner');
   Route::post('/submitAddBanner', 'MemberController@submitAddBanner');

   //clear cache
   Route::get('/ttos-only/clear-cache', function () {
     try {
       \Artisan::call('config:clear');
       \Artisan::call('cache:clear');
       \Artisan::call('route:clear');
       \Artisan::call('view:clear');
       \Artisan::call('clear-compiled');
       \Artisan::call('optimize');
     } catch (\Exception $e) {
     }

     echo '<pre>cache cleared</pre>';
     echo '<button onclick="location=\'/\'">Home</button>';
   });

 });


/***************************************************************************
 * Admin only
 ***************************************************************************/

// Route::group(['middleware' => ['isAdmin', 'localization']], function() {
//   Route::group(['prefix' => 'admin'], function(){
//
//   });
// });
