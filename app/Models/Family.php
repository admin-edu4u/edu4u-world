<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Http\Request;

class Family extends Model
{
	public $timestamps = false;
	public $primaryKey = "f_id";
	protected $table = "families";

	/**
	 * The attributes that are mass assignable.
	 *
	 * @var array
	 */
	protected $fillable = [
		'f_user_id', 'f_is_first_sibiling', 'f_is_parent_teacher', 'f_income', 'f_budget', 'f_is_bumiputera', 'created_at', 'updated_at'
	];

    /***********************************************************************
	 * Get
	 ***********************************************************************/
    public static function getByUserId($id)
    {
        if(isset($id)) {
            return Family::where('f_user_id', '=', $id)->first();
        }
        return null;
    }
    
	/***********************************************************************
	 * Function
	 ***********************************************************************/
    /**
	* add
     */
	public static function addFamily(Request $r) {
		try {
			$dt = Gen::currTime();

			$add = Family::create([
				'f_user_id' => $r->user_id,
				'f_is_first_sibiling' => $r->sibiling,
				'f_is_parent_teacher' => $r->parent,
				'f_income' => $r->income,
				'f_budget' => $r->budget,
                'f_is_bumiputera' => $r->bumiputera,
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
    public static function sibilingArr($index = NULL)
	{
		$arr[1] = 'Yes';
    	$arr[2] = 'No';

		if (isset($index)) {
			return $arr[$index];
		}

		return $arr;
	}

    public static function parentArr($index = NULL)
	{
		$arr[1] = 'Yes';
    	$arr[2] = 'No';

		if (isset($index)) {
			return $arr[$index];
		}

		return $arr;
	}

    public static function incomeArr($index = NULL)
	{
		// $arr[1] = 'Bantuan Prihatin Rakyat (BPR) Receipient';
    	$arr[1] = 'Below MYR 8,000';
    	$arr[2] = 'Below MYR 8,000';
    	$arr[3] = 'Above MYR 8,000';

		if (isset($index)) {
			return $arr[$index];
		}

		return $arr;
	}

    public static function budgetArr($index = NULL)
	{
		$arr[1] = 'Below MYR 20,000';
    	$arr[2] = 'MYR 20,001 - MYR 40,000';
    	$arr[3] = 'MYR 40,001 - MYR 60,000';
    	$arr[4] = 'MYR 60,001 - MYR 80,000';
    	$arr[5] = 'MYR 80,001 - MYR 100,000';
    	$arr[6] = 'Above MYR 100,000';

		if (isset($index)) {
			return $arr[$index];
		}

		return $arr;
	}

    public static function bumiArr($index = NULL)
	{
		$arr[1] = 'Yes';
    	$arr[2] = 'No';

		if (isset($index)) {
			return $arr[$index];
		}

		return $arr;
	}
}
