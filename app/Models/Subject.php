<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Http\Request;

class Subject extends Model
{
	public $timestamps = false;
	public $primaryKey = "s_id";
	protected $table = "subjects";

	/**
	 * The attributes that are mass assignable.
	 *
	 * @var array
	 */
	protected $fillable = [
		's_level_id', 's_subject_cat_id', 's_name', 's_is_preselect', 's_status', 'created_at', 'updated_at'
	];

	/***********************************************************************
	 * Get
	 ***********************************************************************/
	public static function getAllActive()
	{
		$subjects = Subject::where('s_status', '=', 1)->get();

		// Add level name to the result set
		$subjects = $subjects->map(function ($subject) {
			$subject['level_name'] = Subject::levelArr($subject->s_level_id);
			return $subject;
		});

		return $subjects;
	}

	public static function getByLevelSubject($level, $sub)
	{
		try{
			if(isset($level, $sub)) {
				return Subject::where([
					['s_status', '=', 1],
					['s_level_id', '=', $level],
					['s_id', '=', $sub]
				])
				->first();
			}
		} catch (\Exception $e) {
            \Log::error($e);
        }

        return false;
	}

	public static function getSubjects($id)
   {
     if(isset($id)){
       return Subject::where([
         ['s_status', '=', 1],
         ['s_level_id', '=', $id]
         ])
         ->get();
     }
     return null;
   }

   public static function getAllActiveByLevel($level)
   {
	try{
		if(isset($level)) {
			return Subject::where([
				['s_level_id', '=', $level],
				['s_status', '=', 1]
			])
			->leftJoin('subject_categories as a', 'a.sc_id', '=', 's_subject_cat_id')
			->get();
		}
	} catch (\Exception $e) {
		\Log::error($e);
	}
	return false;

   }

   public static function getSubjectName($id)
   {
	try {
		if(isset($id)) {
			$s = Subject::where('s_id', '=', $id)->first();

			if(isset($s)) {
				return $s->s_name;
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

  public static function levelArr($index = NULL)
	{
		$arr[1] = 'SPM';
    	$arr[2] = 'O-Level';
    	$arr[3] = 'STPM';
    	$arr[4] = 'UEC';
    	$arr[5] = 'Foundation';
    	$arr[6] = 'Diploma';
    	$arr[7] = 'A-Level';
    	$arr[8] = 'IB Diploma';
    	$arr[9] = 'CPU/CIMP';
    	$arr[10] = 'Ausmat/SACEi/Atar';
    	$arr[11] = 'Others';

		if (isset($index)) {
			// return $arr[$index];
		}

		return $arr;
	}

	/**
		 * add new
		 */
	public static function addSubject(Request $r) {
		try {
			$dt = Gen::currTime();

			$add = Subject::create([
				's_level_id' => $r->subjectId,
				's_subject_cat_id' => $r->subjectCategory,
				's_name' => $r->subjectTitle,
				's_is_preselect' => $r->preselected,
				's_status' => 1,
				'created_at' => $dt,
				'updated_at' => $dt,
			]);

			// Check if the category was added successfully
			if ($add) {
				return true;
			}

		} catch (\Exception $e) {
			\Log::error($e);
		}

		return false; // Return false in case of an error or failure
	}

	public static function editSubject(Request $r) {
        try {
            $dt = Gen::currTime();

            // Check if c_id is provided for updating
            if ($r->has('s_id')) {
                // Update the existing job
                $sub = Subject::find($r->s_id);

                // Check if the job is found
                if ($sub) {
                    $sub->s_name = $r->editSubjectTitle;
                    $sub->s_subject_cat_id = $r->editSubjectCategory;
                    $sub->s_is_preselect = $r->editPreselected;
                    $sub->updated_at = $dt;

                    // Save the changes
                    $sub->save();

                    // Check if the job was updated successfully
                    if ($sub) {
                        return true;
                    }
                }
            }
        } catch (\Exception $e) {
            \Log::error($e);
        }

        return false; // Return false in case of an error or failure
    }
}
