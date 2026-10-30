<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Http\Request;

use Lang;

class CareerCategory extends Model
{

	public $timestamps = false;
    public $primaryKey = "cc_id";
    protected $table = "career_categories";

    protected $fillable = [
        'cc_title', 'cc_status', 'created_at', 'updated_at'
    ];

    /***********************************************************************
	 * Get
	 ***********************************************************************/
    public static function getById($id)
      {
        try{
          if(isset($id)){
            return CareerCategory::where('cc_id', '=', $id)->first();
          }
        }catch (\Exception $e) {
          \Log::error($e);
        }
        return null;
      }

	/***********************************************************************
	 * Add
	 ***********************************************************************/
    public static function addCategory(Request $r) {
        try {
            $dt = Gen::currTime();
        
            $add = CareerCategory::create([
                'cc_title' => $r->title,
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
}
