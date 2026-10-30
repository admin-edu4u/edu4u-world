<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Http\Request;

use Lang;

class Enquiry extends Model
{
    public $timestamps = false;
    public $primaryKey = "e_id";
    protected $table = "enquiries";

    protected $fillable = [
        'e_name', 'e_email', 'e_phone_num', 'e_message', 'e_status', 'created_at', 'updated_at'
    ];

    /***********************************************************************
	 * Get
	 ***********************************************************************/
    public static function getById($id)
    {
        try {
            if(isset($id)) {
                return Enquiry::where('e_id', '=', $id)->first();
            }
        } catch (\Exception $e) {
          \Log::error($e);
        }
        return null;
    }

    public static function getAll() {
        try{
          return Enquiry::where('e_status', '=', 1)
          ->orderBy('created_at', 'desc')
          ->get();
        }catch (\Exception $e) {
          \Log::error($e);
        }
        return null;
      }

    /***********************************************************************
	 * Add
	 ***********************************************************************/
    public static function addEnquiry($r)
    {
        try {
            $dt = Gen::currTime();

            $add = Enquiry::create([
                'e_name' => $r->name,
                'e_email' => $r->email,
                'e_phone_num' => $r->contact_no,
                'e_message' => $r->message,
                'e_status' => 1,
                'created_at' => $dt,
                'updated_at' => $dt
            ]);

            if(isset($add)) {
                return $add;
            }
        } catch (\Exception $e) {
            \Log::error($e);
        }
        return null;
    }
}