<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Http\Request;

class InternationalSchoolBranch extends Model
{
    public $timestamps = false;
	public $primaryKey = "int_b_id";
	protected $table = "international_branches";

    protected $fillable = [
		'int_id', 'int_b_addr', 'int_b_state', 'int_b_country', 'int_b_status', 'created_at', 'updated_at'
	];

    /***********************************************************************
	 * Get
	 ***********************************************************************/
    public static function getById($id)
    {
      try{
        if(isset($id)){
          return InternationalSchoolBranch::where('int_id', '=', $id)
          ->leftJoin('states as a', 'a.state_id', '=', 'int_b_state')
          ->get();
        }
      }catch (\Exception $e) {
        \Log::error($e);
      }
      return null;
    }

    /***********************************************************************
	 * Add
	 ***********************************************************************/
    public static function addInternationalSchoolBranch(Request $r, $id){
        try {
            $dt = Gen::currTime();
    
            $branchIndex = 1;
            while ($r->has("baddress$branchIndex")) {

                $baddress = $r->input("baddress$branchIndex");
                $bstate = $r->input("bstate$branchIndex");
                $bcountry = $r->input("bcountry$branchIndex");
    
                InternationalSchoolBranch::create([
                    'int_id' => $id,
                    'int_b_addr' => $baddress,
                    'int_b_state' => $bstate,
                    'int_b_country' => $bcountry,
                    'int_b_status' => 1,
                    'created_at' => $dt,
                    'updated_at' => $dt,
                ]);
    
                $branchIndex++;
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
    public static function editInternationalSchoolBranch(Request $r, $id){
        try {
            $deletedIds = explode(',', $r->input('deletedBranch'));
            InternationalSchoolBranch::whereIn('int_b_id', $deletedIds)->update(['int_b_status' => 99]);

            $dt = Gen::currTime();

            $k12Branch = InternationalSchoolBranch::where('int_id', $id)->where('int_b_status', 1)->get();

            $branchIndex = 1;
            foreach($k12Branch as $kb) {
                
                $kb->int_b_addr = $r->input("baddress$branchIndex");
                $kb->int_b_state = $r->input("bstate$branchIndex");
                $kb->int_b_country = $r->input("bcountry$branchIndex");
                $kb->updated_at = $dt;

                $kb->save();

                $branchIndex ++;
            }

            $newBranchIndex = $branchIndex; 

            while ($r->has("baddress$newBranchIndex")) {

                $nbaddress = $r->input("baddress$newBranchIndex");
                $nbstate = $r->input("bstate$newBranchIndex");
                $nbcountry = $r->input("bcountry$newBranchIndex");

                InternationalSchoolBranch::create([
                    'int_id' => $id,
                    'int_b_addr' => $nbaddress,
                    'int_b_state' => $nbstate,
                    'int_b_country' => $nbcountry,
                    'int_b_status' => 1,
                    'created_at' => $dt,
                    'updated_at' => $dt,
                ]);

                $newBranchIndex++;
            }

            return true; 
        } catch (\Exception $e) {
            \Log::error($e);
        }
    
        return null;
    }

}