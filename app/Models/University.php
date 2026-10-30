<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Http\Request;

class University extends Model
{
    public $timestamps = false;
	public $primaryKey = "u_id";
	protected $table = "universities";

    protected $fillable = [
		'u_logo', 'u_img', 'u_name', 'u_has_rank', 'u_ranking', 'u_student', 'u_staff', 'u_year', 'u_desc', 'u_addr', 'u_state', 'u_country', 'u_hours', 'u_status', 'u_url_code', 'u_has_branch', 'u_type', 'created_at', 'updated_at'
	];

    /***********************************************************************
	 * Get
	 ***********************************************************************/
    public static function getAll() {
        try{
          return University::where('u_status', '=', 1)
          ->orderBy('u_id', 'desc')
          ->get();
        }catch (\Exception $e) {
          \Log::error($e);
        }
        return null;
    }

    public static function admingetAll() {
      try {
        return University::whereIn('u_status', [1, 98])
            ->orderBy('u_id', 'desc')
            ->get();
      } catch (\Exception $e) {
          \Log::error($e);
      }
      return null;
  }

    public static function getById($id)
    {
      try{
        if(isset($id)){
          return University::where('u_id', '=', $id)->first();
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
          return University::where('u_url_code', '=', $id)->first();
        }
      }catch (\Exception $e) {
        \Log::error($e);
      }
      return null;
    }

    public static function getUniWithBranch()
    {
      try {
        return University::where([
                ['u_status', '=', 1],
              ])
              ->leftJoin('branches as a', 
              function ($a) {
                $a->on('a.b_uni_id', '=', 'u_id')
                ->where('b_status', '=', 1);
              })
              ->leftJoin('states as b', 'b.state_id', '=', 'b_state')
              ->orderBy('u_name', 'asc')
              ->get();

      } catch (\Exception $e) {
        \Log::error($e);
      }
      return null;
    }

    public static function getNameById($id)
    {
      try {
        if(isset($id)) {
          $uni = University::where('u_id', '=', $id)->first();

          if(isset($uni)) return $uni->u_name;
        }
      } catch (\Exception $e) {
        \Log::error($e);
      }
      return null;
    }

