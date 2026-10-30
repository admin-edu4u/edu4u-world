<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Http\Request;

use Lang;

class Characters extends Model
{
    public $timestamps = false;
    public $primaryKey = "c_id";
    protected $table = "characters";

    protected $fillable = [
        'c_name', 'c_aver_salary', 'c_desc', 'c_img_male', 'c_img_female', 'c_stream', 'c_status', 'created_at', 'updated_at'
    ];
    
    /***********************************************************************
	 * Get
	 ***********************************************************************/
    public static function getAll() {
        try{
          return Characters::where('c_status', '=', 1)
          ->orderBy('created_at', 'desc')
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
            return Characters::where([
                ['c_id', '=', $id],
                ['sc_status', '=', 1]
                ])
                ->leftJoin('sub_cats as a', 'a.sc_character', '=', 'c_id')
                ->first();
          }
        }catch (\Exception $e) {
          \Log::error($e);
        }
        return null;
      }

      public static function statusArr() {
        $arr = array();
        $arr[1] = 'Active';
        $arr[99] = 'Deactivate';
        return $arr;
    }

     /***********************************************************************
	 * Add
	 ***********************************************************************/
        public static function addCharacter(Request $r) {
            try{
                $dt = Gen::currTime();
                
                $imgMale = "character-male-" . uniqid() . '.jpg';
                $imgFemale = "character-female-" . uniqid() . '.jpg';
         
                $add = Characters::create([
                    'c_name' => $r->title,
                    'c_stream' => $r->coursestream,
                    'c_aver_salary' => $r->aversal,
                    'c_desc' => $r->desc,
                    'c_img_male' => $imgMale,
                    'c_img_female' => $imgFemale,
                    'c_status' => 1,
                    'created_at' => $dt,
                    'updated_at' => $dt,
                ]);
                
                if ($add) {
                    // Move and store male image
                    $r->img1->move(public_path('/images/characters'), $imgMale);
                    // Move and store female image
                    $r->img2->move(public_path('/images/characters'), $imgFemale);
        
                    return $add;
                }
    
            }catch (\Exception $e) {
                \Log::error($e);
            }
            return null;
        }
    
    /***********************************************************************
	 * Edit Update
	 ***********************************************************************/
    public static function editCharacter(Request $r) {
        try {
            $dt = Gen::currTime();
        
            if ($r->has('c_id')) {
                $c = Characters::find($r->c_id);
        
                if ($c) {
                    $c->c_name = $r->edittitle;
                    $c->c_stream = $r->editcoursestream;
                    $c->c_aver_salary = $r->aversal;
                    $c->c_desc = $r->editdesc;
                    $c->updated_at = $dt;

                    if ($r->hasFile('img3')) {
                        $imgMale = "character-male-" . uniqid() . '.jpg';
                        $c->c_img_male = $imgMale;
                        $r->img3->move(public_path('/images/characters'), $imgMale);
                    }

                    if ($r->hasFile('img4')) {
                        $imgFemale = "character-female-" . uniqid() . '.jpg';
                        $c->c_img_female = $imgFemale;
                        $r->img4->move(public_path('/images/characters'), $imgFemale);
                    }
                    
                    $c->save();

                    if ($c) {
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