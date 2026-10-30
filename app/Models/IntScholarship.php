<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Http\Request;

class IntScholarship extends Model
{
    public $timestamps = false;
	public $primaryKey = "s_id";
	protected $table = "int_scholarship";

    protected $fillable = [
		's_url_code', 's_is_corporate', 's_c_logo', 's_c_name', 's_int_id', 's_has_interview', 's_title', 's_details', 's_criteria', 's_application', 's_type', 's_coverage', 's_requirement', 's_status', 'created_at', 'updated_at'
	];

    /***********************************************************************
	 * Get
	 ***********************************************************************/
    public static function getAll() {
        try{
          return IntScholarship::where('s_status', '=', 1)
          ->orderBy('s_id', 'desc')
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
          return IntScholarship::where('s_id', '=', $id)->first();
        }
      }catch (\Exception $e) {
        \Log::error($e);
      }
      return null;
    }

    public static function getByUniId($id)
    {
      try{
        if(isset($id)){
          return IntScholarship::where([
            ['s_int_id', '=', $id],
            ['s_status', '=', 1]
            ])
            ->leftJoin('international_school as a', 'a.int_id', '=', 's_int_id')
            ->orderBy('int_scholarship.created_at', 'desc')
            ->get();
        }
      }catch (\Exception $e) {
        \Log::error($e);
      }
      return null;
    }

    public static function getTotalScholarshipBySchool($id)
    {
      try {
        if(isset($id)) {
          $ttl = IntScholarship::where('s_int_id', '=', $id)->selectRaw('COALESCE(COUNT(s_id), 0) as total')->first();

          if(isset($ttl)) {
            return $ttl->total;
          }
        }
      } catch (\Exception $e) {
        \Log::error($e);
      }
      return 0;
    }

    public static function getByUrlCode($url)
    {
      try {
        return IntScholarship::where('s_url_code', '=', $url)
        ->leftJoin('international_school as a', 'a.int_id', '=', 's_int_id')
        ->first();

      } catch (\Exception $e) {
        \Log::error($e);
      }
      return null;
    }

     /***********************************************************************
	 * Add
	 ***********************************************************************/
    public static function addK12Scholarship(Request $r) {
        try{
          $dt = Gen::currTime();

          $url = $r->title;
          $url = preg_replace('/[^a-zA-Z0-9_ -]/s', '', $url);
          $url = str_replace(' ', '_', $url) . '_' . uniqid();

          $logo = null;
          if ($r->companyname) {
              $logo = $r->companyname . uniqid() . '.jpg';
          }
    
          $add = IntScholarship::create([
            's_url_code' => $url,
            's_is_corporate' => $r->iscorporate,
            's_c_name' => $r->companyname,
            's_c_logo' => $logo,
            's_int_id' => $r->uni,
            's_has_interview' =>$r->interview,
            's_title' => $r->title,
            's_details' => $r->details,
            's_criteria' => $r->criteria,
            's_application' => $r->application,
            's_type' => implode(',', $r->schtype),
            's_coverage' => implode(',', $r->schcover),
            's_requirement' => implode(',', $r->schreq),
            's_status' => 1,
            'created_at' => $dt,
            'updated_at' => $dt,
          ]);
    
          if (isset($add) && $r->hasFile('logo')) {
            $r->logo->move(public_path('/images/scholarships'), $logo);
              return $add;
          } 
          else {
              return $add;
          }
         
        }catch (\Exception $e) {
          \Log::error($e);
        }
        return null;
      }

    /***********************************************************************
	 * Edit / Update
	 ***********************************************************************/
    public static function editK12Scholarship(Request $r) {
        try {
            $dt = Gen::currTime();
    
            if ($r->has('s_id')) {
                $s = IntScholarship::find($r->s_id);

                $s->s_is_corporate = $r->iscorporate;
                
                $logo = null;
                if ($r->hasFile('logo')) {
                    $logo = $r->companyname . uniqid() . '.jpg';
                    $s->s_c_logo = $logo;
                    $r->logo->move(public_path('/images/scholarships'), $logo);
                }
    
                $s->s_c_name = $r->companyname;
                $s->s_int_id = $r->edituni;
                $s->s_title = $r->edittitle;
                $s->s_has_interview = $r->interview;
                $s->s_type = implode(',', $r->schtype);
                $s->s_coverage = implode(',', $r->schcover);
                $s->s_requirement = implode(',', $r->schreq);
                $s->updated_at = $dt;

                if ($r->has('editdetails') && $r->editdetails !== null) {
                    $s->s_details = $r->editdetails;
                }
                
                if ($r->has('editcriteria') && $r->editcriteria !== null) {
                    $s->s_criteria = $r->editcriteria;
                }

                if ($r->has('editapplication') && $r->editapplication !== null) {
                    $s->s_application = $r->editapplication;
                }

                $s->save();
    
                return $s;
            }
        } catch (\Exception $e) {
            \Log::error($e);
        }
    
        return null;
    }
}