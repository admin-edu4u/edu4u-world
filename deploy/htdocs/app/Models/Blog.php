<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Http\Request;

use Lang;

class Blog extends Model
{

	public $timestamps = false;
    public $primaryKey = "b_id";
    protected $table = "blogs";

    protected $fillable = [
        'b_title', 'b_url_code', 'b_cat_id', 'b_img', 'b_content', 'b_is_star', 'created_at', 'updated_at', 'b_status'
    ];


    /***********************************************************************
     * Get
     ***********************************************************************/
     public static function getAll() {
       try{
         return Blog::where('b_status', '=', 1)
         ->orderBy('b_is_star', 'desc')
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
        return Blog::where('b_status', '=', 1)
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
          return Blog::where('b_url_code', '=', $url)->first();
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
          return Blog::where('b_id', '=', $id)->first();
        }
      }catch (\Exception $e) {
        \Log::error($e);
      }
      return null;
    }

		/***********************************************************************
     * Add
     ***********************************************************************/
     public static function addBlog(Request $r) {
       try{
         $dt = Gen::currTime();
         $url = $r->title;
         $url = preg_replace('/[^a-zA-Z0-9_ -]/s', '', $url);
         $url = str_replace(' ', '_', $url) . '_' . uniqid();
         $img = "blog-" . uniqid() . '.jpg';

         $add = Blog::create([
           'b_title' => $r->title,
           'b_cat_id' => $r->blogcat,
           'b_img' => $img,
           'b_url_code' => $url,
           'b_content' => $r->content,
           'b_status' => 1,
           'created_at' => $dt,
           'updated_at' => $dt,
         ]);

         if(isset($add)){
           $r->img->move(
             public_path('/images/blogs'),
             $img
           );

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
      public static function editBlog(Request $r) {
        try {
            $dt = Gen::currTime();
        
            if ($r->has('b_id')) {
                $b = Blog::find($r->b_id);
        
                if ($b) {
                    $b->b_title = $r->title;
                    $b->b_cat_id = $r->blogcat;

                    if ($r->has('desc') && $r->desc !== null) {
                      $b->b_content = $r->desc;
                    }

                    $b->updated_at = $dt;

                    if ($r->hasFile('img')) {
                        $img = "blog" . uniqid() . '.jpg';
                        $b->b_img = $img;
                        $r->img->move(public_path('/images/blogs'), $img);
                    }
                    
                    $b->save();

                    if ($b) {
                        return true;
                    }
                }
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
