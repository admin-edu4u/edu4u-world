<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Http\Request;

class Result extends Model
{
	public $timestamps = false;
	public $primaryKey = "r_id";
	protected $table = "results";

	/**
	 * The attributes that are mass assignable.
	 *
	 * @var array
	 */
	protected $fillable = [
		'r_user_id', 'r_subject_id', 'r_type', 'r_grade', 'r_status', 'r_grade_type', 'r_cgpa', 'created_at', 'updated_at'
	];

	/***********************************************************************
	 * Get
	 ***********************************************************************/
	public static function getByUser($id)
	{
		if(isset($id)){
            return Result::where([
                ['r_user_id', '=', $id],
                ['r_status', '=', 1]
                ])
                ->get();
        }
        return null;
	}

	public static function getUserGrades($id)
	{
		if(isset($id)){
            return Result::where([
                ['r_user_id', '=', $id],
                ['r_status', '=', 1]
                ])
				->leftJoin('subjects as a', 'a.s_id', '=', 'r_subject_id')
				->leftJoin('subject_grades as b', 'b.sg_id', '=', 'r_grade')
                ->get();
        }
        return null;
	}
    /***********************************************************************
	 * Function
	 ***********************************************************************/
    
    /**
    * add
	*/
	public static function addResult(Request $r) {
		try {
			$dt = Gen::currTime();

			$add = Result::create([
				'r_user_id' => $r->user_id,
				'r_subject_id' => $r->subject_id,
				'r_grade' => $r->grade,
                'r_type' => $r->type,
				'r_status' => 1,
				'r_grade_type' => 1,
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

	public static function addCgpa(Request $r) {
		try {
			$dt = Gen::currTime();

			$add = Result::create([
				'r_user_id' => $r->user_id,
                'r_type' => $r->type,
				'r_cgpa' => $r->cgpa,
				'r_status' => 1,
				'r_grade_type' => 2,
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
    public static function statusArr($index = NULL)
	{
		$arr[1] = 'Active';
    	$arr[99] = 'Deleted';

		if (isset($index)) {
			return $arr[$index];
		}

		return $arr;
	}

    public static function typeArr($index = NULL)
	{
		$arr[1] = 'Final Results';
    	$arr[2] = 'Trial/Forecast Results';
    	$arr[3] = 'Partial Results';

		if (isset($index)) {
			return $arr[$index];
		}

		return $arr;
	}

	public static function gradeTypeArr($index = NULL)
	{
		$arr[1] = 'Grades';
    	$arr[2] = 'CGPA';

		if (isset($index)) {
			return $arr[$index];
		}

		return $arr;
	}
}