    public static function getUniList($r)
    {
      try {
        if(isset($r)) {
          \DB::statement("SET SQL_MODE=''");
          $u = University::leftJoin('courses as a', 'a.c_uni', '=', 'u_id')
          ->leftJoin('branches as b', 'b.b_uni_id', '=', 'u_id')
          ->where([
            ['u_status', '=', 1],
            [function ($a) use ($r) {
              if($r->field != null) {
                $a->where('c_main_cat', '=', $r->field);
              }

              if($r->major != null) {
                $a->where('c_sub_cat', '=', $r->major);
              }

              if($r->level != null) {
                $a->where('c_level', '=', $r->level);
              }

              if($r->country != null) {
                $a->where('u_country', '=', $r->country)
                ->orWhere('b_country', '=', $r->country);
              }

              if($r->state != null) {
                $a->where('u_state', '=', $r->state)
                ->orWhere('b_state', '=', $r->state);
              }

              if($r->search != null) {
                $a->where('u_name', 'LIKE', '%' . $r->search . '%');
              }
            }]
          ])
          ->groupBy('u_id');

          if($r->sort != null) {
            if($r->sort == 1) {
              $u->orderBy('u_name', 'asc');
            } else{
              $u->orderBy('u_name', 'desc');
            }
          }
          return $u->get();
        }
      } catch (\Exception $e) {
        \Log::error($e);
      }
      return null;
    }
  /***********************************************************************
	 * Add
	 ***********************************************************************/
    public static function addUniversity(Request $r) {
       try{
         $dt = Gen::currTime();

         // Handle logo upload
        $logo = "logo-" . uniqid() . '.jpg';

        $r->file('logo')->move(
            public_path('/images/university/logo'), $logo
        );

        $url = $r->title;
        $url = preg_replace('/[^a-zA-Z0-9_ -]/s', '', $url);
        $url = str_replace(' ', '_', $url) . '_' . uniqid();

         $add = University::create([
           'u_url_code' => $url,
           'u_name' => $r->title,
           'u_has_rank' => $r->hasrank,
           'u_ranking' => $r->ranking,
           'u_student' => $r->student,
           'u_staff' => $r->staff,
           'u_year' => $r->year,
           'u_desc' => $r->desc,
           'u_addr' => $r->address,
           'u_state' => $r->state,
           'u_country' => $r->country,
           'u_hours' => $r->hours1 . '~' . $r->hours2,
           'u_has_branch' => $r->hasbranch,
           'u_type' => $r->type,
           'u_status' => 1,
           'created_at' => $dt,
           'updated_at' => $dt,
         ]);

         if (isset($add)) {
            $images = []; // Initialize an array to store image names

            // Handle multiple image uploads (if any)
            if (isset($r->img)) {
                foreach ($r->img as $image) {
                    $img = "uni-" . uniqid() . '.jpg';
                    $images[] = $img;

                    // Move the current image to the 'public/images/event' directory
                    $image->move(
                        public_path('/images/university/images'), $img);
                }
            }
            // Update the event record with the image paths
            $add->update(['u_img' => implode(',', $images), 'u_logo' => $logo]);

            // $images array now contains the unique names of the uploaded images
            // You can use this array as needed, for example, storing it in the database
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
    public static function editUniversity(Request $r) {
        try {
            $dt = Gen::currTime();
    
            if ($r->has('u_id')) {
                $u = University::find($r->u_id);
    
                // Handle logo upload only if a new logo is selected
                if ($r->hasFile('logo')) {
                    $logo = "logo-" . uniqid() . '.jpg';
    
                    $r->file('logo')->move(
                        public_path('/images/university/logo'), $logo
                    );
    
                    $u->u_logo = $logo;
                }
    
                // Handle multiple image uploads only if new images are selected
                if ($r->hasFile('img')) {
                    $images = [];
    
                    foreach ($r->file('img') as $image) {
                        $img = "uni-" . uniqid() . '.jpg';
                        $images[] = $img;
    
                        $image->move(
                            public_path('/images/university/images'), $img
                        );
                    }

                    $existingImages = explode(',', $u->u_img);
                    $deletedImages = explode(',', $r->deletedImage);

                    foreach ($deletedImages as $deletedIndex) {
                        $indexToDelete = $deletedIndex - 1;
                        
                        // Check if the index to delete exists in $existingImages array
                        if (isset($existingImages[$indexToDelete])) {
                            unset($existingImages[$indexToDelete]); // Remove the element
                        }
                    }
                    $existingImages = array_values($existingImages);

                    $allImages = array_merge($existingImages, $images);
                    $u->u_img = implode(',', $allImages);
                    // $u->u_img = implode(',', $images);
                }
    
                // Update other fields
                $u->u_name = $r->title;
                $u->u_has_rank = $r->hasrank;
                $u->u_ranking = $r->ranking;
                $u->u_student = $r->student;
                $u->u_staff = $r->staff;
                $u->u_year = $r->year;
                $u->u_addr = $r->address;
                $u->u_state = $r->state;
                $u->u_country = $r->country;
                $u->u_has_branch = $r->hasbranch;
                $u->u_type = $r->type;
                $u->u_hours = $r->hours1 . '~' . $r->hours2;
                $u->updated_at = $dt;

                // Check if 'desc' exists in the request and is not null
                if ($r->has('desc') && $r->desc !== null) {
                    $u->u_desc = $r->desc;
                }
    
                // Save the changes
                $u->save();
    
                return $u;
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

    public static function typeArr() {
      $arr = array();
      $arr[1] = 'Private';
      $arr[2] = 'Public';
      return $arr;
    }

}