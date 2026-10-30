<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Http\Request;

use Lang;

class Academic extends Model
{

	public $timestamps = false;
    public $primaryKey = "a_id";
    protected $table = "academics";

    protected $fillable = [
        'a_user_id', 'is_malaysian', 'a_level', 'a_qualification', 'a_school', 'a_transcript', 'created_at', 'updated_at'
    ];

    /***********************************************************************
     * Function
     ***********************************************************************/
    public static function addAcademic(Request $r) {
        try {
            $dt = Gen::currTime();

            $add = Academic::create([
                'a_user_id' => $r->user_id,
                'is_malaysian' => $r->is_malaysian,
                'a_level' => $r->level,
                'a_qualification' => $r->qualification,
                'a_school' => $r->school,
                'created_at' => $dt,
                'updated_at' => $dt,
            ]);

            if ($add) {
              if($r->level == 3 || $r->qualification == 11) {
                $attachment = "attachment-" . uniqid() . '.jpg';

                $r->transcript->move(
      						public_path('/images/result'),
      						$attachment
      					);

      					$add->a_transcript = $attachment;
      					$add->save();

              }
                return $add;
            }

        } catch (\Exception $e) {
            \Log::error($e);
        }

        return false; // Return false in case of an error or failure
    }

    /***********************************************************************
     * Get
     ***********************************************************************/
    public static function getByUserId($id)
    {
        try{
            if(isset($id)){
                return Academic::where('a_user_id', '=', $id)->first();
            }
        } catch (\Exception $e) {
            \Log::error($e);
        }

        return false; 
    }
    /***********************************************************************
     * Array
     ***********************************************************************/

     public static function malaysiansArr() {
         $arr = array();
         $arr[1] = 'Malaysian';
         $arr[2] = 'Non-Malaysian';
         return $arr;
     }

     public static function levelArr() {
         $arr = array();
         $arr[1] = 'Secondary';
         $arr[2] = 'Pre-University';
         $arr[3] = 'Others';
         return $arr;
     }
}
