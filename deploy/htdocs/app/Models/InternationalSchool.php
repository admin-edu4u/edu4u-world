<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Http\Request;

class InternationalSchool extends Model
{
    public $timestamps = false;
	public $primaryKey = "int_id";
	protected $table = "international_school";

    protected $fillable = [
		'int_url_code', 'int_logo', 'int_img', 'int_name', 'int_level', 'int_student', 'int_staff', 'int_year', 'int_addr', 'int_state', 'int_country', 'int_has_branch', 'int_hours', 'int_desc', 'int_status', 'created_at', 'updated_at'
	];

     /***********************************************************************
	 * Get
	 ***********************************************************************/
    public static function getAll() {
        try{
          return InternationalSchool::where('int_status', '=', 1)
          ->orderBy('int_id', 'desc')
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
          return InternationalSchool::where('int_id', '=', $id)->first();
        }
      }catch (\Exception $e) {
        \Log::error($e);
      }
      return null;
    }

    public static function getByUrl($id)
    {
      try{
        if(isset($id)){
          return InternationalSchool::where('int_url_code', '=', $id)->first();
        }
      }catch (\Exception $e) {
        \Log::error($e);
      }
      return null;
    }

    public static function getSchoolList($r)
    {
      try {
        if(isset($r)) {
          \DB::statement("SET SQL_MODE=''");
          $is = InternationalSchool::leftJoin('international_branches as b', 'b.int_id', '=', 'international_school.int_id')
          ->where([
            ['int_status', '=', 1],
            [function ($a) use ($r) {

              if($r->country != null) {
                $a->where('int_country', '=', $r->country)
                ->orWhere('int_b_country', '=', $r->country);
              }

              if($r->state != null) {
                $a->where('int_state', '=', $r->state)
                ->orWhere('int_b_state', '=', $r->state);
              }

              if($r->search != null) {
                $a->where('int_name', 'LIKE', '%' . $r->search . '%');
              }
            }]
          ])
          ->groupBy('international_school.int_id')
          ->selectRaw('international_school.int_id as int_id,int_level,int_b_country,int_b_state,int_b_country,int_state,int_country,int_url_code,int_name,int_logo,int_has_branch');

          if($r->sort != null) {
            if($r->sort == 1) {
              $is->orderBy('int_name', 'asc');
            } else{
              $is->orderBy('int_name', 'desc');
            }
          }
          return $is->get();
        }
      } catch (\Exception $e) {
        \Log::error($e);
      }
      return null;
    }

    /***********************************************************************
	 * Add
	 ***********************************************************************/
    public static function addInternationalSchool(Request $r) {
        try{
          $dt = Gen::currTime();

         $logo = "logo-" . uniqid() . '.jpg';
 
         $r->file('logo')->move(
             public_path('/images/internationalschool/logo'), $logo
         );
 
         $url = $r->k12name;
         $url = preg_replace('/[^a-zA-Z0-9_ -]/s', '', $url);
         $url = str_replace(' ', '_', $url) . '_' . uniqid();
 
          $add = InternationalSchool::create([
            'int_url_code' => $url,
            'int_name' => $r->k12name,
            'int_level' => implode(',', $r->k12level),
            'int_student' => $r->student,
            'int_staff' => $r->staff,
            'int_year' => $r->year,
            'int_addr' => $r->address,
            'int_state' => $r->state,
            'int_country' => $r->country,
            'int_hours' => $r->hours1 . '~' . $r->hours2,
            'int_has_branch' => $r->hasbranch,
            'int_desc' => $r->desc,
            'int_status' => 1,
            'created_at' => $dt,
            'updated_at' => $dt,
          ]);
 
          if (isset($add)) {
             $images = [];

             if (isset($r->img)) {
                 foreach ($r->img as $image) {
                     $img = "k12-" . uniqid() . '.jpg';
                     $images[] = $img;
 
                     $image->move(
                         public_path('/images/internationalschool/images'), $img);
                 }
             }
             $add->update(['int_img' => implode(',', $images), 'int_logo' => $logo]);

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
    public static function editInternationalSchool(Request $r) {
        try {
            $dt = Gen::currTime();
    
            if ($r->has('int_id')) {
                $k = InternationalSchool::find($r->int_id);

                if ($r->hasFile('logo')) {
                    $logo = "logo-" . uniqid() . '.jpg';
    
                    $r->file('logo')->move(
                        public_path('/images/internationalschool/logo'), $logo
                    );
    
                    $k->int_logo = $logo;
                }

                if ($r->hasFile('img')) {
                    $images = [];
    
                    foreach ($r->file('img') as $image) {
                        $img = "k12-" . uniqid() . '.jpg';
                        $images[] = $img;
    
                        $image->move(
                            public_path('/images/internationalschool/images'), $img
                        );
                    }

                    $existingImages = explode(',', $k->int_img);
                    $allImages = array_merge($existingImages, $images);
                    $k->int_img = implode(',', $allImages);
                    // $u->u_img = implode(',', $images);
                }

                $k->int_name = $r->k12name;
                $k->int_level = implode(',', $r->k12level);
                $k->int_student = $r->student;
                $k->int_staff = $r->staff;
                $k->int_year = $r->year;
                $k->int_addr = $r->address;
                $k->int_state = $r->state;
                $k->int_country = $r->country;
                $k->int_has_branch = $r->hasbranch;
                $k->int_hours = $r->hours1 . '~' . $r->hours2;
                $k->updated_at = $dt;

                if ($r->has('desc') && $r->desc !== null) {
                    $k->int_desc = $r->desc;
                }
    
                // Save the changes
                $k->save();
    
                return $k;
            }
        } catch (\Exception $e) {
            \Log::error($e);
        }
    
        return null;
    }
        
    /***********************************************************************
	 * Arr
	 ***********************************************************************/
    public static function statusArr() {
         $arr = array();
         $arr[1] = 'Active';
         $arr[99] = 'Deactivate';
         return $arr;
    }

    public static function levelArr() {
          $arr = array();
          $arr[1] = 'Year 7';
          $arr[2] = 'Year 8';
          $arr[3] = 'Year 9';
          $arr[4] = 'Year 10';
          $arr[5] = 'Year 11';
          $arr[6] = 'Year 12';
          return $arr;
    }
}