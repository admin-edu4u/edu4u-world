<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Http\Request;

class Scholarship extends Model
{
    public $timestamps = false;
	public $primaryKey = "s_id";
	protected $table = "scholarships";

    protected $fillable = [
		's_url_code', 's_is_corporate', 's_c_logo', 's_c_name', 's_uni_id', 's_level_id', 's_has_interview', 's_title', 's_study_field', 's_criteria', 's_highlights', 's_award', 's_details', 's_application', 's_type', 's_coverage', 's_requirement', 's_status', 'created_at', 'updated_at'
	];

  /***********************************************************************
	 * Get
	 ***********************************************************************/
    public static function getAll() {
        try{
          return Scholarship::where('s_status', '=', 1)
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
          return Scholarship::where('s_id', '=', $id)->first();
        }
      }catch (\Exception $e) {
        \Log::error($e);
      }
      return null;
    }

    public static function getByUrlCode($url)
    {
      try {
        return Scholarship::where('s_url_code', '=', $url)
        ->leftJoin('universities as a', 'a.u_id', '=', 's_uni_id')
        ->first();

      } catch (\Exception $e) {
        \Log::error($e);
      }
      return null;
    }

    public static function getByUniId($uni_id)
    {
      try{
        if(isset($uni_id)) {
          return Scholarship::where([
            ['s_status', '=', 1],
            ['s_uni_id', '=', $uni_id]
            ])
          ->leftJoin('universities as a', 'a.u_id', '=', 's_uni_id')
          ->orderBy('scholarships.created_at', 'desc')
          ->get();
        }
      } catch (\Exception $e) {
        \Log::error($e);
      }
      return null;
    }

    public static function getAvailableScholarship($user_id, $course)
    {
      try {
        if(isset($user_id, $course)) {
          $scholarship = Scholarship::where([
            ['s_uni_id', '=', $course->c_uni],
            ['s_status', '=', 1],
            ['s_level_id', '=', $course->c_level],
            ['sd_status', '=', 1]
            ])
          ->orWhere('s_is_corporate', '=', 1)
          ->leftJoin('scholarship_discount as a', 'a.sd_s_id', '=', 's_id')
          ->get();

          return $scholarship;
        }
      } catch (\Exception $e) {
        \Log::error($e);
      }
      return null;
    }

    public static function getTotalScholarshipByUni($id)
    {
      try {
        if(isset($id)) {
          $ttl = Scholarship::where('s_uni_id', '=', $id)->selectRaw('COALESCE(COUNT(s_id), 0) as total')->first();

          if(isset($ttl)) {
            return $ttl->total;
          }
        }
      } catch (\Exception $e) {
        \Log::error($e);
      }
      return 0;
    }
  /***********************************************************************
	 * Table
	 ***********************************************************************/

   public static function getScholarship($r)
   {
      try{
        if(isset($r)) {
          \DB::statement("SET SQL_MODE=''");
          $s = Scholarship::where([
            ['s_status', '=', 1],
            [function ($a) use ($r) {
              if($r->search != null) {
                $a->where('s_title', 'LIKE', '%' . $r->search . '%');
              }

              if($r->country != null) {
                $a->where('u_country', '=', $r->country)
                ->orWhere('b_country', '=', $r->country);
              }

              if($r->state != null) {
                $a->where([
                  ['u_country', '=', $r->country],
                  ['u_state', '=', $r->state]
                ])
                ->orWhere([
                  ['b_country', '=', $r->country],
                  ['b_state', '=', $r->state]
                ]);
              }

              if($r->institution != null) {
                $a->where('u_id', '=', $r->institution);
              }

              if($r->level != null) {
                $a->where('s_level_id', '=', $r->level);
              }

              if($r->sponsor != null) {
                $a->where('s_sponser', '=', $r->sponsor);
              }

              if($r->type != null) {
                $a->where('s_type', '=', $r->type);
              }

              if($r->requirement != null) {
                $a->where('s_requirement', '=', $r->requirement);
              }

              if($r->interview != null) {
                $a->where('s_has_interview', '=', $r->interview);
              }

            }]
          ])
          ->leftJoin('universities as a', 'a.u_id', '=', 's_uni_id')
          ->leftJoin('branches as b', 'b.b_uni_id', '=', 'a.u_id')
          ->leftJoin('scholarship_discount as c', 'c.sd_s_id', '=', 's_id')
          ->selectRaw('max(sd_percentage) as max_percentage, max(sd_amt) as max_amount, scholarships.*, c.*, a.u_name, a.u_logo')
          ->groupBy('s_id');

          if($r->sort != null) {
            if($r->sort == 1) {
              $s->orderBy('max_percentage', 'desc');
            } elseif($r->sort == 2) {
              $s->orderBy('max_percentage', 'asc');
            } elseif($r->sort == 3) {
              $s->orderBy('max_amount', 'desc');
            } elseif($r->sort == 4) {
              $s->orderBy('max_amount', 'asc');
            }
          }

          return $s->get();
        }
      } catch (\Exception $e) {
        \Log::error($e);
      }
      return null;
   }
  /***********************************************************************
	 * Add
	 ***********************************************************************/
    public static function addScholarship(Request $r) {
        try{
          $dt = Gen::currTime();

          $url = $r->title;
          $url = preg_replace('/[^a-zA-Z0-9_ -]/s', '', $url);
          $url = str_replace(' ', '_', $url) . '_' . uniqid();

          $logo = null;
          if ($r->companyname) {
              $logo = $r->companyname . uniqid() . '.jpg';
          }

          $implodedschlv = implode(',', $r->schlv);
    
          $add = Scholarship::create([
            's_url_code' => $url,
            's_is_corporate' => $r->iscorporate,
            's_c_name' => $r->companyname,
            's_c_logo' => $logo,
            's_uni_id' => $r->uni,
            's_level_id' => $implodedschlv,
            's_has_interview' =>$r->interview,
            's_title' => $r->title,
            's_study_field' => !empty($r->studyfield) ? implode(',', $r->studyfield) : null,
            's_criteria' => $r->criteria,
            's_details' => $r->details,
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
    public static function editScholarship(Request $r) {
        try {
            $dt = Gen::currTime();
    
            if ($r->has('s_id')) {
                $s = Scholarship::find($r->s_id);
    
                $s->s_is_corporate = $r->iscorporate;
                
                $logo = null;
                if ($r->hasFile('logo')) {
                    $logo = $r->companyname . uniqid() . '.jpg';
                    $s->s_c_logo = $logo;
                    $r->logo->move(public_path('/images/scholarships'), $logo);
                }

                $implodedschlv = implode(',', $r->editschlv);

                $s->s_c_name = $r->companyname;
                $s->s_uni_id = $r->edituni;
                $s->s_title = $r->edittitle;
                $s->s_level_id = $implodedschlv;
                $s->s_has_interview = $r->interview;
                $s->s_study_field = !empty($r->editstudyfield) ? implode(',', $r->editstudyfield) : null;
                $s->s_type = implode(',', $r->schtype);
                $s->s_coverage = implode(',', $r->schcover);
                $s->s_requirement = implode(',', $r->schreq);
                $s->updated_at = $dt;

                if ($r->has('editcriteria') && $r->editcriteria !== null) {
                    $s->s_criteria = $r->editcriteria;
                }

                if ($r->has('editdetails') && $r->editdetails !== null) {
                  $s->s_details = $r->editdetails;
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

  /***********************************************************************
	 * Array
	 ***********************************************************************/
  
   public static function typeArr() {
    $arr = array();
    $arr[1] = 'Full scholarship';
    $arr[2] = 'Partial scholarship';
    $arr[3] = 'Financial Aid';
    $arr[4] = 'Deal';
    $arr[5] = 'Benefit';
    return $arr;
  }

  public static function sponserArr() {
    $arr = array();
    $arr[1] = 'By Edu4u';
    $arr[2] = 'Institution';
    $arr[3] = 'Foundation';
    $arr[4] = 'Corporate';
    return $arr;
  }

  public static function coverageArr() {
    $arr = array();
    $arr[1] = 'Tuition Fee';
    $arr[2] = 'Resource Fee';
    $arr[3] = 'Accommodation';
    $arr[4] = 'Job Placement';
    $arr[5] = 'Others';
    return $arr;
  }

  public static function requirementArr() {
    $arr = array();
    $arr[1] = 'Financial';
    $arr[2] = 'Merit';
    $arr[3] = 'Sports';
    $arr[4] = 'Talent & Leadership';
    $arr[5] = 'Others';
    return $arr;
  }

  public static function interviewArr() {
    $arr = array();
    $arr[0] = 'No';
    $arr[1] = 'Yes';
    return $arr;
  }

}