<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Http\Request;

use Session;

class Course extends Model
{
    public $timestamps = false;
	public $primaryKey = "c_id";
	protected $table = "courses";

    protected $fillable = [
		'c_name', 'c_url_code', 'c_uni', 'c_level', 'c_stream', 'c_intake', 'c_duration', 'c_award', 'c_has_internship', 'c_internship_duration', 'c_outline', 'c_is_malaysian', 
    'c_represent_sport', 'c_represent_level', 'c_talent', 'c_talent_level', 'c_leadership', 'c_leadership_level', 'c_amount', 'c_amount_bumi', 'c_amount_international', 
    'c_is_bumiputra', 'c_main_cat', 'c_sub_cat', 'c_core', 'c_elective', 'c_other', 'c_english_required', 'c_entry_req', 'c_status', 'created_at', 'updated_at'
	];

  /***********************************************************************
	 * Get
	 ***********************************************************************/
    public static function getAll() {
      try{
          return Course::where('c_status', '=', 1)
          ->orderBy('c_uni', 'desc')
          ->get();
        }catch (\Exception $e) {
          \Log::error($e);
        }
        return null;
    }

    public static function admingetAll() {
      try{
          return Course::whereIn('c_status', [1, 98])
          ->orderBy('c_uni', 'desc')
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
          return Course::where('c_id', '=', $id)->first();
        }
      }catch (\Exception $e) {
        \Log::error($e);
      }
      return null;
    }

    public static function getTotalCoursesByUni($id)
    {
      try {
        if(isset($id)) {
          $ttl = Course::where('c_uni', '=', $id)->selectRaw('COALESCE(COUNT(c_id), 0) as total')->first();

          if(isset($ttl)) {
            return $ttl->total;
          }
        }
      } catch (\Exception $e) {
        \Log::error($e);
      }
      return 0;
    }

    public static function getByIntakeByUni($id)
    {
      try {
        if(isset($id)) {
          return Course::where('c_uni', '=', $id)
                        ->selectRaw('c_level, c_intake')
                        ->groupBy('c_level','c_intake')
                        ->get();
        }
      } catch (\Exception $e) {
        \Log::error($e);
      }
      return null;
    }

    public static function getCourseCatByUni($id)
    {
      try {
        if(isset($id)) {
          return Course::where([
              ['c_uni', '=', $id],
              ['courses.c_status', '=', 1]
            ])
            ->leftJoin('categories as a', 'a.c_id', '=', 'c_main_cat')
            ->selectRaw('c_main_cat, a.c_name')
            ->groupBy('c_main_cat', 'a.c_name')
            ->get();
        }
      } catch (\Exception $e) {
        \Log::error($e);
      }
      return null;
    }

    public static function getCourseByMainCat($cat_id, $uni_id) {
      try {
        if(isset($cat_id, $uni_id)) {
          return Course::where([
            ['c_uni', '=', $uni_id],
            ['c_main_cat', '=', $cat_id],
            ['c_status', '=', 1]
          ])
          ->leftJoin('universities as a', 'a.u_id', '=', 'c_uni')
          ->orderBy('courses.created_at', 'desc')
          ->get();
        }
      } catch (\Exception $e) {
        \Log::error($e);
      }
      return null;
    }

    public static function getByUrlCode($code)
    {
      try {
        if(isset($code)) {
          return Course::where('c_url_code', '=', $code)
                        ->leftJoin('universities as a', 'a.u_id', '=', 'c_uni')
                        ->first();
        }
      } catch (\Exception $e) {
        \Log::error($e);
      }
      return null;
    }

    public static function getCourseByMainCatLimit($main_cat)
    {
      try {
        if(isset($main_cat)) {
          return Course::where([
            ['c_main_cat', '=', $main_cat],
            ['c_status', '=', 1]
            ])
          ->leftJoin('universities as a', 'a.u_id', '=', 'c_uni')
          ->inRandomOrder()
          ->limit(6)
          ->get();
        }
      } catch (\Exception $e) {
        \Log::error($e);
      }
      return null;
    }


    public static function getRecommendCourseJsOld(Request $r)
    {
      try {
        if(isset($r)) {
          // $id = Models\User::getById($id);
          $id = 4;
          $academic = Academic::where('a_user_id', '=', $id)->first();
          $family = Family::where('f_user_id', '=', $id)->first();
          $uni = UniversityPreference::where('up_user_id', '=', $id)->first();

          \DB::statement("SET SQL_MODE=''");
          $c = Course::where([
            ['c_status', '=', 1],
            [function ($a) use ($r, $academic, $family, $uni) {
              if($r->character != null) {
                $a->where('sc_character', '=', $r->character);
              }

              if($uni->up_location == 1) {
                $a->where('u_state', '=', $uni->up_state)
                  ->orWhere('b_state', '=', $uni->up_state);
              } else if($uni->up_location == 2) {
                $a->where('u_country', '=', $uni->up_country)
                  ->orWhere('b_country', '=', $uni->up_country);

                $a->where('u_state', '=', $uni->up_state)
                  ->orWhere('b_state', '=', $uni->up_state);
              } else if($uni->up_location == 3) {
                $a->where('u_country', '=', $uni->up_country)
                  ->orWhere('b_country', '=', $uni->up_country);
              }
            }]
          ])
          ->leftJoin('universities as a', 'a.u_id', '=', 'c_uni')
          ->leftJoin('sub_cats as c', 'c.sc_id', '=', 'c_sub_cat')
          ->leftJoin('branches as d', 'd.b_uni_id', '=', 'c_uni')
          ->groupBy('c_id');

          if($academic->is_malaysian == 1) {
            $c->whereIn('c_is_malaysian', array(1,2));
          } else if($academic->is_malaysian == 2) {
            $c->whereIn('c_is_malaysian', array(0,2));
          }
          
          if($family->f_budget == 1) {
            $c->where('c_amount', '<=', 20000); 
          } else if($family->f_budget == 2) {
            $c->where('c_amount', '>=', 20001); 
            $c->where('c_amount', '<=', 40000);
          } else if($family->f_budget == 3) {
            $c->where('c_amount', '>=', 40001); 
            $c->where('c_amount', '<=', 60000);
          } else if($family->f_budget == 4) {
            $c->where('c_amount', '>=', 60001); 
            $c->where('c_amount', '<=', 80000);
          } else if($family->f_budget == 5) {
            $c->where('c_amount', '>=', 80001); 
            $c->where('c_amount', '<=', 100000);
          } else if($family->f_budget == 6) {
            $c->where('c_amount', '>', 100000); 
          }

          if($uni->up_pathway == 1) {
            $c->where('c_level', '=', 2); 
          } else if($uni->up_pathway == 2) {
            $c->where('c_level', '=', 1);
          } else if($uni->up_pathway == 3) {
            $c->where('c_level', '=', 3);
          } else if($uni->up_pathway == 4) {
            $c->where('c_level', '=', 4);
          }

          if($uni->up_type == 1) {
            $c->where('u_type', '=', 1);
          } else if($uni->up_type == 2) {
            $c->where('u_type', '=', 2);
          }

          $c = $c->get();

          return $c;
        }
      } catch (\Exception $e) {
        \Log::error($e);
      }
      return null;
    }

    public static function getRecommendCourseJs(Request $r)
    {
      try {
        if(isset($r)) {
          $id = Session::get('user_id');
          // $id = 26;
          $user = User::getById($id);
          $family = Family::where('f_user_id', '=', $id)->first();
          $uni = UniversityPreference::where('up_user_id', '=', $id)->first();
          $state = explode(',', $uni->up_state);

          \DB::statement("SET SQL_MODE=''");
          $c = Course::where([
            ['c_status', '=', 1],
            ['c_level', '=', $uni->up_pathway],
            [function ($a) use ($r, $user, $family, $uni, $state) {     
              if($r->character != null) {
                $a->where('sc_character', '=', $r->character);
              }
    
              if($family->f_budget == 1) {
                $a->where('c_amount', '<=', 20000); 
              } else if($family->f_budget == 2) { 
                $a->where('c_amount', '<=', 40000);
              } else if($family->f_budget == 3) {
                $a->where('c_amount', '<=', 60000);
              } else if($family->f_budget == 4) {
                $a->where('c_amount', '<=', 80000);
              } else if($family->f_budget == 5) {
                $a->where('c_amount', '<=', 100000);
              }
    
              if($user->is_malaysian == 1) {
                $a->whereIn('c_is_malaysian', array(1,2));
              } else if($user->is_malaysian == 2) {
                $a->whereIn('c_is_malaysian', array(0,2));
              }

              if($uni->up_type == 1) {
                $a->where('ut_type', '=', 1);
              } else if($uni->up_type == 2) {
                $a->where('ut_type', '=', 2);
              }

            }]
          ])
          ->where(function ($a) use ($uni,$state) {
            if($uni->up_location == 1) {
              $a->whereIn('u_state', $state)
              ->orWhereIn('b_state', $state);
            } else if($uni->up_location == 2) {
                $a->where('u_country', '=', $uni->up_country)
                ->orWhere('b_country', '=', $uni->up_country)
                ->orWhereIn('u_state', $state)
                ->orWhereIn('b_state', $state);
            } else if($uni->up_location == 3) {
                $a->where('u_country', '=', $uni->up_country)
                ->orWhere('b_country', '=', $uni->up_country);
            }
          })
          ->leftJoin('universities as a', 'a.u_id', '=', 'c_uni')
          ->leftJoin('sub_cats as c', 'c.sc_id', '=', 'c_sub_cat')
          ->leftJoin('branches as d', 'd.b_uni_id', '=', 'c_uni')
          ->leftJoin('university_type as e', 'e.ut_id', '=', 'u_type')
          ->groupBy('c_id');

          // if($r->character != null) {
          //   $c->where('sc_character', '=', $r->character);
          // }

          // if($family->f_budget == 1) {
          //   $c->where('c_amount', '<=', 20000); 
          // } else if($family->f_budget == 2) { 
          //   $c->where('c_amount', '<=', 40000);
          // } else if($family->f_budget == 3) {
          //   $c->where('c_amount', '<=', 60000);
          // } else if($family->f_budget == 4) {
          //   $c->where('c_amount', '<=', 80000);
          // } else if($family->f_budget == 5) {
          //   $c->where('c_amount', '<=', 100000);
          // }

          // if($user->is_malaysian == 1) {
          //   $c->whereIn('c_is_malaysian', array(1,2));
          // } else if($user->is_malaysian == 2) {
          //   $c->whereIn('c_is_malaysian', array(0,2));
          // }

          // if($uni->up_type == 1) {
          //   $c->where('u_type', '=', 1);
          // } else if($uni->up_type == 2) {
          //   $c->where('u_type', '=', 2);
          // }
          // $c->groupBy('c_id');
          return $c->get();
        }
      } catch (\Exception $e) {
        \Log::error($e);
      }
      return null;
    }

    public static function getAllRecommendCourseJs(Request $r)
    {
      try {
        if(isset($r)) {
          $id = Session::get('user_id');
          // $id = 26;
          $user = User::getById($id);
          $family = Family::where('f_user_id', '=', $id)->first();
          $uni = UniversityPreference::where('up_user_id', '=', $id)->first();
          $state = explode(',', $uni->up_state);

          \DB::statement("SET SQL_MODE=''");
          $c = Course::where([
            ['c_status', '=', 1],
            ['c_level', '=', $uni->up_pathway],
            [function ($a) use ($r, $user, $family, $uni, $state) {     
              if($r->character != null) {
                $a->where('sc_character', '=', $r->character);
              }
    
              // if($family->f_budget == 1) {
              //   $a->where('c_amount', '<=', 20000); 
              // } else if($family->f_budget == 2) { 
              //   $a->where('c_amount', '<=', 40000);
              // } else if($family->f_budget == 3) {
              //   $a->where('c_amount', '<=', 60000);
              // } else if($family->f_budget == 4) {
              //   $a->where('c_amount', '<=', 80000);
              // } else if($family->f_budget == 5) {
              //   $a->where('c_amount', '<=', 100000);
              // }
    
              // if($user->is_malaysian == 1) {
              //   $a->whereIn('c_is_malaysian', array(1,2));
              // } else if($user->is_malaysian == 2) {
              //   $a->whereIn('c_is_malaysian', array(0,2));
              // }

              // if($uni->up_type == 1) {
              //   $a->where('ut_type', '=', 1);
              // } else if($uni->up_type == 2) {
              //   $a->where('ut_type', '=', 2);
              // }

            }]
          ])
          // ->where(function ($a) use ($uni,$state) {
          //   if($uni->up_location == 1) {
          //     $a->whereIn('u_state', $state)
          //     ->orWhereIn('b_state', $state);
          //   } else if($uni->up_location == 2) {
          //       $a->where('u_country', '=', $uni->up_country)
          //       ->orWhere('b_country', '=', $uni->up_country)
          //       ->orWhereIn('u_state', $state)
          //       ->orWhereIn('b_state', $state);
          //   } else if($uni->up_location == 3) {
          //       $a->where('u_country', '=', $uni->up_country)
          //       ->orWhere('b_country', '=', $uni->up_country);
          //   }
          // })
          ->leftJoin('universities as a', 'a.u_id', '=', 'c_uni')
          ->leftJoin('sub_cats as c', 'c.sc_id', '=', 'c_sub_cat')
          ->leftJoin('branches as d', 'd.b_uni_id', '=', 'c_uni')
          ->leftJoin('university_type as e', 'e.ut_id', '=', 'u_type')
          ->groupBy('c_id');

          // if($r->character != null) {
          //   $c->where('sc_character', '=', $r->character);
          // }

          // if($family->f_budget == 1) {
          //   $c->where('c_amount', '<=', 20000); 
          // } else if($family->f_budget == 2) { 
          //   $c->where('c_amount', '<=', 40000);
          // } else if($family->f_budget == 3) {
          //   $c->where('c_amount', '<=', 60000);
          // } else if($family->f_budget == 4) {
          //   $c->where('c_amount', '<=', 80000);
          // } else if($family->f_budget == 5) {
          //   $c->where('c_amount', '<=', 100000);
          // }

          // if($user->is_malaysian == 1) {
          //   $c->whereIn('c_is_malaysian', array(1,2));
          // } else if($user->is_malaysian == 2) {
          //   $c->whereIn('c_is_malaysian', array(0,2));
          // }

          // if($uni->up_type == 1) {
          //   $c->where('u_type', '=', 1);
          // } else if($uni->up_type == 2) {
          //   $c->where('u_type', '=', 2);
          // }
          // $c->groupBy('c_id');
          return $c->get();
        }
      } catch (\Exception $e) {
        \Log::error($e);
      }
      return null;
    }

    public static function getAllWithUni() {
      try{
          return Course::where('c_status', '=', 1)
          ->orderBy('c_uni', 'desc')
          ->leftJoin('universities', 'courses.c_uni', '=', 'universities.u_id')
          ->get();
        }catch (\Exception $e) {
          \Log::error($e);
        }
        return null;
    }
  /***********************************************************************
	 * Add
	 ***********************************************************************/
  public static function addCourse(Request $r) {
    try {
      $dt = Gen::currTime();
  
      $url = $r->coursename . '_' . $r->courseuni;
      $url = preg_replace('/[^a-zA-Z0-9_ -]/s', '', $url);
      $url = str_replace(' ', '_', $url) . '_' . uniqid();
  
      // Process Core
      $coretitle = $r->input("title1");
      $coredesc = $r->input("coremodule");
  
      $c_core = [];
  
      foreach ($coretitle as $title1Index => $title1) {
          if (!empty($title1) && isset($coredesc[$title1Index])) {
              $c_core[] = [
                  'title' => $title1,
                  'desc' => $coredesc[$title1Index]
              ];
          }
      }
      $encodedCore = json_encode($c_core);
  
      // Process Elective
      $electivetitle = $r->input("title2");
      $electivedesc = $r->input("electivemodule");
  
      $c_elective = [];
  
      foreach ($electivetitle as $title2Index => $title2) {
          if (!empty($title2) && isset($electivedesc[$title2Index])) {
              $c_elective[] = [
                  'title' => $title2,
                  'desc' => $electivedesc[$title2Index]
              ];
          }
      }
      $encodedElective = json_encode($c_elective);
  
      // Process Other
      $othertitle = $r->input("title3");
      $otherdesc = $r->input("othermodule");
  
      $c_other = [];
  
      foreach ($othertitle as $title3Index => $title3) {
          if (!empty($title3) && isset($otherdesc[$title3Index])) {
              $c_other[] = [
                  'title' => $title3,
                  'desc' => $otherdesc[$title3Index]
              ];
          }
      }
      $encodedOther = json_encode($c_other);
  
      // Create Course
      $add = Course::create([
          'c_url_code' => $url,
          'c_name' => $r->coursename,
          'c_uni' => $r->courseuni,
          'c_level' => $r->courselevel,
          'c_stream' => $r->coursestream,
          'c_english_required' => $r->engreq,
          'c_intake' => implode(',', $r->coursemonth),
          'c_duration' => $r->courseduration,
          'c_award' => $r->courseaward,
          'c_has_internship' => $r->courseinternship,
          'c_internship_duration' => $r->internduration,
          'c_outline' => $r->courseoutline,
          'c_is_malaysian' => $r->courseisfor,
          'c_represent_sport' => $r->coursesportsrepresent,
          'c_represent_level' => $r->representlevel,
          'c_talent' => $r->coursetalentrepresent,
          'c_talent_level' => $r->talentlevel,
          'c_leadership' => $r->courseleadership,
          'c_leadership_level' => $r->leadershiplevel,
          'c_amount' => $r->courseamount,
          'c_amount_bumi' => $r->courseamountbumi,
          'c_amount_international' => $r->courseamountfore,
          'c_is_bumiputra' => $r->coursebumiputra,
          'c_main_cat' => $r->coursemaincat,
          'c_sub_cat' => $r->coursesubcat,
          'c_core' => $encodedCore,
          'c_elective' => $encodedElective,
          'c_other' => $encodedOther,
          'c_entry_req' => $r->entryreq,
          'c_status' => 1,
          'created_at' => $dt,
          'updated_at' => $dt,
      ]);
  
      return $add;
    } catch (\Exception $e) {
        \Log::error($e);
    }
    
    return null;
  
  }

  /***********************************************************************
	 * Edit Update
	 ***********************************************************************/
    public static function editCourse(Request $r) {
      try {
        $dt = Gen::currTime();

        if ($r->has('c_id')) {
            $c = Course::find($r->c_id);

            // Update other fields
            $c->c_name = $r->editcoursename;
            $c->c_uni = $r->editcourseuni;
            $c->c_level = $r->editcourselevel;
            $c->c_stream = $r->editcoursestream;
            $c->c_english_required = $r->engreq;
            $c->c_intake = implode(',', $r->editcoursemonth);
            $c->c_duration = $r->editcourseduration;
            $c->c_award = $r->editcourseaward;
            $c->c_has_internship = $r->editcourseinternship;
            $c->c_internship_duration = $r->editinternduration;
            $c->c_is_malaysian = $r->editcourseisfor;
            $c->c_represent_sport = $r->editcoursesportsrepresent;
            $c->c_represent_level = $r->editrepresentlevel;
            $c->c_talent = $r->editcoursetalentrepresent;
            $c->c_talent_level = $r->edittalentlevel;
            $c->c_leadership = $r->editcourseleadership;
            $c->c_leadership_level = $r->editleadershiplevel;
            $c->c_amount = $r->editcourseamount;
            $c->c_amount_bumi = $r->editcourseamountbumi;
            $c->c_amount_international = $r->editcourseamountfore;
            $c->c_is_bumiputra = $r->editcoursebumiputra;
            $c->c_main_cat = $r->editcoursemaincat;
            $c->c_sub_cat = $r->editcoursesubcat;
            $c->updated_at = $dt;

            if ($r->has('editcourseoutline') && $r->editcourseoutline !== null) {
                $c->c_outline = $r->editcourseoutline;
            }

            if ($r->has('entryreq') && $r->entryreq !== null) {
                $c->c_entry_req = $r->entryreq;
            }

            $coretitle = $r->input("title1");
            $coredesc = $r->input("editcoremodule");

            $c_core = [];

            if (!empty($coretitle) && !empty($coredesc)) {
                foreach ($coretitle as $title1Index => $title1) {
                    if (!empty($title1) && isset($coredesc[$title1Index])) {
                        $c_core[] = [
                            'title' => $title1,
                            'desc' => $coredesc[$title1Index]
                        ];
                    }
                }
                $encodedCore = json_encode($c_core);
                $c->c_core = $encodedCore;
            }

            $electivetitle = $r->input("title2");
            $electivedesc = $r->input("editelectivemodule");

            $c_elective = [];

            if (!empty($electivetitle) && !empty($electivedesc)) {
                foreach ($electivetitle as $title2Index => $title2) {
                    if (!empty($title2) && isset($electivedesc[$title2Index])) {
                        $c_elective[] = [
                            'title' => $title2,
                            'desc' => $electivedesc[$title2Index]
                        ];
                    }
                }
                $encodedElective = json_encode($c_elective);
                $c->c_elective = $encodedElective;
            }

            $othertitle = $r->input("title3");
            $otherdesc = $r->input("editothermodule");

            $c_other = [];

            if (!empty($othertitle) && !empty($otherdesc)) {
                foreach ($othertitle as $title3Index => $title3) {
                    if (!empty($title3) && isset($otherdesc[$title3Index])) {
                        $c_other[] = [
                            'title' => $title3,
                            'desc' => $otherdesc[$title3Index]
                        ];
                    }
                }
                $encodedOther = json_encode($c_other);
                $c->c_other = $encodedOther;
            }
        
            $c->save();

            return $c;
        }
      }
     catch (\Exception $e) {
        \Log::error($e);
    }

    return null;
    }

  /***********************************************************************
	 * Table
	 ***********************************************************************/

   public static function getCourses($r)
   {
    try {
      if(isset($r)) {
        \DB::statement("SET SQL_MODE=''");
        $c = Course::where([
          ['c_status', '=', 1],
          [function ($a) use ($r) {
  
            if($r->search != null) {
              $a->where('c_name', 'LIKE', '%' . $r->search . '%');
            }
  
            if($r->field != null) {
              $a->where('c_main_cat', '=', $r->field);
            }
  
            if($r->major != null) {
              $a->where('c_sub_cat', '=', $r->major);
            }
  
            if($r->level != null) {
              $a->where('c_level', '=', $r->level);
            }
  
            if($r->fee != null) {
              if($r->fee == 1) {
                $a->where('c_amount', '<=', 20000);
              } else if($r->fee == 2) {
                $a->where('c_amount', '>', 20000);
                $a->where('c_amount', '<=', 40000);
              } else if($r->fee == 3) {
                $a->where('c_amount', '>', 40000);
                $a->where('c_amount', '<=', 60000);
              } else if($r->fee == 4) {
                $a->where('c_amount', '>', 60000);
                $a->where('c_amount', '<=', 80000);
              } else if($r->fee == 5) {
                $a->where('c_amount', '>', 80000);
                $a->where('c_amount', '<=', 100000);
              } else if($r->fee == 6) {
                $a->where('c_amount', '>', 100000);
              }
            }
  
            if($r->duration != null) {
              $a->where('c_duration', '=', $r->duration);
            }
  
            if($r->country != null) {
              $a->where('u_country', '=', $r->country)
              ->orWhere('b_country', '=', $r->country);
            }
  
          }]
        ])
        ->leftJoin('universities as a', 'a.u_id', '=', 'c_uni')
        ->leftJoin('branches as b', 'b.b_uni_id', '=', 'a.u_id')
        ->groupBy('c_id');

        if($r->sort != null) {
          if($r->sort == 1) {
            $c->orderBy('c_amount', 'asc');
          } else{
            $c->orderBy('c_amount', 'desc');
          }
        }
        return $c->get();
      }

    } catch (\Exception $e) {
      \Log::error($e);
    }
    return null;
   }

   public static function getCompareCourse($r)
   {
      try {
        return Course::where([
          ['c_status', '=', 1],
          [function ($a) use ($r) {
            if($r->search != null) {
              $a->where('c_name', 'LIKE', '%' . $r->search . '%');
            }

            if($r->uni != null) {
              $a->where('c_uni', '=', $r->uni);
            }

          }]
        ])
        ->leftJoin('universities as a', 'a.u_id', '=', 'c_uni')
        ->orderBy('c_name', 'asc')
        ->get();

      }catch (\Exception $e) {
          \Log::error($e);
      }
        return null;
    }

   public static function getCourseInfo($courseID)
   {
    try { 
      return Course::where('courses.c_id', $courseID)
            ->where('courses.c_status', 1)
            ->leftJoin('universities', 'universities.u_id', '=', 'courses.c_uni')
            ->leftJoin('scholarships', 'scholarships.s_uni_id', '=', 'universities.u_id')
            ->leftJoin('scholarship_discount', 'scholarship_discount.sd_s_id', '=', 'scholarships.s_id')
            ->leftJoin('states', 'states.state_id', '=', 'universities.u_state')
            ->leftJoin('full_countries', 'full_countries.country_id', '=', 'universities.u_country')
            ->leftJoin('categories', 'categories.c_id', '=', 'courses.c_main_cat')
            ->leftJoin('sub_cats', 'sub_cats.sc_id', '=', 'courses.c_sub_cat')
            ->leftJoin('course_grades', 'course_grades.cg_c_id', '=', 'courses.c_id')
            ->select(
              'courses.*', 
              'universities.*', 
              'scholarships.*', 
              'scholarship_discount.*',
              'states.*',
              'full_countries.*',
              'categories.c_name as category_name',
              'sub_cats.*',
              'course_grades.*',
              \DB::raw('(SELECT GROUP_CONCAT(cg_level SEPARATOR ",") FROM course_grades WHERE cg_c_id = courses.c_id) AS grades'),
              // \DB::raw('(SELECT GROUP_CONCAT(sd_amt SEPARATOR ",") FROM scholarship_discount WHERE sd_s_id = scholarships.s_id AND sd_is_amt = 1 AND sd_status = 1) AS amount_discount')
              )
              // ->addSelect(\DB::raw('(SELECT GROUP_CONCAT(sd_percentage SEPARATOR ",") FROM scholarship_discount WHERE universities.u_id = scholarships.s_uni_id AND scholarship_discount.sd_is_amt = 0 AND scholarship_discount.sd_status = 1) AS percentage_discount'))
              ->addSelect(\DB::raw('(SELECT GROUP_CONCAT(s_id SEPARATOR ",") FROM scholarships WHERE universities.u_id = scholarships.s_uni_id AND scholarships.s_status = 1) AS offer_scholarships'))
            ->first();

      // return Course::where('courses.c_id', $courseID)
      // ->where('courses.c_status', 1)
      // ->leftJoin('universities', 'universities.u_id', '=', 'courses.c_uni')
      // ->leftJoin('scholarships', 'scholarships.s_uni_id', '=', 'universities.u_id')
      // ->leftJoin('states', 'states.state_id', '=', 'universities.u_state')
      // ->leftJoin('full_countries', 'full_countries.country_id', '=', 'universities.u_country')
      // ->leftJoin('categories', 'categories.c_id', '=', 'courses.c_main_cat')
      // ->leftJoin('sub_cats', 'sub_cats.sc_id', '=', 'courses.c_sub_cat')
      // ->leftJoin('scholarship_discount', function($join) {
      //     $join->on('scholarship_discount.sd_s_id', '=', 'scholarships.s_id')
      //         ->where(function($query) {
      //             $query->where('scholarship_discount.sd_is_amt', 0)
      //                   ->where('scholarship_discount.sd_percentage', '=', function($subquery) {
      //                       $subquery->select(\DB::raw('MAX(sd_percentage)'))
      //                               ->from('scholarship_discount')
      //                               ->where('sd_status', 1);
      //                   });
      //         })
      //         ->orWhere(function($query) {
      //             $query->where('scholarship_discount.sd_is_amt', 1)
      //                   ->where('scholarship_discount.sd_amt', '=', function($subquery) {
      //                       $subquery->select(\DB::raw('MAX(sd_amt)'))
      //                               ->from('scholarship_discount')
      //                               ->where('sd_status', 1);
      //                   });
      //         });
      // })
      // ->select(
      //     'courses.*', 
      //     'universities.*', 
      //     'scholarships.*', 
      //     \DB::raw('CASE WHEN scholarship_discount.sd_is_amt = 0 THEN scholarship_discount.sd_percentage ELSE NULL END AS highest_sd_percentage'),
      //     \DB::raw('CASE WHEN scholarship_discount.sd_is_amt = 1 THEN scholarship_discount.sd_amt ELSE NULL END AS highest_sd_amount'),
      //     'states.*',
      //     'full_countries.*',
      //     'categories.c_name as category_name',
      //     'sub_cats.*'
      // )
      // ->first();

    } catch (\Exception $e) {
        \Log::error($e);
    }

    return null;
   }
  /***********************************************************************
	 * Course Level
	 ***********************************************************************/
    public static function levelArr() {
        $arr = array();
        $arr[1] = 'Foundation';
        $arr[2] = 'International Pre-University';
        $arr[3] = 'Diploma';
        $arr[4] = 'Degree';
        $arr[5] = 'Master';
        $arr[6] = 'PHD';
        $arr[7] = 'K-12';
        $arr[8] = 'Certificate';
        $arr[9] = 'Post Basic Certificate';
        $arr[10] = 'Executive Diploma';
        $arr[11] = 'Ausmat';
        $arr[12] = 'A-Levels';
        $arr[13] = 'Professional Accountancy';
        return $arr;
    }

    /***********************************************************************
	 * Course Intake Months
	 ***********************************************************************/
    public static function monthArr() {
        $arr = array();
        $arr[1] = 'January';
        $arr[2] = 'February';
        $arr[3] = 'March';
        $arr[4] = 'April';
        $arr[5] = 'May';
        $arr[6] = 'June';
        $arr[7] = 'July';
        $arr[8] = 'August';
        $arr[9] = 'September';
        $arr[10] = 'October';
        $arr[11] = 'November';
        $arr[12] = 'December';
        return $arr;
    }

    /***********************************************************************
	 * Course Qualifications
	 ***********************************************************************/
    public static function qlevelArr($index = null)
	{
		  $arr[1] = 'SPM';
    	$arr[2] = 'IGCSE/O-Level';
    	$arr[3] = 'STPM';
    	$arr[4] = 'UEC';
    	$arr[5] = 'Foundation';
    	$arr[6] = 'Diploma';
    	$arr[7] = 'A-Level';
    	$arr[8] = 'IB Diploma';
    	$arr[9] = 'CPU/CIMP';
    	$arr[10] = 'Ausmat/SACEi/Atar';
      $arr[11] = 'K-12';
    	$arr[12] = 'Others';
    	$arr[13] = 'Master';
    	$arr[14] = 'PHD';
    	$arr[15] = 'SKM/Certificate';
    	$arr[16] = 'Pre-University';
    	$arr[17] = 'Degree';
    	$arr[18] = 'Monash University Foundation Year (MUFY)';
    	$arr[19] = 'SMA 3';
    	$arr[20] = 'India CBSE / HSC';
    	$arr[21] = 'Matriculation';

		if (isset($index)) {
			return $arr[$index];
		}

		return $arr;
	}

    /***********************************************************************
	 * Represent Level
	 ***********************************************************************/
    public static function rlevelArr()
	{
		  $arr[1] = 'State';
    	$arr[2] = 'National';
    	$arr[3] = 'Other';

		if (isset($index)) {
			return $arr[$index];
		}

		return $arr;
	}

  /***********************************************************************
	 * Is Malaysian
	 ***********************************************************************/
  public static function isMalaysianArr()
	{
		$arr[0] = 'Foreign';
    $arr[1] = 'Malaysian';
    $arr[2] = 'Both';

		if (isset($index)) {
			return $arr[$index];
		}

		return $arr;
	}

  /***********************************************************************
	 * Stream
	 ***********************************************************************/
  public static function streamArr()
	{
		$arr[0] = 'All Stream';
    $arr[1] = 'Science Stream';
    $arr[2] = 'Art Stream';
    $arr[3] = 'Sub Science';

		if (isset($index)) {
			return $arr[$index];
		}

		return $arr;
	}
}