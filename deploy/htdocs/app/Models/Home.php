<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Http\Request;

use Lang;

class Home extends Model
{
    public $timestamps = false;
    public $primaryKey = "h_id";
    protected $table = "home";

    protected $fillable = [
        'h_img', 'created_at', 'updated_at'
    ];

    /***********************************************************************
	 * Edit / Update
	 ***********************************************************************/
    public static function updateBg(Request $r) {
        try {
            $dt = Gen::currTime();
    
            if ($r->has('h_id')) {
                $bg = Home::find($r->h_id);
    
                $Images = $r->file('bg', []);

                foreach ($Images as $img) {
                    $filename = "home-" . uniqid() . '.' . $img->getClientOriginalExtension();
                    $img->move(public_path('/images/home'), $filename);
                    
                    $BackgroundImages[] = $filename;
                }

                $bg->h_img = implode(',', $BackgroundImages);

                $bg->updated_at = $dt;
    
                $bg->save();
    
                return $bg;
            }
        } catch (\Exception $e) {
            \Log::error($e);
        }
    
        return null;
    }
}