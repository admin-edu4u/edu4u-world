<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Http\Request;

class IntScholarshipDiscount extends Model
{
    public $timestamps = false;
	public $primaryKey = "sd_id";
	protected $table = "int_scholarship_discount";

    protected $fillable = [
		'sd_s_id', 'sd_level_id', 'sd_count', 'sd_grade', 'sd_is_above', 'sd_amt', 'sd_percentage', 'sd_is_amt', 'sd_is_manual', 'sd_desc', 'sd_status', 'created_at', 'updated_at'
	];

    /***********************************************************************
	 * Get
	 ***********************************************************************/
    public static function getById($id)
    {
      try{
        if(isset($id)){
          return IntScholarshipDiscount::where('sd_id', '=', $id)->first();
        }
      }catch (\Exception $e) {
        \Log::error($e);
      }
      return null;
    }

    public static function getByScholarshipId($id)
    {
      try{
        if(isset($id)){
          return IntScholarshipDiscount::where('sd_s_id', '=', $id)->get();
        }
      }catch (\Exception $e) {
        \Log::error($e);
      }
      return null;
    }

    /***********************************************************************
	 * Add
	 ***********************************************************************/
    public static function addK12ScholarshipDiscount(Request $r, $id) {
        try {
            $dt = Gen::currTime();
        
            if ($r->ismanual == 0) {
                if ($r->isamount == 1) {
                    foreach ($r->input() as $key => $value) {
                        if (strpos($key, 'level') !== false) {
                            $levelId = preg_replace('/[^0-9]/', '', $key);
                            $sd_level_id = $r->input('level' . $levelId);
                            $countArray = $r->input('count' . $levelId);
                            $gradeArray = $r->input('grade' . $levelId);
                            // $isAboveArray = $r->input('isabove' . $levelId);
                            $amountArray = $r->input('amount' . $levelId);
                            $count = count($countArray);
                    
                            for ($i = 0; $i < $count; $i++) {
                                $add = IntScholarshipDiscount::create([
                                    'sd_s_id' => $id,
                                    'sd_level_id' => $sd_level_id,
                                    'sd_count' => $countArray[$i],
                                    'sd_grade' => $gradeArray[$i],
                                    'sd_is_above' => 0,
                                    'sd_amt' => $amountArray[$i],
                                    'sd_is_amt' => $r->isamount,
                                    'sd_is_manual' => $r->ismanual,
                                    'sd_desc' => $r->desc,
                                    'sd_status' => 1,
                                    'created_at' => $dt,
                                    'updated_at' => $dt,
                                ]);
                            }
                        }
                    }
                } else if ($r->isamount == 0) {
                    foreach ($r->input() as $key => $value) {
                        if (strpos($key, 'plevel') !== false) {
                            $levelId = preg_replace('/[^0-9]/', '', $key);
                            $sd_level_id = $r->input('plevel' . $levelId);
                            $countArray = $r->input('pcount' . $levelId);
                            $gradeArray = $r->input('pgrade' . $levelId);
                            $isAboveArray = $r->input('pisabove' . $levelId);
                            $percentageArray = $r->input('pamount' . $levelId);
                            $count = count($countArray);

                            for ($i = 0; $i < $count; $i++) {
                                $add = IntScholarshipDiscount::create([
                                    'sd_s_id' => $id,
                                    'sd_level_id' => $sd_level_id,
                                    'sd_count' => $countArray[$i],
                                    'sd_grade' => $gradeArray[$i],
                                    'sd_is_above' => $isAboveArray[$i],
                                    'sd_percentage' => $percentageArray[$i],
                                    'sd_is_amt' => $r->isamount,
                                    'sd_is_manual' => $r->ismanual,
                                    'sd_desc' => $r->desc,
                                    'sd_status' => 1,
                                    'created_at' => $dt,
                                    'updated_at' => $dt,
                                ]);
                            }
                        }
                    }
                }
            } else if ($r->ismanual == 1) {

                if ($r->isamountm == 1) {
                    $add = IntScholarshipDiscount::create([
                        'sd_s_id' => $id,
                        'sd_is_amt' => $r->isamountm,
                        'sd_amt' => $r->mamount,
                        'sd_is_manual' => $r->ismanual,
                        'sd_desc' => $r->desc,
                        'sd_status' => 1,
                        'created_at' => $dt,
                        'updated_at' => $dt,
                    ]);
                } else if ($r->isamountm == 0){
                    $add = IntScholarshipDiscount::create([
                        'sd_s_id' => $id,
                        'sd_is_amt' => $r->isamountm,
                        'sd_percentage' => $r->mpercentage,
                        'sd_is_manual' => $r->ismanual,
                        'sd_desc' => $r->desc,
                        'sd_status' => 1,
                        'created_at' => $dt,
                        'updated_at' => $dt,
                    ]);
                }
            
            }
            return $add;
        } catch (\Exception $e) {
            \Log::error($e);
        }
        return null;
    }

