<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Http\Request;

class SubjectCategory extends Model
{
	public $timestamps = false;
	public $primaryKey = "sc_id";
	protected $table = "subject_categories";

	protected $fillable = [
		'sc_name', 'sc_status', 'created_at', 'updated_at'
	];

	public static function addsubjectCategory(Request $r) {
        try {
            $dt = Gen::currTime();
        
            $add = SubjectCategory::create([
                'sc_name' => $r->title,
                'sc_status' => 1,
                'created_at' => $dt,
                'updated_at' => $dt,
            ]);
        
            // Check if the category was added successfully
            if ($add) {
                return true;
            }
    
        } catch (\Exception $e) {
            \Log::error($e);
        }
        
        return false; // Return false in case of an error or failure
    }

	/***********************************************************************
	 * Get
	 ***********************************************************************/
   /**
     * getAll
     */
	 public static function getAllActive()
   {
       return SubjectCategory::where('sc_status', '=', 1)->get();
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

}
