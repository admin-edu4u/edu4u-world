<?php

namespace App\Models;

use Illuminate\Notifications\Notifiable;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Http\Request;

use Session;
use Lang;
use DB;


class User extends Authenticatable
{
    use Notifiable;

    public $timestamps = false;


    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'mlm_id', 'email', 'username', 'fullname', 'first_name', 'last_name', 'password', 'contact_code', 'contact_no', 'isActive', 'isAdmin', 'referral', 'dob_day', 'dob_month', 'dob_year', 'is_malaysian', 'gender', 'profile_pic', 'session_no', 'preferred_course', 'character', 'interests', 'pref_main_cat', 'pref_sub_cat', 'stream', 'created_at', 'updated_at'
    ];



    /**
     * The attributes that should be hidden for arrays.
     *
     * @var array
     */
    protected $hidden = [
        // 'password', 'remember_token'
        'remember_token'
    ];


    /***********************************************************************
     * By
     ***********************************************************************/
     public static function byContact($contact_code, $contact_no)
   	{
   		try {
   			if (isset($contact_code, $contact_no)) {
   				return User::where([
   					['contact_code', '=', trim($contact_code)],
   					['contact_no', '=', trim($contact_no)],
   				])
   					->first();
   			}
   		} catch (\Exception $e) {
   			\Log::error($e);
   		}

   		return null;
   	}

    public static function getByEmail($email) {
      if (isset($email)) {
          return User::where('email', '=', $email)->first();
      }

      return null;
    }

    public static function bySessionNo($mlm_id, $session_no)
  	{
  		try {
  			if (isset($mlm_id, $session_no)) {
  				return User::where([
  					['session_no', '=', $session_no],
  					['mlm_id', '=', $mlm_id]
  				])
  					->first();
  			}
  		} catch (\Exception $e) {
  			\Log::error($e);
  		}

  		return null;
  	}

    public static function getById($id)
    {
      try{
        if(isset($id)){
          return User::where('id', '=', $id)->first();
        }
      } catch (\Exception $e) {
        \Log::error($e);
      }

      return null;
    }

    public static function getNameById($id)
    {
      try{
        if(isset($id)){
          $u = User::where('id', '=', $id)->first();

          if(isset($u)) return $u->first_name.' '.$u->last_name;
        }
      } catch (\Exception $e) {
        \Log::error($e);
      }

      return null;
    }

    public static function byMlmId($id)
    {
      try{
        if(isset($id)){
          return User::where('mlm_id', '=', $id)->first();
        }
      } catch (\Exception $e) {
        \Log::error($e);
      }

      return null;
    }

    /***********************************************************************
     * Get
     ***********************************************************************/
     public static function getAllReferral($id)
     {
       try{
         if(isset($id)){
           return User::where('referral', '=', $id)->get();
         }
       } catch (\Exception $e) {
   			\Log::error($e);
   		}

   		return null;
     }
     /**
   	 * genMlmId
   	 */
   	public static function genMlmId($id)
   	{
   		if (isset($id)) {
   			$code = "EDU";
   			$id += 2000; //increment id value by 2000

   			return $code .  str_pad($id, 8, "0", STR_PAD_LEFT);
   		}

   		return null;
   	}

    public static function getAllBranchAdmin()
    {
      try{
        return User::where([
          ['isBranchAdmin', '=', 1],
          ['isActive', '=', 1]
        ])
        ->get();
      }catch (\Exception $e) {
       \Log::error($e);
     }

     return null;
    }

    public static function getAllTeacher()
    {
      try{
        return User::where([
          ['isTeacher', '=', 1],
          ['isActive', '=', 1]
        ])
        ->get();
      }catch (\Exception $e) {
       \Log::error($e);
     }

     return null;
    }

    public static function getAll() {
      try{
        return User::where('isAdmin', '=', 0)
        ->orderBy('mlm_id', 'asc')
        ->get();
      }catch (\Exception $e) {
        \Log::error($e);
      }
      return null;
    }

    /***********************************************************************
     * Table
     ***********************************************************************/
     public static function getBranchAdmin(Request $request)
     {
       try{
         if(isset($request)) {
           $records_start = 0;
           if($request->records_start != null) $records_start = $request->records_start;

           $page_size = 25;
           if($request->page_size != null) $page_size = $request->page_size;

           $param = array();
           $param[] = array('isActive', '=', '1');
           $param[] = array('isBranchAdmin', '=', '1');

           return User::where($param)
           ->where(function($q) use($request) {
             if($request->search != null) {
               $q->where('contact_no', 'LIKE', '%' . $request->search . '%')
               ->orWhere(DB::raw('concat(first_name," ",last_name)'), 'LIKE', '%' . $request->search . '%')
               ->orWhere('a.b_name', 'LIKE', '%' . $request->search . '%');
             }
           })
           ->selectRaw('id, password, first_name, last_name, contact_code, contact_no, b_name, users.created_at, dob_day, dob_month, dob_year, gender, profile_pic')
           ->leftJoin('branches as a', 'a.b_user_id', '=', 'id')
           ->skip($records_start)
           ->limit($page_size)
           ->orderBy('users.created_at', 'desc')
           ->get();
         }
       } catch (\Exception $e) {
         \Log::error($e);
       }

       return null;
     }

     public static function getTotalBranchAdmin(Request $request)
     {
       try{
         if(isset($request)) {
           $param = array();
           $param[] = array('isActive', '=', '1');
           $param[] = array('isBranchAdmin', '=', '1');

           $u = User::where($param)
           ->where(function($q) use($request) {
             if($request->search != null) {
               $q->where('contact_no', 'LIKE', '%' . $request->search . '%')
               ->orWhere(DB::raw('concat(first_name," ",last_name)'), 'LIKE', '%' . $request->search . '%')
               ->orWhere('a.b_name', 'LIKE', '%' . $request->search . '%');
             }
           })
           ->leftJoin('branches as a', 'a.b_user_id', '=', 'id')
           ->selectRaw('COALESCE(COUNT(id), 0) as total')
           ->first();

           if(isset($u)) return $u->total;
         }
       } catch (\Exception $e) {
         \Log::error($e);
       }

       return 0;
     }

     public static function getTeacher(Request $request)
     {
       try{
         if(isset($request)) {
           $records_start = 0;
           if($request->records_start != null) $records_start = $request->records_start;

           $page_size = 25;
           if($request->page_size != null) $page_size = $request->page_size;

           $param = array();
           $param[] = array('isActive', '=', '1');
           $param[] = array('isTeacher', '=', '1');

           return User::where($param)
           ->where(function($q) use($request) {
             if($request->search != null) {
               $q->where(DB::raw('concat(contact_code,contact_no)'), 'LIKE', '%' . $request->search . '%')
               ->orWhere(DB::raw('concat(first_name," ",last_name)'), 'LIKE', '%' . $request->search . '%');
             }
           })
           ->skip($records_start)
           ->limit($page_size)
           ->orderBy('created_at', 'desc')
           ->get();
         }
       } catch (\Exception $e) {
         \Log::error($e);
       }

       return null;
     }

     public static function getTotalTeacher(Request $request)
     {
       try{
         if(isset($request)) {
           $records_start = 0;
           if($request->records_start != null) $records_start = $request->records_start;

           $page_size = 25;
           if($request->page_size != null) $page_size = $request->page_size;

           $param = array();
           $param[] = array('isActive', '=', '1');
           $param[] = array('isTeacher', '=', '1');

           $u = User::where($param)
           ->where(function($q) use($request) {
             if($request->search != null) {
               $q->where(DB::raw('concat(contact_code,contact_no)'), 'LIKE', '%' . $request->search . '%')
               ->orWhere(DB::raw('concat(first_name," ",last_name)'), 'LIKE', '%' . $request->search . '%');
             }
           })
           ->selectRaw('COALESCE(COUNT(id), 0) as total')
           ->first();

           if(isset($u)) return $u->total;
         }
       } catch (\Exception $e) {
         \Log::error($e);
       }

       return 0;
     }

     public static function getTotalUser()
     {
       try{
         $total = 0;
         $parent = User::where('isActive', '=', 1)->selectRaw('COALESCE(COUNT(id), 0) as total')->first();
         $child = Profile::where([
           ['p_status', '=', 1],
           ['a.isActive', '=', 1]
         ])
         ->leftJoin('users as a', 'a.id', '=', 'p_parent_id')
         ->selectRaw('COALESCE(COUNT(p_id), 0) as total')
         ->first();

         if(isset($parent)){
           if(isset($child)){
             $total = $parent->total + $child->total;
           }else{
             $total = $parent->total;
           }
         }

         return $total;
       } catch (\Exception $e) {
         \Log::error($e);
       }

       return 0;
     }

    /***********************************************************************
     * Add
     ***********************************************************************/

    /**
     * addUser
     */
     public static function addUser(Request $request) {
       if(isset($request)) {

         \DB::beginTransaction();

         try{
           $dt = Gen::currTime();
           $u = User::create([
             'fullname' => $request->fullname,
             'username' => $request->name,
             'email' => $request->email,
             'referral' => ($request->referral_id != null) ? $request->referral_id : 1,
             'password' => encrypt($request->password),
             'contact_no' => $request->contact_no,
             'contact_code' => $request->contact_code,
             'isActive' => ($request->is_active != null) ? $request->is_active : 0,
             'member_type' => 0,
             'gender' => ($request->gender != null) ? $request->gender : 0,
             'dob_day' => isset($request->dob_day) ? $request->dob_day : 0,
             'dob_month' => isset($request->dob_month) ? $request->dob_month : 0,
             'dob_year' => isset($request->dob_year) ? $request->dob_year : 0,
             'is_malaysian' => $request->is_malaysian,
             'created_at' => $dt,
             'updated_at' => $dt,
           ]);

           if(isset($u)) {
             $mlm_id  = User::genMlmId($u->id);
             if(isset($mlm_id)) {
               $u->mlm_id = $mlm_id;
             }
             $u->save();

             DB::commit();
             return $u;
           }
         }catch(\Exception $e) {
           \Log::error($e);
         }
       }

       DB::rollback();
       return null;
     }

     /**
      * add branch admin
      */
     public static function addBranchAdmin(Request $request) {
       if(isset($request)) {

         \DB::beginTransaction();

         try{
           //$is_active = (\Session::get('login_admin') == 1) ? 1 : 0;
           $dt = Gen::currTime();
           $u = User::create([
             'first_name' => $request->first_name,
             'last_name' => $request->last_name,
             'referral' => ($request->referral_id != null) ? $request->referral_id : 1,
             'password' => encrypt($request->password),
             'contact_no' => $request->contact_no,
             'contact_code' => $request->contact_code,
             'isActive' => 1,
             'gender' => ($request->gender != null) ? $request->gender : 0,
             'dob_day' => isset($request->dob_day) ? $request->dob_day : 0,
             'dob_month' => isset($request->dob_month) ? $request->dob_month : 0,
             'dob_year' => isset($request->dob_year) ? $request->dob_year : 0,
             'isBranchAdmin' => 1,
             'created_at' => $dt,
             'updated_at' => $dt,
           ]);

           if(isset($u)) {

             if ($request->profile_pic != null) {
               $profile = 'profile-' . uniqid() . '.jpg';
               $request->profile_pic->move(
                 public_path('/images/profile'),
                 $profile
               );
               $u->profile_pic = $profile;
             }

             $mlm_id  = User::genMlmId($u->id);
             if(isset($mlm_id)) {
               $u->mlm_id = $mlm_id;
             }
             $u->save();

             DB::commit();
             return $u;
           }
         }catch(\Exception $e) {
           \Log::error($e);
         }
       }

       DB::rollback();
       return null;
     }

     /**
      * add branch admin
      */
     public static function addTeacher(Request $request) {
       if(isset($request)) {

         \DB::beginTransaction();

         try{
           //$is_active = (\Session::get('login_admin') == 1) ? 1 : 0;
           $dt = Gen::currTime();
           $u = User::create([
             'first_name' => $request->first_name,
             'last_name' => $request->last_name,
             'referral' => ($request->referral_id != null) ? $request->referral_id : 1,
             'password' => encrypt($request->password),
             'contact_no' => $request->contact_no,
             'contact_code' => $request->contact_code,
             'isActive' => 1,
             'gender' => ($request->gender != null) ? $request->gender : 0,
             'dob_day' => isset($request->dob_day) ? $request->dob_day : 0,
             'dob_month' => isset($request->dob_month) ? $request->dob_month : 0,
             'dob_year' => isset($request->dob_year) ? $request->dob_year : 0,
             'isTeacher' => 1,
             'created_at' => $dt,
             'updated_at' => $dt,
           ]);

           if(isset($u)) {

             if ($request->profile_pic != null) {
               $profile = 'profile-' . uniqid() . '.jpg';
               $request->profile_pic->move(
                 public_path('/images/profile'),
                 $profile
               );
               $u->profile_pic = $profile;
             }

             $mlm_id  = User::genMlmId($u->id);
             if(isset($mlm_id)) {
               $u->mlm_id = $mlm_id;
             }
             $u->save();

             DB::commit();
             return $u;
           }
         }catch(\Exception $e) {
           \Log::error($e);
         }
       }

       DB::rollback();
       return null;
     }

    /***********************************************************************
     * Edit
     ***********************************************************************/

    /**
     * editMember
     */
    public static function editMember(Request $request) {
        if(isset($request)) {
            $u = User::getById($request->member_id);
            if(isset($u)) {

                try {
                    $u->fullname = $request->fullname;
                    $u->contact_no = $request->contact_no;
                    $u->email = $request->email;
                    $u->ic = $request->ic;
                    $u->addr1 = ($request->addr1 != null) ? $request->addr1 : null;
                    $u->addr2 = ($request->addr2 != null) ? $request->addr2 : null;
                    $u->state = ($request->state != null) ? $request->state : null;
                    $u->postcode = ($request->postcode != null) ? $request->postcode : null;
                    $u->state = ($request->state != null) ? $request->state : 0;
                    $u->city = $request->city;
                    if($request->selected_type != null) {
                        $u->member_type = $request->selected_type;
                    }
                    $u->country = ($request->country != null) ? $request->country : 0;

                    if($request->bank != null) {
                        $u->bank_id = $request->bank;
                    }

                    if($request->bank_holder_name != null) {
                        $u->bank_holder_name = $request->bank_holder_name;
                    }

                    if($request->bank_acc_no != null) {
                        $u->bank_acc_no = $request->bank_acc_no;
                    }

                    if($request->gender != null) {
                        $u->gender = ($request->gender != null )?$request->gender:0;
                    }

                    if($request->referral_id != null) {
                        $u->referral = $request->referral_id;
                    }

                    $u->title = ($request->title != null) ? $request->title : 0;
                    $u->isActive = ($request->status != null) ? $request->status : 1;
                    //$u->contact_person_name = $request->contact_person_name;
                    //$u->contact_person_no = $request->contact_person_no;

                    //$u->dob_day = $request->dob_day;
                    //$u->dob_month = $request->dob_month;
                    //$u->dob_year = $request->dob_year;
                    $u->updated_at = \App\Gen::currTime();

                    $u->save();
                    return $u;

                } catch(\Exception $e) {
                    \Log::error($e);
                }
            }
        }

        return null;
    }


    /***********************************************************************
     * exists
     ***********************************************************************/


	/**
     * user exists?
     * - check by mlm id
     */
    public static function existsByMlmId($id) {
        if (isset($id)) {
            $u =  User::where('mlm_id', '=', $id)->first();
            if ($u) return true;
        }

        return false;
    }

    /***********************************************************************
  	 * Default
  	 ***********************************************************************/
     /**
   	 * perPage
   	 */
   	public static function perPage()
   	{
   		return 10;
   	}

    /***********************************************************************
     * Array
     ***********************************************************************/


    /**
     * memberTypeArr
     */
    public static function memberTypeArr() {
        $arr = array();
        $arr[0] = 'Base';
        $arr[1] = 'Bronze';
        $arr[2] = 'Silver';
        $arr[3] = 'Gold';
        $arr[4] = 'Platinum';
        return $arr;
    }

    /**
     * gerderArr
     */
    public static function genderArr() {
        $arr = array();
        $arr[1] = \Lang::get('lang.male');
        $arr[2] = \Lang::get('lang.female');
        return $arr;
    }


    /**
     * statusArr
     */
    public static function statusArr() {
        $arr = array();
        $arr[0] = \Lang::get('lang.pending');
        $arr[1] = \Lang::get('lang.active');
        $arr[4] = \Lang::get('lang.deactivated');
        $arr[99] = \Lang::get('lang.suspend');
        return $arr;
    }

    /**
     * academicArr
     */
    public static function academicArr() {
        $arr = array();
        $arr[1] = 'Standard 1';
        $arr[2] = 'Standard 2';
        $arr[3] = 'Standard 3';
        $arr[4] = 'Standard 4';
        $arr[5] = 'Standard 5';
        $arr[6] = 'Standard 6';
        // $arr[7] = 'Peralihan';
        $arr[7] = 'Form 1';
        $arr[8] = 'Form 2';
        $arr[9] = 'Form 3';
        $arr[10] = 'Form 4';
        $arr[11] = 'Form 5';
        $arr[12] = 'Form 6';
        $arr[13] = 'Graduated';
        return $arr;
    }

    /**
     * allergyArr
     */
    public static function allergyArr() {
        $arr = array();
        $arr[1] = 'Pollen Allergy (Hay Fever or Allergic Rhinitis)';
        $arr[2] = 'Mold Allergy';
        $arr[3] = 'Pet Dander Allergy';
        $arr[4] = 'Dust Mite Allergy';
        $arr[5] = 'Peanut Allergy';
        $arr[6] = 'Tree Nut Allergy';
        $arr[7] = 'Milk Allergy';
        $arr[8] = 'Egg Allergy';
        $arr[9] = 'Wheat Allergy';
        $arr[10] = 'Soy Allergy';
        $arr[11] = 'Fish Allergy';
        $arr[12] = 'Shellfish Allergy';
        $arr[13] = 'Bee Sting Allergy';
        $arr[14] = 'Wasp Sting Allergy';
        $arr[15] = 'Hornet Sting Allergy';
        $arr[16] = 'Fire Ant Allergy';
        $arr[17] = 'Penicillin Allergy';
        $arr[18] = 'Sulfa Drug Allergy';
        $arr[19] = 'Aspirin Allergy';
        $arr[20] = 'NSAIDs (Nonsteroidal Anti-Inflammatory Drugs) Allergy';
        $arr[21] = 'Latex Allergy';
        $arr[22] = 'Contact Dermatitis';
        $arr[23] = 'Eczema (Atopic Dermatitis)';
        $arr[24] = 'Urticaria (Hives)';
        $arr[25] = 'Angioedema (Swelling)';
        $arr[26] = 'Allergic Conjunctivitis';
        $arr[27] = 'Anaphylaxis';
        return $arr;
    }

    /**
     * disabilityArr
     */
    public static function disabilityArr() {
        $arr = array();
        $arr[1] = 'Mobility Impairments';
        $arr[2] = 'Orthopedic Disabilities';
        $arr[3] = 'Neuromuscular Disorders';
        $arr[4] = 'Spinal Cord Injuries';
        $arr[5] = 'Joint Disorders';
        $arr[6] = 'Congenital Disorders';
        $arr[7] = 'Chronic Pain Conditions';
        $arr[8] = 'Hearing Impairments';
        $arr[9] = 'Balance and Coordination Disorders';
        $arr[10] = 'Speech and Communication Disorders';
        return $arr;
    }

}
