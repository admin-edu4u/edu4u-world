<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Http\Request;

class SubCat extends Model
{
	public $timestamps = false;
	public $primaryKey = "sc_id";
	protected $table = "sub_cats";

	/**
	 * The attributes that are mass assignable.
	 *
	 * @var array
	 */
	protected $fillable = [
		'sc_name', 'sc_status', 'sc_main', 'sc_character', 'created_at', 'updated_at'
	];

	/***********************************************************************
	 * Get
	 ***********************************************************************/
   public static function getAllActive($id)
   {
     if(isset($id)){
       return SubCat::where([
         ['sc_status', '=', 1],
         ['sc_main', '=', $id]
         ])
         ->get();
     }
     return null;
   }

   public static function getAll() {
	try{
	  return SubCat::where('sc_status', '=', 1)
	  ->orderBy('sc_id', 'desc')
	  ->get();
	}catch (\Exception $e) {
	  \Log::error($e);
	}
	return null;
	}

	public static function getNameById($id)
	{
		try {
			if(isset($id)) {
				$sub = SubCat::where('sc_id', '=', $id)->first();

				if(isset($sub)) {
					return $sub->sc_name;
				}
			}
		} catch (\Exception $e) {
			\Log::error($e);
		}
		return null;
	}

	public static function getCatByCharacter($id)
	{
		try {
			if(isset($id)) {
				return SubCat::where('sc_character', '=', $id)->first();
			}
		} catch (\Exception $e) {
			\Log::error($e);
		}
		return null;
	}

	public static function getById($id)
    {
    	try{
          if(isset($id)){
            return SubCat::where('sc_id', '=', $id)->first();
          }
        }catch (\Exception $e) {
          \Log::error($e);
        }
        return null;
    }

	public static function getRate($c_id, $cat_id)
	{
		try{
			return SubCat::where([
				['sc_character', '=', $c_id],
				['sc_id', '=', $cat_id],
			])
			->first();
		} catch (\Exception $e) {
          \Log::error($e);
        }
        return null;
	}

	public static function getMainCat($id)
	{
		try {
			if(isset($id)) {
				return SubCat::where('sc_main', '=', $id)->get();
			}
		} catch (\Exception $e) {
			\Log::error($e);
		}
		return null;
	}

	  /***********************************************************************
	 * Add
	 ***********************************************************************/
	public static function addSubCat(Request $r) {
		try{
			$dt = Gen::currTime();
	 
			$add = SubCat::create([
				'sc_main' => $r->c_id,
				'sc_name' => $r->subcat,
				'c_status' => 1,
				'sc_character' =>$r->subcatchar,
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
    public static function editSubCat(Request $r) {
        try {
            $dt = Gen::currTime();
        
            if ($r->has('sc_id')) {
                $sc = SubCat::find($r->sc_id);
        
                if ($sc) {
                    $sc->sc_name = $r->editsubcat;
                    $sc->sc_character = $r->editsubcatchar;
                    $sc->updated_at = $dt;
                    
                    $sc->save();

                    if ($sc) {
                        return true;
                    }
                }
            }            
        } catch (\Exception $e) {
            \Log::error($e);
        }
        return null;        
    }

	/***********************************************************************
	 * Array
	 ***********************************************************************/
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
