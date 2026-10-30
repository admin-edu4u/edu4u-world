<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Http\Request;

class UniversityIntake extends Model
{
    public $timestamps = false;
	public $primaryKey = "ui_id";
	protected $table = "university_intakes";

    protected $fillable = [
		'ui_uni_id', 'ui_level_id', 'ui_month', 'ui_status', 'created_at', 'updated_at'
	];

    /***********************************************************************
	 * Get
	 ***********************************************************************/
    public static function getAll() {
        
        try{
            return UniversityIntake::where('ui_status', '=', 1)
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
  public static function addIntake(Request $r) {
    try{
      $dt = Gen::currTime();

      $add = UniversityIntake::create([
        'ui_uni_id' => $r->u_id,
        'ui_level_id' => $r->level,
        'ui_month' => implode(',', $r->intake),
        'ui_status' => 1,
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
     * Array
     ***********************************************************************/
     /**
      * memberTypeArr
      */
     public static function levelArr() {
         $arr = array();
         $arr[1] = 'A-Level';
         $arr[2] = 'Diploma';
         $arr[3] = 'Degree';
         $arr[4] = 'Masters';
         $arr[5] = 'PHD';
         return $arr;
     }

     public static function monthArr() {
        $arr = array();
        $arr[1] = 'January';
        $arr[2] = 'February';
        $arr[3] = 'March';
        $arr[4] = 'April';
        $arr[5] = 'May';
        $arr[6] = 'June';
        $arr[7] = 'July';
        $arr[8] = 'August';
        $arr[9] = 'September';
        $arr[10] = 'October';
        $arr[11] = 'November';
        $arr[12] = 'December';
        return $arr;
    }
}
