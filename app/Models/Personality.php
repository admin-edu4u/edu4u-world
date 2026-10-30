<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Http\Request;

class Personality extends Model
{
	public $timestamps = false;
	public $primaryKey = "p_id";
	protected $table = "personalities";

	/**
	 * The attributes that are mass assignable.
	 *
	 * @var array
	 */
	protected $fillable = [
		'p_name', 'p_character', 'p_type', 'p_status', 'created_at', 'updated_at'
	];

	/***********************************************************************
	 * Get
	 ***********************************************************************/
   public static function getAllActive($type)
   {
     if(isset($type)){
       return Personality::where([
         ['p_type', '=', $type],
         ['p_status', '=', 1],
       ])
       ->get();
     }
     return null;
   }

   public static function getAll() {
	try{
	  return Personality::where('p_status', '=', 1)
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
            return Personality::where('p_id', '=', $id)->first();
          }
        }catch (\Exception $e) {
          \Log::error($e);
        }
        return null;
    }

  /***********************************************************************
	 * Add
	 ***********************************************************************/
  public static function addPersonality(Request $r) {
       try{
         $dt = Gen::currTime();

         $add = Personality::create([
           'p_name' => $r->title,
           'p_character' => implode(',', $r->character),
           'p_type' => $r->type,
           'p_status' => 1,
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

	public static function editPersonality(Request $r) {
        try {
            $dt = Gen::currTime();
    
            if ($r->has('p_id')) {
                $p = Personality::find($r->p_id);

                if ($p) {
                    $p->p_name = $r->title;
					$p->p_character = implode(',', $r->editcharacter);
                    $p->p_type = $r->type;
                    $p->updated_at = $dt;
    
                    // Save the changes
                    $p->save();
    
                    // Check if the job was updated successfully
                    if ($p) {
                        return true;
                    }
                }
            }
        } catch (\Exception $e) {
            \Log::error($e);
        }
    
        return false; // Return false in case of an error or failure
    } 

	/***********************************************************************
	 * Array
	 ***********************************************************************/

  public static function typeArr($index = NULL)
	{
		$arr[1] = 'Describe';
    $arr[2] = 'Interest';

		if (isset($index)) {
			return $arr[$index];
		}

		return $arr;
	}

  public static function statusArr($index = NULL)
	{
		$arr[1] = 'Active';
    $arr[99] = 'Deleted';

		if (isset($index)) {
			return $arr[$index];
		}

		return $arr;
	}

}
