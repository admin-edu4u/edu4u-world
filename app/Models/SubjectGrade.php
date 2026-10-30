<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Http\Request;
use \Illuminate\Support\Str;

class SubjectGrade extends Model
{
  public $timestamps = false;
	public $primaryKey = "sg_id";
	protected $table = "subject_grades";

    protected $fillable = [
		'sg_s_id', 'sg_grade', 'sg_status', 'created_at', 'updated_at'
	];

    /***********************************************************************
	 * Get
	 ***********************************************************************/
    public static function getAll() {
        try{
          return SubjectGrade::where('sg_status', '=', 1)
          ->orderBy('sg_id', 'asc')
          ->get();
        }catch (\Exception $e) {
          \Log::error($e);
        }
        return null;
    }

    public static function getGrades($id)
    {
        if (isset($id)) {
            return SubjectGrade::where([
                ['sg_status', '=', 1],
                ['sg_s_id', '=', $id],
            ])
                ->whereNotNull('sg_grade') // Add this line to exclude rows where sg_grade is null
                ->get();
        }
        return null;
    }


    public static function getGradeValue($id) 
    {
      try {
        if(isset($id)) {
          $sg = SubjectGrade::where('sg_id', '=', $id)->first();

          if(isset($sg)) {
            return $sg->sg_grade;
          }
        }
      } catch (\Exception $e) {
        \Log::error($e);
      }
      return null;
    }

    /***********************************************************************
	 * Add
	 ***********************************************************************/
  public static function addGrade(Request $r, $id) {
		try {
			$dt = Gen::currTime();

			$add = SubjectGrade::create([
				'sg_s_id' => $id,
				'sg_grade' => 'A',
				'sg_status' => 1,
				'created_at' => $dt,
				'updated_at' => $dt,
			]);

			if ($add) {
				return true;
			}

		} catch (\Exception $e) {
			\Log::error($e);
		}

		return false;
	}

    /***********************************************************************
	 * Edit Update
	 ***********************************************************************/

   public static function editGrade(Request $r)
   {
    try {
      $dt = Gen::currTime();

      // Iterate through the request data
      foreach ($r->all() as $key => $value) {
          if (Str::startsWith($key, 'grade')) {

              $sg_id = Str::after($key, 'grade');

              $sg = SubjectGrade::find($sg_id);

              if ($sg) {

                  $sg->sg_grade = $value;
                  $sg->updated_at = $dt;

                  $sg->save();
              }
          }
      }

      return true;
    } catch (\Exception $e) {
        \Log::error($e);
    }

    return false; // Return false in case of an error or failure
   }
   
}