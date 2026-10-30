<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Http\Request;

use Lang;

class Career extends Model
{

	public $timestamps = false;
    public $primaryKey = "c_id";
    protected $table = "careers";

    protected $fillable = [
        'c_title', 'c_state', 'c_type', 'c_salary_min', 'c_salary_max', 'c_category', 'c_status', 'c_url', 'created_at', 'updated_at'
    ];

		/**
		 * add new
		 */
    public static function addJob(Request $r) {
        try {
            $dt = Gen::currTime();
        
            $add = Career::create([
                'c_title' => $r->title,
                'c_state' => $r->state,
                'c_type' => $r->type,
                'c_salary_min' => $r->minsalary,
                'c_salary_max' => $r->maxsalary,
                'c_category' => $r->category,
                'c_url' => $r->jobUrl,
                'cc_status' => 1,
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
        

     public static function statusArr() {
         $arr = array();
         $arr[1] = 'Active';
         $arr[99] = 'Deactivate';
         return $arr;
     }

     /**
     * getAll
     */
    public static function getAll() {
        try{
          return Career::where('c_status', '=', 1)
          ->orderBy('created_at', 'desc')
          ->get();
        }catch (\Exception $e) {
          \Log::error($e);
        }
        return null;
      }

      public static function editJob(Request $r) {
        try {
            $dt = Gen::currTime();
    
            // Check if c_id is provided for updating
            if ($r->has('c_id')) {
                // Update the existing job
                $job = Career::find($r->c_id);
    
                // Check if the job is found
                if ($job) {
                    $job->c_title = $r->title;
                    $job->c_state = $r->state;
                    $job->c_type = $r->type;
                    $job->c_salary_min = $r->minsalary;
                    $job->c_salary_max = $r->maxsalary;
                    $job->c_category = $r->category;
                    $job->c_url = $r->jobUrl;
                    $job->updated_at = $dt;
    
                    // Save the changes
                    $job->save();
    
                    // Check if the job was updated successfully
                    if ($job) {
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