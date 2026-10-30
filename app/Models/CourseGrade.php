<?php 

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Http\Request;


class CourseGrade extends Model
{
    public $timestamps = false;
    public $primaryKey = "cg_id";
	protected $table = "course_grades";

    protected $fillable = [
		'cg_c_id', 'cg_level', 'cg_min', 'cg_min_grades', 'cg_grades', 'cg_status', 'created_at', 'updated_at'
	];

    /***********************************************************************
	 * Get
	 ***********************************************************************/
    public static function getById($id)
    {
      try{
        if(isset($id)){
          return CourseGrade::where('cg_c_id', '=', $id)
                            ->leftJoin('subject_grades as a', 'a.sg_id', '=', 'cg_min_grades')
                            ->get();    
        }
      }catch (\Exception $e) {
        \Log::error($e);
      }
      return null;
    }

    public static function getAll() {
        try{
          return CourseGrade::where('cg_status', '=', 1)
          ->get();
        }catch (\Exception $e) {
          \Log::error($e);
        }
        return null;
    }
 
    /***********************************************************************
	 * Add
	 ***********************************************************************/
    public static function addCourseGrades(Request $r, $id) {

        try {
            $dt = Gen::currTime();
    
            $qualificationIndex = 1;
            while ($r->has("qlevel$qualificationIndex")) {
                // Get data for the current qualification set
                $qlevel = $r->input("qlevel$qualificationIndex");
                $minsubject = $r->input("minsubject$qualificationIndex");
                $mingrade = $r->input("mingrade$qualificationIndex");
                $subjects = $r->input("subject$qualificationIndex");
                $grades = $r->input("grade$qualificationIndex");
    
                // Initialize an array to store grades data
                $gradesData = [];
    
                // Loop through subjects and grades and prepare the data
                foreach ($subjects as $subjectIndex => $subject) {
                    // Check if subject and grade are not empty
                    if (!empty($subject) && isset($grades[$subjectIndex])) {
                        $gradesData[] = [
                            'subject' => $subject,
                            'grades' => $grades[$subjectIndex]
                        ];
                    }
                }
    
                // Encode the grades data into JSON format
                $encodedGrades = json_encode($gradesData);
    
                // Create CourseGrade record for the current qualification set
                CourseGrade::create([
                    'cg_c_id' => $id,
                    'cg_level' => $qlevel,
                    'cg_min' => $minsubject,
                    'cg_min_grades' => $mingrade,
                    'cg_grades' => $encodedGrades,
                    'cg_status' => 1,
                    'created_at' => $dt,
                    'updated_at' => $dt,
                ]);
    
                $qualificationIndex++;
            }
    
            return true;
        } catch (\Exception $e) {
            \Log::error($e);
            return false;
        }
    }

    /***********************************************************************
	 * Edit / Update
	 ***********************************************************************/
    public static function editCourseGrades(Request $r, $id){
        try {
            $dt = Gen::currTime();

            $courseGrades = CourseGrade::where('cg_c_id', $id)->get();

            $qualificationIndex = 1;
            foreach($courseGrades as $cg) {
                
                $cg->cg_level = $r->input("editqlevel$qualificationIndex");
                $cg->cg_min = $r->input("editminsubject$qualificationIndex");
                $cg->cg_min_grades = $r->input("editmingrade$qualificationIndex");

                $subjects = $r->input("editsubject$qualificationIndex");
                $grades = $r->input("editgrade$qualificationIndex");

                $subjectGradePairs = [];
                foreach ($subjects as $index => $subject) {
                    if (!empty($subject) && isset($grades[$index])) {
                        $subjectGradePairs[] = [
                            'subject' => $subject,
                            'grades' => $grades[$index]
                        ];
                    }
                }

                $encodedSubjectGradePairs = json_encode($subjectGradePairs);

                $cg->cg_grades = $encodedSubjectGradePairs;
                $cg->updated_at = $dt;

                $cg->save();

                $qualificationIndex ++;
            }

            // Handle new qualifications
            $newQualificationIndex = $qualificationIndex; // Start index for new qualifications

            while ($r->has("editqlevel$newQualificationIndex")) {
                // Process each new qualification
                $qlevel = $r->input("editqlevel$newQualificationIndex");
                $minsubject = $r->input("editminsubject$newQualificationIndex");
                $mingrade = $r->input("editmingrade$newQualificationIndex");
                $subjects = $r->input("editsubject$newQualificationIndex");
                $grades = $r->input("editgrade$newQualificationIndex");

                if ($subjects !== null && $grades !== null) {
                    // Initialize an array to store grades data
                    $gradesData = [];
            
                    // Loop through subjects and grades and prepare the data
                    foreach ($subjects as $subjectIndex => $subject) {
                        // Check if subject and grade are not empty
                        if (!empty($subject) && isset($grades[$subjectIndex])) {
                            $gradesData[] = [
                                'subject' => $subject,
                                'grades' => $grades[$subjectIndex]
                            ];
                        }
                    }
            
                    // Encode the grades data into JSON format
                    $encodedGrades = json_encode($gradesData);
                } else {
                    // If $subjects or $grades is null, set $encodedGrades to null
                    $encodedGrades = null;
                }

                CourseGrade::create([
                    'cg_c_id' => $id,
                    'cg_level' => $qlevel,
                    'cg_min' => $minsubject,
                    'cg_min_grades' => $mingrade,
                    'cg_grades' => $encodedGrades,
                    'cg_status' => 1,
                    'created_at' => $dt,
                    'updated_at' => $dt,
                ]);

                $newQualificationIndex++;
            }

            return true; 
        } catch (\Exception $e) {
            \Log::error($e);
        }
    
        return null;
    }
}

