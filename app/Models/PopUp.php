<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Http\Request;

use Lang;

class PopUp extends Model
{
    public $timestamps = false;
    public $primaryKey = "pu_id";
    protected $table = "pop_up";

    protected $fillable = [
        'pu_img', 'pu_url', 'created_at', 'updated_at'
    ];

    /***********************************************************************
	 * Edit / Update
	 ***********************************************************************/
    public static function updatePopUp(Request $r) {
        try {
            $dt = Gen::currTime();
        
            if ($r->has('pu_id')) {
                $pu = PopUp::find($r->pu_id);
        
                if ($pu) {
                    $pu->pu_url = 'https://' . $r->pu_url;

                    $pu->updated_at = $dt;

                    if ($r->hasFile('img')) {
                        $img = "popup" . uniqid() . '.jpg';
                        $pu->pu_img = $img;
                        $r->img->move(public_path('/images/home'), $img);
                    }
                    
                    $pu->save();

                    if ($pu) {
                        return true;
                    }
                }
            }            
        } catch (\Exception $e) {
            \Log::error($e);
        }
    
        return null;
    }
}