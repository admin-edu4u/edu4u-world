<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class RegisterInterest extends Model
{
	public $timestamps = false;
	public $primaryKey = "ri_id";
	protected $table = "register_interests";

	/**
	 * The attributes that are mass assignable.
	 *
	 * @var array
	 */
	protected $fillable = [
		'ri_name', 'ri_email', 'ri_contact', 'ri_birthday', 'ri_school', 'ri_state', 'ri_course', 'ri_level', 'ri_uni', 'ri_remarks', 'ri_result', 'created_at', 'updated_at'
	];

	/***********************************************************************
	 * Process
	 ***********************************************************************/
	/**
	 * Add Address
	 */
	public static function addInterest($r)
	{
		$dt = Gen::currTime();

		\DB::beginTransaction();

		try {

				$add = RegisterInterest::create([
          'ri_name' => $r->name,
          'ri_email' => $r->email,
          'ri_contact' => $r->contact,
          'ri_birthday' => $r->birthday,
          'ri_school' => $r->school,
          'ri_state' => $r->state,
          'ri_course' => $r->course,
          'ri_level' => $r->level,
          'ri_uni' => $r->uni,
          'ri_remarks' => $r->remarks != null ? $r->remarks : null,
          'created_at' => $dt,
          'updated_at' => $dt
				]);

			if (isset($add)) {

				if($r->attachment != null) {
					$attachment = "attachment-" . uniqid() . '.jpg';

					$r->attachment->move(
						public_path('/images/result'),
						$attachment
					);

					$add->ri_result = $attachment;
					$add->save();
				}

				\DB::commit();
				return $add;
			}
		} catch (Exception $e) {
			\Log::error($e);
		}

		\DB::rollback();
		return null;
	}

  /***********************************************************************
   * Table
   ***********************************************************************/
   public static function getInterestList($request)
   {
     try{
       if(isset($request)) {
         $records_start = 0;
         if($request->records_start != null) $records_start = $request->records_start;

         $page_size = 25;
         if($request->page_size != null) $page_size = $request->page_size;

         return RegisterInterest::leftJoin('states as a', 'a.state_id', '=', 'ri_state')
         ->skip($records_start)
         ->limit($page_size)
         ->orderBy('register_interests.created_at', 'desc')
         ->get();
       }
     } catch (\Exception $e) {
       \Log::error($e);
     }

     return null;
   }

   public static function getTotalInterest($request)
   {
     try{
       if(isset($request)) {

         $u = RegisterInterest::leftJoin('states as a', 'a.state_id', '=', 'ri_state')
         ->selectRaw('COALESCE(COUNT(ri_id), 0) as total')
         ->first();

         if(isset($u)) return $u->total;
       }
     } catch (\Exception $e) {
       \Log::error($e);
     }

     return 0;
   }

	/***********************************************************************
	 * Array
	 ***********************************************************************/

  public static function levelArr($index = NULL)
	{
		$arr[1] = 'Advanced Diploma';
    $arr[2] = 'Certificate';
    $arr[3] = 'Degree';
    $arr[4] = 'Diploma';
    $arr[5] = 'Foundation/Pre-U';
    $arr[6] = 'Postgraduate - Diploma';
    $arr[7] = 'Postgraduate - Masters';
    $arr[8] = 'Postgraduate - PHD';
		$arr[9] = 'Professional Programmes';

		if (isset($index)) {
			return $arr[$index];
		}

		return $arr;
	}
}
