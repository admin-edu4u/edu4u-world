<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Http\Request;

class UniversityType extends Model
{
	public $timestamps = false;
	public $primaryKey = "ut_id";
	protected $table = "university_type";

	protected $fillable = [
		'ut_name', 'ut_status', 'ut_type', 'created_at', 'updated_at'
	];

    /***********************************************************************
	 * Get
	 ***********************************************************************/
	 public static function getAll()
     {
         return UniversityType::where('ut_status', '=', 1)->get();
     }

    /***********************************************************************
	* Add
	***********************************************************************/
	public static function addUniversityType(Request $r) {
        try {
            $dt = Gen::currTime();
        
            $add = UniversityType::create([
                'ut_name' => $r->unitype,
                'ut_type' => $r->utype,
                'ut_status' => 1,
                'created_at' => $dt,
                'updated_at' => $dt,
            ]);
        
            if ($add) {
                return true;
            }
    
        } catch (\Exception $e) {
            \Log::error($e);
        }
        
        return false;
    }

	/***********************************************************************
	 * Array
	 ***********************************************************************/
	public static function statusArr() {
		$arr = array();
		$arr[1] = 'Active';
		$arr[99] = 'Deactivate';
		return $arr;
	}

    public static function typeArr() {
        $arr = array();
        $arr[1] = 'Private';
        $arr[2] = 'Public';
        return $arr;
    }

}
