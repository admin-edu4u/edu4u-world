<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class State extends Model
{
    protected $table = "states";
    public $primaryKey = "state_id";
    public $timestamps = false;

    protected $fillable = [
        'state_country_id', 'state_code', 'state_name', 'state_type', 'state_status', 'created_at', 'updated_at'
    ];

    /**********************************************************************
     * Functions
     **********************************************************************/
    /**
     * By Id
     */
    public static function byId($state_id)
    {
        if (isset($state_id)) {
            return State::where('state_id', '=', $state_id)
                ->first();
        }

        return NULL;
    }

    /**
     * Get All
     */
    public static function getAll($country = NULL, $status = NULL)
    {
        return State::where(function ($a) use ($country, $status) {
            if (isset($status)) {
                $a->where('state_status', '=', $status);
            }

            if (isset($country)) {
                $a->where('state_country_id', '=', $country);
            }
        })
            ->get();
    }

    /**
     * Get All Active
     */
    public static function getAllActive($country = NULL)
    {
        return State::getAll($country, 1);
    }

    /**
     * Get By Country ID
     */
    public static function getByCountryId($country)
    {
        if (isset($country)) {
            return State::where('state_country_id', $country)->get();
        }

        return NULL;
    }

    /**
     * Get Name By ID
     */
    public static function getNameById($state_id)
    {
        if (isset($state_id)) {
            $s = State::byId($state_id);

            if (isset($s)) {
                return $s->state_name;
            }
        }

        return NULL;
    }

    /**
     * Get Code By ID
     */
    public static function getCodeById($state_id)
    {
        if (isset($state_id)) {
            $s = State::byId($state_id);

            if (isset($s)) {
                return $s->state_code;
            }
        }

        return NULL;
    }

    /**
     * Get All State
     */
    public static function getAllState() {
        try{
          return State::where('state_status', '=', 1)
          ->orderBy('state_id', 'asc')
          ->get();
        }catch (\Exception $e) {
          \Log::error($e);
        }
        return null;
    }

    /**********************************************************************
     * Process
     **********************************************************************/

    /**********************************************************************
     * Array
     **********************************************************************/
    /**
     * Status
     */
    public static function statusArr($index = NULL)
    {
        $arr[1] = \Lang::get('lang.active');
        $arr[2] = \Lang::get('lang.deactive');

        if (isset($index)) {
            return $arr[$index];
        }

        return $arr;
    }

    /**
     * stateType
     */
    public static function stateTypeArr()
    {
        return [
            1 => \Lang::get('lang.westMalaysia'),
            2 => \Lang::get('lang.eastMalaysia')
        ];
    }
}
