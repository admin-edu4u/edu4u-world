<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Http\Request;

use Lang;

class CourseApplication extends Model
{
    public $timestamps = false;
    public $primaryKey = "ca_id";
    protected $table = "course_application";

    protected $fillable = [
        'ca_c_id', 'ca_salutation', 'ca_name', 'ca_dob', 'ca_ic_num', 'ca_gender', 'ca_nationality', 'ca_martial_status', 'ca_ethnicity', 'ca_religion','ca_home_phone','ca_phone','ca_email','ca_e_name','ca_e_contact','ca_e_email','ca_e_relationship','ca_p_addr','ca_p_city',
        'ca_p_state', 'ca_p_country','ca_p_postcode','ca_m_addr','ca_m_city','ca_m_state','ca_m_country','ca_m_postcode','ca_qualification','ca_qualification_school_name','ca_qualification_year','ca_credit_transfer','ca_first_family','ca_transcript','ca_resume','ca_e_qualification',
        'ca_e_score', 'ca_e_year','ca_h_income','ca_programme_name','ca_programme_month','ca_programme_year','ca_has_disability','ca_disability_desc','ca_status','created_at','updated_at'
    ];

    /***********************************************************************
	 * Get
	 ***********************************************************************/
    public static function getById($id)
    {
        try {
            if(isset($id)) {
                return CourseApplication::where('ca_id', '=', $id)
                ->leftJoin('courses as a', 'a.c_id', '=', 'ca_c_id')
                ->leftJoin('universities as b', 'b.u_id', '=', 'a.c_uni')
                ->first();
            }
        } catch (\Exception $e) {
          \Log::error($e);
        }
        return null;
    }

    public static function getAll() {
        try{
          return CourseApplication::where('ca_status', '=', 1)
          ->orderBy('created_at', 'desc')
          ->get();
        }catch (\Exception $e) {
          \Log::error($e);
        }
        return null;
    }

    /***********************************************************************
	 * Add
	 ***********************************************************************/
    public static function addApplication($r)
    {
        try {
            $dt = Gen::currTime();

            $add = CourseApplication::create([
                'ca_c_id' => $r->course_id,
                'ca_salutation' => $r->salutation,
                'ca_name' => $r->name,
                'ca_dob' => $r->dob_date.'-'.$r->dob_month.'-'.$r->dob_year,
                'ca_ic_num' => $r->ic_num,
                'ca_gender' => $r->gender,
                'ca_nationality' => $r->nationality,
                'ca_martial_status' => $r->martial_status,
                'ca_ethnicity' => $r->ethnicity,
                'ca_religion' => $r->religion,
                'ca_home_phone' => $r->home_contact_no,
                'ca_phone' => $r->contact_no,
                'ca_email' => $r->email,
                'ca_e_name' => $r->emergency_contact_name,
                'ca_e_contact' => $r->emergency_contact_no,
                'ca_e_email' => $r->emergency_contact_email,
                'ca_e_relationship' => $r->relationship,
                'ca_p_addr' => $r->p_addr,
                'ca_p_city' => $r->p_city,
                'ca_p_state' => $r->p_state,
                'ca_p_country' => $r->p_country,
                'ca_p_postcode' => $r->p_postcode,
                'ca_m_addr' => $r->is_same_addr == 1 ? $r->p_addr : $r->m_addr,
                'ca_m_city' =>  $r->is_same_addr == 1 ? $r->p_city : $r->m_city,
                'ca_m_state' =>  $r->is_same_addr == 1 ? $r->p_state : $r->m_state,
                'ca_m_country' =>  $r->is_same_addr == 1 ? $r->p_country : $r->m_country,
                'ca_m_postcode' =>  $r->is_same_addr == 1 ? $r->p_postcode : $r->m_postcode,
                'ca_qualification' => $r->qualification,
                'ca_qualification_school_name' => $r->school_name,
                'ca_qualification_year' => $r->year_completion,
                'ca_credit_transfer' => $r->credit_transfer,
                'ca_first_family' => $r->member_uni,
                'ca_e_qualification' => $r->english_q,
                'ca_e_score' => $r->english_score,
                'ca_e_year' => $r->english_year,
                'ca_h_income' => $r->income,
                'ca_programme_name' => $r->programme_name,
                'ca_programme_month' => $r->programme_month,
                'ca_programme_year' => $r->programme_year,
                'ca_has_disability' => $r->disability,
                'ca_disability_desc' => $r->disbaility_name,
                'ca_status' => 1,
                'created_at' => $dt,
                'updated_at' => $dt,
            ]);

            if(isset($add)) {

                if(isset($r->transcript) && count($r->transcript) > 0) {
                    $transcript = '';
                    foreach ($r->transcript as $t) {
                        $extension = $t->extension();

                        if($extension == 'pdf') {
                            $name = "transcript-" . uniqid() . '.pdf';
                        }else {
                            $name = "transcript-" . uniqid() . '.jpg';
                        }

                        $transcript .= $name.',';

                        $t->move(
                            public_path('/images/application'),
                            $name
                        );
                    }

                    $add->ca_transcript = trim($transcript, ',');
                }

                if(isset($r->resume) && count($r->resume) > 0) {
                    $resume = '';
                    foreach ($r->resume as $re) {
                        $extension = $re->extension();

                        if($extension == 'pdf') {
                            $name = "resume-" . uniqid() . '.pdf';
                        }else {
                            $name = "resume-" . uniqid() . '.jpg';
                        }

                        $resume .= $name.',';

                        $re->move(
                            public_path('/images/application'),
                            $name
                        );
                    }

                    $add->ca_resume = trim($resume, ',');
                }

                $add->save();
                
                return $add;
            }
        } catch (\Exception $e) {
            \Log::error($e);
        }
        return null;
    }

