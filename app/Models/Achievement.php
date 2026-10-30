<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Http\Request;

class Achievement extends Model
{
	public $timestamps = false;
	public $primaryKey = "a_id";
	protected $table = "achievements";

	/**
	 * The attributes that are mass assignable.
	 *
	 * @var array
	 */
	protected $fillable = [
		'a_user_id', 'a_type', 'a_is_represent', 'a_level', 'a_represent', 'a_role', 'created_at', 'updated_at'
	];

    /***********************************************************************
	 * Get
	 ***********************************************************************/
    public static function getByUserId($id)
    {
        if(isset($id)) {
            return Achievement::where('a_user_id', '=', $id)->get();
        }
        return null;
    }
    
	/***********************************************************************
	 * Function
	 ***********************************************************************/
    /**
	* add
     */
	public static function addAchievement(Request $r) {
		try {
			$dt = Gen::currTime();

			$add = Achievement::create([
				'a_user_id' => $r->user_id,
				'a_type' => $r->type,
				'a_is_represent' => $r->is_represent,
				'a_level' => $r->is_represent == 1 ? $r->level : 0,
				'a_represent' => $r->is_represent == 1 ? $r->represent : null,
                'a_role' => $r->is_leadership == 1 && $r->type == 3 ? $r->role : 0,
				'created_at' => $dt,
				'updated_at' => $dt,
			]);

			if ($add) {
				return $add;
			}

		} catch (\Exception $e) {
			\Log::error($e);
		}

		return false;
	}

	/***********************************************************************
	 * Array
	 ***********************************************************************/
    public static function typeArr($index = NULL)
	{
		$arr[1] = 'Sports';
    	$arr[2] = 'Talent';
    	$arr[3] = 'Leadership';

		if (isset($index)) {
			return $arr[$index];
		}

		return $arr;
	}

    public static function levelArr($index = NULL)
	{
		$arr[1] = 'School';
    	$arr[2] = 'District';
    	$arr[3] = 'State';
    	$arr[4] = 'National';
    	$arr[5] = 'International';

		if (isset($index)) {
			return $arr[$index];
		}

		return $arr;
	}

    public static function representArr($index = NULL)
	{
		$arr[1] = 'Yes';
    	$arr[2] = 'No';

		if (isset($index)) {
			return $arr[$index];
		}

		return $arr;
	}

    public static function rollArr($index = NULL)
	{
		$arr[1] = 'President';
    	$arr[2] = 'Vice President';
    	$arr[3] = 'Secretary';
    	$arr[4] = 'Treasurer';
    	$arr[5] = 'Director/Team Lead';
    	$arr[6] = 'Others';

		if (isset($index)) {
			return $arr[$index];
		}

		return $arr;
	}
}
