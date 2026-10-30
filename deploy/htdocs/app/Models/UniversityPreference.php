<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Http\Request;

class UniversityPreference extends Model
{
	public $timestamps = false;
	public $primaryKey = "up_id";
	protected $table = "university_preferences";

	/**
	 * The attributes that are mass assignable.
	 *
	 * @var array
	 */
	protected $fillable = [
		'up_user_id', 'up_pathway', 'up_start', 'up_location', 'up_state', 'up_country', 'up_type', 'up_is_fee_paid', 'up_uni_name', 'created_at', 'updated_at'
	];

	/***********************************************************************
	 * Get
	 ***********************************************************************/
	public static function getByUser($id)
	{
		if(isset($id)){
            return UniversityPreference::where('up_user_id', '=', $id)->first();
        }
        return null;
	}
    /***********************************************************************
	 * Function
	 ***********************************************************************/
    
    /**
    * add
	*/
	public static function addUniversityPreference(Request $r) {
		try {
			$dt = Gen::currTime();

			$add = UniversityPreference::create([
				'up_user_id' => $r->user_id,
				'up_pathway' => $r->pathway,
				'up_start' => $r->start,
                'up_location' => $r->location,
				'up_state' => $r->state,
				'up_country' => $r->country,
				'up_type' => $r->type,
				'up_is_fee_paid' => $r->fees_paid,
				'up_uni_name' => $r->uni_name,
				'created_at' => $dt,
				'updated_at' => $dt,
			]);

			if (isset($add)) {
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
    public static function pathwayArr($index = NULL)
	{
		$arr[1] = 'Foundation';
    	$arr[2] = 'International Pre-University';
    	$arr[3] = 'Diploma';
    	$arr[4] = 'Degree';
    	$arr[5] = 'Master';
    	$arr[6] = 'PHD';

		if (isset($index)) {
			return $arr[$index];
		}

		return $arr;
	}

    public static function startArr($index = NULL)
	{
		$arr[1] = 'Oct - Dec';
    	$arr[2] = 'Jan - Mar';
    	$arr[3] = 'Apr - Jun';
    	$arr[3] = 'Jul - Sep';
    	$arr[3] = 'Oct Onwards';

		if (isset($index)) {
			return $arr[$index];
		}

		return $arr;
	}

    public static function locationArr($index = NULL)
	{
		$arr[1] = 'Malaysia';
    	$arr[2] = 'Malaysia, but I plan to go overseas later';
    	$arr[3] = 'Overseas, for my entire studies';

		if (isset($index)) {
			return $arr[$index];
		}

		return $arr;
	}

    public static function typeArr($index = NULL)
	{
		$arr[1] = 'Private';
    	$arr[2] = 'Public';
    	$arr[3] = 'Both';

		if (isset($index)) {
			return $arr[$index];
		}

		return $arr;
	}

    public static function feeArr($index = NULL)
	{
		$arr[1] = 'Yes';
    	$arr[2] = 'No';

		if (isset($index)) {
			return $arr[$index];
		}

		return $arr;
	}
}
