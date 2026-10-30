<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Http\Request;
use Lang;


class Banner extends Model
{

	public $timestamps = false;
    public $primaryKey = "banner_id";
    protected $table = "banners";

    protected $fillable = [
        'banner_img', 'banner_status', 'created_at', 'updated_at',
    ];

    /***********************************************************************
     * By
     ***********************************************************************/

    /**
     * byId
     */
    public static function byId($banner_id) {
        try{
            if(isset($banner_id)) {
                return Banner::where('banner_id', '=', $banner_id)
                  ->first();
            }
        } catch(\Exception $e) {
            \Log::error($e);
        }

        return null;
    }


    /***********************************************************************
     * Get
     ***********************************************************************/

	/**
     * getAll
     */
    public static function getAll() {
        return Banner::orderBy('created_at', 'desc')->get();
    }


	/**
     * allActive
     */
    public static function allActive() {
        return Banner::where('banner_status', '=', 1)
          ->orderBy('created_at', 'desc')
          ->first();
    }


    /**
     * getLatest
     */
    public static function getLatest() {
        return Banner::where([
            ['banner_status', '=', 1]
        ])
          ->orderBy('created_at', 'desc')
          ->first();
    }

    /***********************************************************************
     * Add
     ***********************************************************************/

    /**
     * addBanner
     */
    public static function addBanner(Request $request) {
        \DB::beginTransaction();
        try{
            if(isset($request)) {
                $dt = Gen::currTime();
                $img = "banner-" . uniqid() . '.jpg';
                $b = Banner::create([
                    'banner_img' => $img,
                    'banner_status' => 1,
                    'created_at' => $dt,
                    'updated_at' => $dt,
                ]);
                if(isset($b)) {
                    $request->banner_img->move(
                        public_path('/images/banners'), $img
                    );

                    \DB::commit();
                    return $b;
                }
            }
        } catch(\Exception $e) {
            \Log::error($e);
        }

        \DB::rollback();
        return null;
    }


    /**
     * bannerW
     */
    public static function bannerW() {
        return 670;
    }


    /**
     * bannerH
     */
    public static function bannerH() {
        return 400;
    }


    /***********************************************************************
     * Array
     ***********************************************************************/

    /**
     * statusArr
     */
    public static function statusArr() {
        $arr = array();
        $arr[1] = Lang::get('lang.active');
        $arr[98] = Lang::get('lang.deactivated');
        return $arr;
    }
}
