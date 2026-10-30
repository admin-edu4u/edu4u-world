<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Http\Request;

class Category extends Model
{
	public $timestamps = false;
	public $primaryKey = "c_id";
	protected $table = "categories";

	/**
	 * The attributes that are mass assignable.
	 *
	 * @var array
	 */
	protected $fillable = [
		'c_name', 'c_img', 'c_status', 'created_at', 'updated_at'
	];

	/***********************************************************************
	 * Get
	 ***********************************************************************/
   public static function getAllActive()
   {
       return Category::where('c_status', '=', 1)->get();
   }

   public static function getById($id)
    {
		try{
		if(isset($id)){
			return Category::where('c_id', '=', $id)->first();
		}
		}catch (\Exception $e) {
		\Log::error($e);
		}
        return null;
    }

   /***********************************************************************
	 * Add
	 ***********************************************************************/
	public static function addCategory(Request $r) {
		try{
			$dt = Gen::currTime();
			
			$img = "maincat-" . uniqid() . '.jpg';
	 
			$add = Category::create([
				'c_name' => $r->maincat,
				'c_img' => $img,
				'c_status' => 1,
				'created_at' => $dt,
				'updated_at' => $dt,
			]);
			
			if ($add) {

				$r->img->move(public_path('/images/category'), $img);
	
				return $add;
			}

		}catch (\Exception $e) {
			\Log::error($e);
		}
		return null;
	}

	/***********************************************************************
	 * Edit Update
	 ***********************************************************************/
    public static function editCategory(Request $r) {
        try {
            $dt = Gen::currTime();
        
            if ($r->has('c_id')) {
                $c = Category::find($r->c_id);
        
                if ($c) {
                    $c->c_name = $r->editmaincat;
                    $c->updated_at = $dt;

                    if ($r->hasFile('img2')) {
                        $img = "maincat-" . uniqid() . '.jpg';
                        $c->c_img = $img;
                        $r->img2->move(public_path('/images/category'), $img);
                    }
                    
                    $c->save();

                    if ($c) {
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
