<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Http\Request;

class QualificationLevel extends Model
{
	public $timestamps = false;
	public $primaryKey = "q_id";
	protected $table = "qualification_level";

	protected $fillable = [
		'q_level', 'q_status', 'created_at', 'updated_at'
	];

    /***********************************************************************
	 * Get
	 ***********************************************************************/
	public static function getAll() {
        try{
          return QualificationLevel::where('q_status', '=', 1)
          ->orderBy('q_id', 'asc')
          ->get();
        }catch (\Exception $e) {
          \Log::error($e);
        }
        return null;
    }

    public static function getById($id)
    {
      try{
        if(isset($id)){
          return QualificationLevel::where('q_id', '=', $id)->first();
        }
      }catch (\Exception $e) {
        \Log::error($e);
      }
      return null;
    }

    /***********************************************************************
	* Add
	***********************************************************************/
    public static function addQualificationLevel(Request $r) {
        try{
          $dt = Gen::currTime();
    
          $add = QualificationLevel::create([
            'q_level' => $r->qlevel,
            'q_status' => 1,
            'created_at' => $dt,
            'updated_at' => $dt,
          ]);
            return $add;
        }catch (\Exception $e) {
          \Log::error($e);
        }
        return null;
      }
}