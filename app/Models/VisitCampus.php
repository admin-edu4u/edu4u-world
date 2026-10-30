<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Http\Request;

class VisitCampus extends Model
{
    public $timestamps = false;
	public $primaryKey = "vc_id";
	protected $table = "visit_campus";

    protected $fillable = [
		'vc_uni_id', 'vc_branch', 'vc_salutation', 'vc_name', 'vc_contact_no', 'vc_ic_num', 'vc_pax', 'vc_date', 'vc_month', 'vc_year', 'vc_time', 'vc_remarks', 'created_at', 'updated_at'
	];



    /***********************************************************************
	 * Get
    ***********************************************************************/
    public static function getAll() {
        try {
            return VisitCampus::orderBy('vc_id', 'desc')->get();
        } catch (\Exception $e) {
            \Log::error($e);
        }
        return null;
    }    
 
    /***********************************************************************
	 * Add
	***********************************************************************/
    public static function addNewVisit(Request $r) {
        try{
            $dt = Gen::currTime();

            $add = VisitCampus::create([
                'vc_uni_id' => $r->uni_id,
                'vc_salutation' => $r->salutation,
                'vc_name' => $r->full_name,
                // 'vc_branch' => $r->has_branch == 1 ? $r->campus : NULL,
                'vc_contact_no' => $r->contact_no,
                'vc_ic_num' => $r->ic_num,
                'vc_pax' => $r->pax,
                'vc_date' => $r->date,
                'vc_month' => $r->month,
                'vc_year' => $r->year,
                'vc_time' => $r->time,
                'vc_remarks' => $r->remarks,
                'created_at' => $dt,
                'updated_at' => $dt,
            ]);

            return $add;
        
        }catch (\Exception $e) {
        \Log::error($e);
        }
        return null;
    }

    /***********************************************************************
	 * Time Arr
	 ***********************************************************************/
    public static function timeArr()
	{
		$arr[1] = '9am - 12pm';
    	$arr[2] = '1pm - 5pm';

		if (isset($index)) {
			return $arr[$index];
		}

		return $arr;
	}
}