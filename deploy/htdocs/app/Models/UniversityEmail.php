<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Http\Request;

class UniversityEmail extends Model
{
    public $timestamps = false;
	public $primaryKey = "ue_id";
	protected $table = "universities_email";

    protected $fillable = [
		'ue_u_id', 'ue_b_id', 'ue_u_email', 'ue_status', 'created_at', 'updated_at'
	];

    /***********************************************************************
	 * Get Email
	 ***********************************************************************/
    public static function getAll() {
        try{
          return UniversityEmail::where('ue_status', '=', 1)
          ->orderBy('ue_id', 'desc')
          ->get();
        }catch (\Exception $e) {
          \Log::error($e);
        }
        return null;
    }

    public static function getUniEmail($uni, $branch)
    {
        try{
            if(isset($uni, $branch)) {
                $e = UniversityEmail::where([
                    ['ue_u_id', '=', $uni],
                    ['ue_b_id', '=', $branch],
                    ['ue_status', '=', 1],
                ])
                ->first();

                if(isset($e)) return $e->ue_u_email;
            }
        } catch (\Exception $e) {
            \Log::error($e);
        }
        return null;
    }
    /***********************************************************************
	 * Add / Update
	 ***********************************************************************/
    public static function updateUniversityEmail(Request $r) {
        try {
            $dt = Gen::currTime();
    
            // Check if a record exists with the given conditions
            $existingRecord = UniversityEmail::where('ue_u_id', $r->uni)->where('ue_b_id', $r->unibranch)->where('ue_status', 1)->first();
    
            if ($existingRecord) {
                // Update the existing record
                $existingRecord->ue_u_email = $r->email;
                $existingRecord->updated_at = $dt;
                $existingRecord->save();
                return $existingRecord;
            } else {
                // Create a new record
                $add = UniversityEmail::create([
                    'ue_u_id' => $r->uni,
                    'ue_b_id' => $r->unibranch,
                    'ue_u_email' => $r->email,
                    'ue_status' => 1,
                    'created_at' => $dt,
                    'updated_at' => $dt,
                ]);
                return $add;
            }
        } catch (\Exception $e) {
            \Log::error($e);
        }
        return null;
     }

}