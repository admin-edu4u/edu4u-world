<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Http\Request;

use Lang;

class CourseLevelDesc extends Model
{

	public $timestamps = false;
    public $primaryKey = "c_level_desc_id";
    protected $table = "course_level_desc";

    protected $fillable = [
        'c_id', 'c_level_id', 'c_level_desc', 'c_level_desc_status', 'created_at', 'updated_at'
    ];


    /***********************************************************************
     * Get
     ***********************************************************************/
     public static function getAll() {
       try{
         return CourseLevelDesc::where('c_level_desc_status', '=', 1)
         ->orderBy('c_level_id', 'asc')
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
          return CourseLevelDesc::where('c_id', '=', $id)->get();    
        }
      }catch (\Exception $e) {
        \Log::error($e);
      }
      return null;
    }

	/***********************************************************************
     * Add
     ***********************************************************************/
    public static function addDesc(Request $r) {
        try{
          $dt = Gen::currTime();

          $existed = CourseLevelDesc::where('c_id', $r->course)->where('c_level_id', $r->level)->first();
 
            if ($existed) {
                $existed->update([
                    'c_level_desc' => $r->desc,
                    'updated_at' => $dt,
                ]);
            } else {
                CourseLevelDesc::create([
                    'c_id' => $r->course,
                    'c_level_id' => $r->level,
                    'c_level_desc' => $r->desc,
                    'c_level_desc_status' => 1,
                    'created_at' => $dt,
                    'updated_at' => $dt,
                ]);
            }
 
        }catch (\Exception $e) {
          \Log::error($e);
        }
        return null;
      }
}
