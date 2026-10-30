<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Http\Request;

class BlogCat extends Model
{
    public $timestamps = false;
	public $primaryKey = "bc_id";
	protected $table = "blog_cats";

	protected $fillable = [
		'bc_name', 'bc_status', 'created_at', 'updated_at'
	];

    /***********************************************************************
	 * Get
	 ***********************************************************************/
    public static function getAll() {
        try{
          return BlogCat::where('bc_status', '=', 1)
          ->orderBy('bc_id', 'desc')
          ->get();
        }catch (\Exception $e) {
          \Log::error($e);
        }
        return null;
        }

    /***********************************************************************
	 * Add
	 ***********************************************************************/
	public static function addBlogCat(Request $r) {
		try{
			$dt = Gen::currTime();
	 
			$add = BlogCat::create([
				'bc_name' => $r->title,
				'bc_status' => 1,
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