    /***********************************************************************
	* Gender Arr
	***********************************************************************/
    public static function genderArr($index = null) {
        $arr = array();
        $arr[1] = 'Male';
        $arr[2] = 'Female';
        
        if (isset($index)) {
			return $arr[$index];
		}

        return $arr;
    }

    /***********************************************************************
	* Nationality Arr
	***********************************************************************/
    public static function nationalityArr($index = null) {
        $arr = array();
        $arr[1] = 'Malaysian';
        $arr[2] = 'Non Malaysian';
        
        if (isset($index)) {
			return $arr[$index];
		}

        return $arr;
    }

    /***********************************************************************
	* Marital Status Arr
	***********************************************************************/
    public static function martialArr($index = null) {
        $arr = array();
        $arr[1] = 'Single';
        $arr[2] = 'Married';
        $arr[3] = 'Divorced';
        $arr[4] = 'Widowed';
        
        if (isset($index)) {
			return $arr[$index];
		}

        return $arr;
    }

    /***********************************************************************
	* Ethnicity Arr
	***********************************************************************/
    public static function ethnicityArr($index = null) {
        $arr = array();
        $arr[1] = 'Malay';
        $arr[2] = 'Chinese';
        $arr[3] = 'Indian';
        $arr[4] = 'Others';
        
        if (isset($index)) {
			return $arr[$index];
		}

        return $arr;
    }

    /***********************************************************************
	* Religion Arr
	***********************************************************************/
    public static function religionArr($index = null) {
        $arr = array();
        $arr[1] = 'Muslim';
        $arr[2] = 'Christian';
        $arr[3] = 'Hindus';
        $arr[4] = 'Others';
        
        if (isset($index)) {
			return $arr[$index];
		}

        return $arr;
    }

    /***********************************************************************
	* Relationship Arr
	***********************************************************************/
    public static function relationshipArr($index = null) {
        $arr = array();
        $arr[1] = 'Parents';
        $arr[2] = 'Sibilings';
        $arr[3] = 'Partner';
        $arr[4] = 'Others';
        
        if (isset($index)) {
			return $arr[$index];
		}

        return $arr;
    }

    /***********************************************************************
	* Credit Transfer Arr
	***********************************************************************/
    public static function creditArr($index = null) {
        $arr = array();
        $arr[1] = 'Yes';
        $arr[2] = 'No';
        
        if (isset($index)) {
			return $arr[$index];
		}

        return $arr;
    }

    /***********************************************************************
	* Family Arr
	***********************************************************************/
    public static function familyArr($index = null) {
        $arr = array();
        $arr[1] = 'Yes';
        $arr[2] = 'No';
        
        if (isset($index)) {
			return $arr[$index];
		}

        return $arr;
    }

    /***********************************************************************
	* English Qualification Arr
	***********************************************************************/
    public static function englishQualificationArr($index = null) {
        $arr = array();
        $arr[1] = 'MUET';
        $arr[2] = 'ILETS';
        $arr[3] = 'TOEFL';
        $arr[4] = 'GSCE/GCE O Level';
        
        if (isset($index)) {
			return $arr[$index];
		}

        return $arr;
    }

    /***********************************************************************
	* Income Arr
	***********************************************************************/
    public static function incomeArr($index = null) {
        $arr = array();
        $arr[1] = 'Below MYR 4,000';
        $arr[2] = 'Below MYR 8,000';
        $arr[3] = 'Above MYR 8,000';
        
        if (isset($index)) {
			return $arr[$index];
		}

        return $arr;
    }

    /***********************************************************************
	* Disability Arr
	***********************************************************************/
    public static function disabilityArr($index = null) {
        $arr = array();
        $arr[1] = 'Yes';
        $arr[2] = 'No';
        
        if (isset($index)) {
			return $arr[$index];
		}

        return $arr;
    }
}