    /***********************************************************************
	 * Edit Update
	 ***********************************************************************/
    public static function editK12ScholarshipDiscount(Request $r, $id){
        try {
            $dt = Gen::currTime();
    
            if ($r->ismanual == 0) {
                if ($r->isamount == 1) {
                    $deletedIds = explode(',', $r->input('deletedRow'));
                    IntScholarshipDiscount::whereIn('sd_id', $deletedIds)->update(['sd_status' => 99]);

                    foreach ($r->input() as $key => $value) {
                        if (strpos($key, 'newlevel') !== false) {
                            $newlevelId = preg_replace('/[^0-9]/', '', $key);
                            $new_sd_level_id = $r->input('newlevel' . $newlevelId);
                            $newcountArray = $r->input('newcount' . $newlevelId);
                            $newgradeArray = $r->input('newgrade' . $newlevelId);
                            $newisAboveArray = $r->input('newisabove' . $newlevelId);
                            $newamountArray = $r->input('newamount' . $newlevelId);
                            $newcount = count($newcountArray);
                
                            for ($i = 0; $i < $newcount; $i++) {
                                IntScholarshipDiscount::create([
                                    'sd_s_id' => $id,
                                    'sd_level_id' => $new_sd_level_id,
                                    'sd_count' => $newcountArray[$i],
                                    'sd_grade' => $newgradeArray[$i],
                                    'sd_is_above' => $newisAboveArray[$i],
                                    'sd_amt' => $newamountArray[$i],
                                    'sd_is_amt' => $r->isamount,
                                    'sd_is_manual' => $r->ismanual,
                                    'sd_desc' => $r->desc,
                                    'sd_status' => 1,
                                    'created_at' => $dt,
                                    'updated_at' => $dt,
                                ]);
                            }
                        }
                    }
                
                    $processedLevels = [];
                    foreach ($r->input() as $key => $value) {
                        if (strpos($key, 'level') !== false) {
                            // Extract the level ID from the input key
                            $levelId = preg_replace('/[^0-9]/', '', $key);

                            if (in_array($levelId, $processedLevels)) {
                                continue;
                            }

                            $existingDiscounts = IntScholarshipDiscount::where('sd_s_id', $id)->where('sd_status', 1)->get();
                
                            $countArray = $r->input('count' . $levelId);
                            $gradeArray = $r->input('grade' . $levelId);
                            $isAboveArray = $r->input('isabove' . $levelId);
                            $amountArray = $r->input('amount' . $levelId);
                
                            $index = 0;
                            foreach ($existingDiscounts as $existingDiscount) {
                                if (in_array($existingDiscount->sd_id, explode(',', $r->input('updateRow')))) {
                                    if (isset($countArray[$index], $gradeArray[$index], $isAboveArray[$index], $amountArray[$index])) {
                                        $existingDiscount->update([
                                            'sd_level_id' => $r->input('level' . $levelId),
                                            'sd_count' => $countArray[$index],
                                            'sd_grade' => $gradeArray[$index],
                                            'sd_is_above' => $isAboveArray[$index],
                                            'sd_amt' => $amountArray[$index],
                                            'sd_is_amt' => $r->isamount,
                                            'sd_is_manual' => $r->ismanual,
                                            'updated_at' => $dt,
                                        ]);
                                    }
                                    $index++;
                                }
                            }                          
            
                            if ($r->has('newcount' . $levelId) && !empty($r->input('newcount' . $levelId))) {
                                $newcountArray = $r->input('newcount' . $levelId);
                                $newgradeArray = $r->input('newgrade' . $levelId);
                                $newisAboveArray = $r->input('newisabove' . $levelId);
                                $newamountArray = $r->input('newamount' . $levelId);
                                $newcount = count($newcountArray);
                
                                for ($i = 0; $i < $newcount; $i++) {
                                    IntScholarshipDiscount::create([
                                        'sd_s_id' => $id,
                                        'sd_level_id' => $r->input('level' . $levelId),
                                        'sd_count' => $newcountArray[$i],
                                        'sd_grade' => $newgradeArray[$i],
                                        'sd_is_above' => $newisAboveArray[$i],
                                        'sd_amt' => $newamountArray[$i],
                                        'sd_is_amt' => $r->isamount,
                                        'sd_is_manual' => $r->ismanual,
                                        'sd_desc' => $r->desc,
                                        'sd_status' => 1,
                                        'created_at' => $dt,
                                        'updated_at' => $dt,
                                    ]);
                                }
                                $processedLevels[] = $levelId;
                            }
                        }
                    }
                }
                              
                else if ($r->isamount == 0) {
                    $deletedIds = explode(',', $r->input('deletedRow'));
                    IntScholarshipDiscount::whereIn('sd_id', $deletedIds)->update(['sd_status' => 99]);

                    foreach ($r->input() as $key => $value) {
                        if (strpos($key, 'newplevel') !== false) {
                            $newlevelId = preg_replace('/[^0-9]/', '', $key);
                            $new_sd_level_id = $r->input('newplevel' . $newlevelId);
                            $newcountArray = $r->input('newpcount' . $newlevelId);
                            $newgradeArray = $r->input('newpgrade' . $newlevelId);
                            $newisAboveArray = $r->input('newpisabove' . $newlevelId);
                            $newamountArray = $r->input('newpamount' . $newlevelId);
                            $newcount = count($newcountArray);
                
                            for ($i = 0; $i < $newcount; $i++) {
                                IntScholarshipDiscount::create([
                                    'sd_s_id' => $id,
                                    'sd_level_id' => $new_sd_level_id,
                                    'sd_count' => $newcountArray[$i],
                                    'sd_grade' => $newgradeArray[$i],
                                    'sd_is_above' => $newisAboveArray[$i],
                                    'sd_percentage' => $newamountArray[$i],
                                    'sd_is_amt' => $r->isamount,
                                    'sd_is_manual' => $r->ismanual,
                                    'sd_desc' => $r->desc,
                                    'sd_status' => 1,
                                    'created_at' => $dt,
                                    'updated_at' => $dt,
                                ]);
                            }
                        }
                    }
                
                    $processedLevels = [];
                    foreach ($r->input() as $key => $value) {
                        if (strpos($key, 'plevel') !== false) {
                            // Extract the level ID from the input key
                            $levelId = preg_replace('/[^0-9]/', '', $key);

                            if (in_array($levelId, $processedLevels)) {
                                continue;
                            }

                            $existingDiscounts = IntScholarshipDiscount::where('sd_s_id', $id)->where('sd_status', 1)->get();
                
                            $countArray = $r->input('pcount' . $levelId);
                            $gradeArray = $r->input('pgrade' . $levelId);
                            $isAboveArray = $r->input('pisabove' . $levelId);
                            $amountArray = $r->input('pamount' . $levelId);
                
                            foreach ($existingDiscounts as $existingDiscount) {
                                if (in_array($existingDiscount->sd_id, explode(',', $r->input('updateRow')))) {
                                    $index = array_search($existingDiscount->sd_id, explode(',', $r->input('updateRow')));
                                    if ($index !== false && isset($countArray[$index], $gradeArray[$index], $isAboveArray[$index], $amountArray[$index])) {
                                        $existingDiscount->update([
                                            'sd_level' => $r->input('plevel' . $levelId),
                                            'sd_count' => $countArray[$index],
                                            'sd_grade' => $gradeArray[$index],
                                            'sd_is_above' => $isAboveArray[$index],
                                            'sd_percentage' => $amountArray[$index],
                                            'sd_is_amt' => $r->isamount,
                                            'sd_is_manual' => $r->sd_is_manual,
                                            'updated_at' => $dt,
                                        ]);
                                    }
                                }
                            }                            
            
                            if ($r->has('newpcount' . $levelId) && !empty($r->input('newpcount' . $levelId))) {
                                $newcountArray = $r->input('newpcount' . $levelId);
                                $newgradeArray = $r->input('newpgrade' . $levelId);
                                $newisAboveArray = $r->input('newpisabove' . $levelId);
                                $newamountArray = $r->input('newpamount' . $levelId);
                                $newcount = count($newcountArray);
                
                                for ($i = 0; $i < $newcount; $i++) {
                                    IntScholarshipDiscount::create([
                                        'sd_s_id' => $id,
                                        'sd_level_id' => $r->input('plevel' . $levelId),
                                        'sd_count' => $newcountArray[$i],
                                        'sd_grade' => $newgradeArray[$i],
                                        'sd_is_above' => $newisAboveArray[$i],
                                        'sd_percentage' => $newamountArray[$i],
                                        'sd_is_amt' => $r->isamount,
                                        'sd_is_manual' => $r->ismanual,
                                        'sd_desc' => $r->desc,
                                        'sd_status' => 1,
                                        'created_at' => $dt,
                                        'updated_at' => $dt,
                                    ]);
                                }
                                $processedLevels[] = $levelId;
                            }
                        }
                    }
                }
        } else if ($r->ismanual == 1) {
            if ($r->isamountm == 1) {
                
                $sd = IntScholarshipDiscount::where('sd_s_id', $id)->where('sd_status', 1)->first();
                $sd->sd_is_amt = $r->isamountm;
                $sd->sd_amt = $r->mamount;
                $sd->sd_is_manual = $r->ismanual;
                $sd->updated_at = $dt;

                if ($r->has('editdesc') && $r->editdesc !== null) {
                    $sd->sd_desc = $r->editdesc;
                }

            } else if ($r->isamountm == 0){

                $sd = IntScholarshipDiscount::where('sd_s_id', $id)->where('sd_status', 1)->first();
                $sd->sd_is_amt = $r->isamountm;
                $sd->sd_percentage = $r->mpercentage;
                $sd->sd_is_manual = $r->ismanual;
                $sd->updated_at = $dt;

                if ($r->has('editdesc') && $r->editdesc !== null) {
                    $sd->sd_desc = $r->editdesc;
                }

            }
                $sd->save();
                return $sd;
        }
        } catch (\Exception $e) {
            \Log::error($e);
        }
        return null;
    }

}