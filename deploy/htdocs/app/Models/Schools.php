<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Http\Request;

class Schools extends Model
{
	public $timestamps = false;
	public $primaryKey = "s_id";
	protected $table = "schools";
	protected $fillable = [
		's_name', 's_qualification', 's_status', 'created_at', 'updated_at'
	];

    /***********************************************************************
	* Get
    ***********************************************************************/
   public static function getAll() {
	try{
	  return Schools::where('s_status', '=', 1)
	  ->orderBy('created_at', 'desc')
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
            return Schools::where('s_id', '=', $id)->first();
          }
        }catch (\Exception $e) {
          \Log::error($e);
        }
        return null;
    }

    /***********************************************************************
	 * Add
	 ***********************************************************************/
  public static function addSchools(Request $r) {
    try{
      $dt = Gen::currTime();

      $add = Schools::create([
        's_name' => $r->schname,
        's_qualification' => implode(',', $r->qualification),
        's_status' => 1,
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
    * Edit Update
	***********************************************************************/

	public static function editSchools(Request $r) {
        try {
            $dt = Gen::currTime();
    
            if ($r->has('s_id')) {
                $s = Schools::find($r->s_id);

                if ($s) {
                    $s->s_name = $r->schname;
                    $s->s_qualification = implode(',', $r->qualification);
                    $s->updated_at = $dt;
                    $s->save();

                    if ($s) {
                        return true;
                    }
                }
            }
        } catch (\Exception $e) {
            \Log::error($e);
        }
    
        return false;
    } 

  public static function searchSchool($search)
  {
    try {
      if(isset($search)) {
        return Schools::where([
          ['s_name', 'like', '%' . $search . '%'],
          ['s_status', '=', 1]
          ])->get();
      }
    } catch (\Exception $e) {
      \Log::error($e);
    }
    return false;
  }

}