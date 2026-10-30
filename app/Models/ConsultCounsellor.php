<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Http\Request;

use Lang;

class ConsultCounsellor extends Model
{
    public $timestamps = false;
    public $primaryKey = "cc_id";
    protected $table = "consult_counsellor";

    protected $fillable = [
        'cc_c_id', 'cc_salutation', 'cc_name', 'cc_gender', 'cc_dob', 'cc_school_name', 'cc_contact_no', 'cc_home_contact_no', 'cc_course', 'cc_qualification', 'cc_transcript', 'cc_remarks', 'cc_status', 'created_at', 'updated_at'
    ];

    /***********************************************************************
	 * Get
	 ***********************************************************************/
    public static function getById($id)
    {
        try {
            if(isset($id)) {
                return ConsultCounsellor::where('cc_id', '=', $id)
                ->leftJoin('courses as a', 'a.c_id', '=', 'cc_c_id')
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
          return ConsultCounsellor::where('cc_status', '=', 1)
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
    public static function addConsult($r)
    {
        try {
            $dt = Gen::currTime();

            $add = ConsultCounsellor::create([
                'cc_c_id' => $r->course_id,
                'cc_salutation' => $r->salutation,
                'cc_name' => $r->name,
                'cc_gender' => $r->gender,
                'cc_dob' => $r->dob_date.'-'.$r->dob_month.'-'.$r->dob_year,
                'cc_school_name' => $r->school_name,
                'cc_contact_no' => $r->contact_no,
                'cc_home_contact_no' => $r->home_contact_no,
                'cc_course' => $r->preferred_course,
                'cc_qualification' => $r->qualification,
                'cc_remarks' => $r->remarks,
                'cc_status' => 1,
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

                    $add->cc_transcript = trim($transcript, ',');
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
}