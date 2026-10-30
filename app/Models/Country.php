<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Country extends Model
{
    protected $table = "full_countries";
    public $primaryKey = "country_id";
    public $timestamps = false;

    protected $fillable = [
        'country_code', 'country_name', 'country_ch_name', 'country_nicename', 'country_rep', 'country_no', 'delivery_fee', 'phonecode'
    ];

    /**********************************************************************
     * Functions
     **********************************************************************/
    public static function byId($country_no)
    {
        if (isset($country_no)) {
            return Country::where('country_no', '=', $country_no)
                ->first();
        }

        return NULL;
    }
    /**
     * Get All
     */
    public static function getAll() {
        try {
            return Country::all();
        } catch (\Exception $e) {
            \Log::error($e);
        }

        return null;
    }

    /**
     * Get Name By Id
     */
    public static function getNameById($country_no)
    {
        if (isset($country_no)) {
            $c = Country::byId($country_no);

            if (isset($c)) {
                return $c->country_name;
            }
        }

        return NULL;
    }
}