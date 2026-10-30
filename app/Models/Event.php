<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Http\Request;

use Lang;

class Event extends Model
{

	public $timestamps = false;
    public $primaryKey = "e_id";
    protected $table = "events";

    protected $fillable = [
        'e_title', 'e_url_code', 'e_desc', 'e_img', 'e_status', 'created_at', 'updated_at'
    ];


    /***********************************************************************
	 * Get
	 ***********************************************************************/
     public static function getAll() {
       try{
         return Event::where('e_status', '=', 1)
         ->orderBy('created_at', 'desc')
         ->get();
       }catch (\Exception $e) {
         \Log::error($e);
       }
       return null;
     }

    public static function getAllLimit($limit = null)
    {
      try{
        return Event::where('e_status', '=', 1)
        ->orderBy('created_at', 'desc')
        ->limit($limit)
        ->get();
      }catch (\Exception $e) {
        \Log::error($e);
      }
      return null;
    }

    public static function getByUrlCode($url)
    {
      try{
        if(isset($url)){
          return Event::where('e_url_code', '=', $url)->first();
        }
      }catch (\Exception $e) {
        \Log::error($e);
      }
      return null;
    }

		public static function getById($id)
    {
      try{
        if(isset($id)){
          return Event::where('e_id', '=', $id)->first();
        }
      }catch (\Exception $e) {
        \Log::error($e);
      }
      return null;
    }

		/***********************************************************************
	 * Add
	 ***********************************************************************/
     public static function addEvent(Request $r) {
       try{
         $dt = Gen::currTime();
         $url = $r->title;
         $url = preg_replace('/[^a-zA-Z0-9_ -]/s', '', $url);
         $url = str_replace(' ', '_', $url) . '_' . uniqid();
        //  $img = "event-" . uniqid() . '.jpg';

         $add = Event::create([
           'e_title' => $r->title,
        //    'e_img' => $img,
           'e_url_code' => $url,
           'e_desc' => $r->desc,
           'e_status' => 1,
           'created_at' => $dt,
           'updated_at' => $dt,
         ]);

         if (isset($add)) {
            $images = []; // Initialize an array to store image names

            // Handle multiple image uploads (if any)
            if (isset($r->img)) {
                foreach ($r->img as $image) {
                    $img = "event-" . uniqid() . '.jpg';
                    $images[] = $img;

                    // Move the current image to the 'public/images/event' directory
                    $image->move(
                        public_path('/images/event'), $img);
                }
            }
            // Update the event record with the image paths
            $add->update(['e_img' => implode(',', $images)]);

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
    public static function editEvent(Request $r) {
      try {
          $dt = Gen::currTime();
  
          if ($r->has('e_id')) {
              $ev = Event::find($r->e_id);
  
              // Separate existing images from newly uploaded images
              $existingImages = $r->input('existing_images', []);
              $newImages = $r->file('img', []);

              // Process existing images
              foreach ($existingImages as $index => $existingImage) {
                  // Check if existing image has been modified
                  if ($r->hasFile("img.{$index}")) {
                      // Handle modification of existing image (e.g., update file)
                      $file = $r->file("img.{$index}");

                      // Upload the new version of the image
                      $filename = "event-" . uniqid() . '.' . $file->getClientOriginalExtension();
                      $file->move(public_path('/images/event'), $filename);

                      // Update existingImages array with the filename of the new image
                      $existingImages[$index] = $filename;
                  }
              }

              // Process newly uploaded images
              foreach ($newImages as $newImage) {
                  // Handle newly uploaded images (e.g., move them to the desired directory)
                  $filename = "event-" . uniqid() . '.' . $newImage->getClientOriginalExtension();
                  $newImage->move(public_path('/images/event'), $filename);

                  // Add the filename of the new image to the existingImages array
                  $existingImages[] = $filename;
              }

              // Update database with the concatenated list of image filenames
              $ev->e_img = implode(',', $existingImages);

  
              $ev->e_title = $r->title;
              $ev->updated_at = $dt;

              if ($r->has('desc') && $r->desc !== null) {
                  $ev->e_desc = $r->desc;
              }
  
              // Save the changes
              $ev->save();
  
              return $ev;
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
}
