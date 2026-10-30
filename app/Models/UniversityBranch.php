<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Http\Request;

class UniversityBranch extends Model
{
    public $timestamps = false;
	public $primaryKey = "b_id";
	protected $table = "branches";

    protected $fillable = [
		'b_uni_id', 'b_addr', 'b_state', 'b_country', 'b_status', 'created_at', 'updated_at'
	];

    /***********************************************************************
	 * Get
	 ***********************************************************************/
    public static function getAll() {
      try{
        return UniversityBranch::where('b_status', '=', 1)
        ->orderBy('b_id', 'desc')
        ->get();
      }catch (\Exception $e) {
        \Log::error($e);
      }
      return null;
    } 
   
   public static function getById($id)
    {
      try{
        if(isset($id)){
          return UniversityBranch::where([
            ['b_uni_id', '=', $id],
            ['b_status', '=', 1]
            ])
          ->leftJoin('states as a', 'a.state_id', '=', 'b_state')
          ->get();
        }
      }catch (\Exception $e) {
        \Log::error($e);
      }
      return null;
    }

    public static function getBranchById($id)
    {
      try{
        if(isset($id)){
          return UniversityBranch::where('b_id', '=', $id)
          ->leftJoin('states as a', 'a.state_id', '=', 'b_state')
          ->first();
        }
      }catch (\Exception $e) {
        \Log::error($e);
      }
      return null;
    }

    /***********************************************************************
	 * Add
	 ***********************************************************************/
    public static function addBranch(Request $r, $id){
        try {
            $dt = Gen::currTime();
    
            $branchIndex = 1;
            while ($r->has("baddress$branchIndex")) {

                $baddress = $r->input("baddress$branchIndex");
                $bstate = $r->input("bstate$branchIndex");
                $bcountry = $r->input("bcountry$branchIndex");
    
                UniversityBranch::create([
                    'b_uni_id' => $id,
                    'b_addr' => $baddress,
                    'b_state' => $bstate,
                    'b_country' => $bcountry,
                    'b_status' => 1,
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
    public static function editBranch(Request $r, $id){
        try {
            $deletedIds = explode(',', $r->input('deletedBranch'));
            UniversityBranch::whereIn('b_id', $deletedIds)->update(['b_status' => 99]);

            $dt = Gen::currTime();

            $uBranch = UniversityBranch::where('b_uni_id', $id)->where('b_status', 1)->get();

            $branchIndex = 1;
            foreach($uBranch as $ub) {
                
                $ub->b_addr = $r->input("baddress$branchIndex");
                $ub->b_state = $r->input("bstate$branchIndex");
                $ub->b_country = $r->input("bcountry$branchIndex");
                $ub->updated_at = $dt;

                $ub->save();

                $branchIndex ++;
            }

            $newBranchIndex = $branchIndex; 

            while ($r->has("baddress$newBranchIndex")) {

                $nbaddress = $r->input("baddress$newBranchIndex");
                $nbstate = $r->input("bstate$newBranchIndex");
                $nbcountry = $r->input("bcountry$newBranchIndex");

                UniversityBranch::create([
                    'b_uni_id' => $id,
                    'b_addr' => $nbaddress,
                    'b_state' => $nbstate,
                    'b_country' => $nbcountry,
                    'cg_status' => 1,
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

    public static function deleteBranch(Request $r, $id, $hb){
      try {
        if ($hb == 0) {
            UniversityBranch::where('b_uni_id', $id)->update(['b_status' => 99]);
          }
          
      } catch (\Exception $e) {
          \Log::error($e);
      }
    
      return null;
    }
}