<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Http\Request;

class CourseFee extends Model
{
    public $timestamps = false;
	public $primaryKey = "cf_id";
	protected $table = "course_fees";

    /**
	 * The attributes that are mass assignable.
	 *
	 * @var array
	 */
	protected $fillable = [
		'cf_c_id', 'cf_type', 'cf_name', 'cf_amount', 'cf_amount_bumi', 'cf_amount_international', 'cf_payment_frequency', 'cf_status', 'created_at', 'updated_at'
	];

    /***********************************************************************
     * Get
     ***********************************************************************/
    public static function getAllFee($id)
    {
        try{
            if(isset($id)) {
                return CourseFee::where([
                    ['cf_c_id', '=', $id],
                    ['cf_type', '=', 1],
                    ['cf_status', '=', 1],
                ])
                ->get();
            }
        } catch (\Exception $e) {
          \Log::error($e);
        }
        return null;
    }

    public static function getAllDeposit($id)
    {
        try{
            if(isset($id)) {
                return CourseFee::where([
                    ['cf_c_id', '=', $id],
                    ['cf_type', '=', 2],
                    ['cf_status', '=', 1],
                ])
                ->get();
            }
        } catch (\Exception $e) {
          \Log::error($e);
        }
        return null;
    }

    public static function totalFee($id, $type = null)
    {
        try{
            if(isset($id)) {
                $c = CourseFee::where([
                    ['cf_c_id', '=', $id],
                    ['cf_type', '=', 1],
                    ['cf_status', '=', 1],
                ]);

                if(isset($type)) {
                    if($type == 1) {
                        $c->selectRaw('COALESCE(SUM(cf_amount), 0) as total');
                    } else if($type == 2) {
                        $c->selectRaw('COALESCE(SUM(cf_amount_bumi), 0) as total');
                    } else if($type == 3) {
                        $c->selectRaw('COALESCE(SUM(cf_amount_international), 0) as total');
                    }
                } else{
                    $c->selectRaw('COALESCE(SUM(cf_amount), 0) as total');
                }

                $c->first();

                if(isset($c)) return $c->total;
            }
        } catch (\Exception $e) {
            \Log::error($e);
        }
        return null;
    }

    public static function getById($id)
    {
      try{
        if(isset($id)){
          return CourseFee::where('cf_c_id', '=', $id)->get();    
        }
      }catch (\Exception $e) {
        \Log::error($e);
      }
      return null;
    }

    /***********************************************************************
	 * Add
	 ***********************************************************************/
    public static function addExtraFees(Request $r, $id)
    {
        try{
            $dt = Gen::currTime();

            $feesIndex = 1;
            while ($r->has("extrafeesname$feesIndex")) {
                $cf_type = $r->input("extrafeestype$feesIndex");
                $cf_name = $r->input("extrafeesname$feesIndex");
                $cf_amount = $r->input("extrafeesamount$feesIndex");
                $cf_amount_bumi = $r->input("extrafeesamountbumi$feesIndex");
                $cf_amount_international = $r->input("extrafeesamountfore$feesIndex");
                $cf_payment_frequency = $r->input("extrafeesfrequency$feesIndex");

                CourseFee::create([
                    'cf_c_id' => $id,
                    'cf_type' => $cf_type,
                    'cf_name' => $cf_name,
                    'cf_amount' => $cf_amount,
                    'cf_amount_bumi' => $cf_amount_bumi,
                    'cf_amount_international' => $cf_amount_international,
                    'cf_payment_frequency' => $cf_payment_frequency,
                    'cf_status' => 1,
                    'created_at' => $dt,
                    'updated_at' => $dt,
                ]);
    
                $feesIndex++;
            }
    
            return true;

        } catch (\Exception $e) {
            \Log::error($e);
            return false;
        }
    }

     /***********************************************************************
	 * Edit / Update
	 ***********************************************************************/
    public static function editExtraFees(Request $r, $id){
        try {
            $dt = Gen::currTime();
        
            $courseFees = CourseFee::where('cf_c_id', $id)->where('cf_status', 1)->get();
        
            // Count how many "extrafeesname" fields have been posted
            $postedFeesCount = 0;
            foreach ($r->all() as $key => $value) {
                if (preg_match('/^extrafeesname[0-9]+$/', $key)) {
                    $postedFeesCount++;
                }
            }
        
            // Update the extra fees rows to status 99 if the posted count is less than the existing count
            if ($postedFeesCount < count($courseFees)) {
                for ($i = $postedFeesCount + 1; $i <= count($courseFees); $i++) {
                    $remainingFee = $courseFees->get($i - 1);
                    if ($remainingFee) {
                        $remainingFee->cf_status = 99;
                        $remainingFee->save();
                    }
                }
            }
        
            // Update the existing course fees
            $feesIndex = 1;
            foreach ($courseFees as $cf) {
                $cf->cf_type = $r->input("extrafeestype$feesIndex");
                $cf->cf_name = $r->input("extrafeesname$feesIndex");
                $cf->cf_amount = $r->input("extrafeesamount$feesIndex");
                $cf->cf_amount_bumi = $r->input("extrafeesamountbumi$feesIndex");
                $cf->cf_amount_international = $r->input("extrafeesamountfore$feesIndex");
                $cf->cf_payment_frequency = $r->input("extrafeesfrequency$feesIndex");
                $cf->updated_at = $dt;
                $cf->save();
        
                $feesIndex++;
            }
        
            // Create new course fees for additional fees posted
            if ($postedFeesCount > count($courseFees)) {
                $newfeesIndex = $feesIndex;
                while ($r->has("extrafeestype$newfeesIndex")) {
                    // Process each new qualification
                    $cf_type = $r->input("extrafeestype$newfeesIndex");
                    $cf_name = $r->input("extrafeesname$newfeesIndex");
                    $cf_amount = $r->input("extrafeesamount$newfeesIndex");
                    $cf_amount_bumi = $r->input("extrafeesamountbumi$newfeesIndex");
                    $cf_amount_international = $r->input("extrafeesamountfore$newfeesIndex");
                    $cf_payment_frequency = $r->input("extrafeesfrequency$newfeesIndex");
        
                    CourseFee::create([
                        'cf_c_id' => $id,
                        'cf_type' => $cf_type,
                        'cf_name' => $cf_name,
                        'cf_amount' => $cf_amount,
                        'cf_amount_bumi' => $cf_amount_bumi,
                        'cf_amount_international' => $cf_amount_international,
                        'cf_payment_frequency' => $cf_payment_frequency,
                        'cg_status' => 1,
                        'created_at' => $dt,
                        'updated_at' => $dt,
                    ]);
        
                    $newfeesIndex++;
                }
            }
        
            return true; 
        } catch (\Exception $e) {
            \Log::error($e);
        }
        
        return null;
        
        // try {
        //     $dt = Gen::currTime();

        //     $courseFees = CourseFee::where('cf_c_id', $id)->where('cf_status', 1)->get();

        //     // Count how many "extrafeesname" fields have been posted
        //     $postedFeesCount = 0;
        //     foreach ($r->all() as $key => $value) {
        //         if (preg_match('/^extrafeesname[0-9]+$/', $key)) {
        //             $postedFeesCount++;
        //         }
        //     }

        //     //  dd($postedFeesCount, count($courseFees));

        //     $feesIndex = 1;
        //     foreach ($courseFees as $cf) {
        //         $cf->cf_type = $r->input("extrafeestype$feesIndex");
        //         $cf->cf_name = $r->input("extrafeesname$feesIndex");
        //         $cf->cf_amount = $r->input("extrafeesamount$feesIndex");
        //         $cf->cf_amount_bumi = $r->input("extrafeesamountbumi$feesIndex");
        //         $cf->cf_amount_international = $r->input("extrafeesamountfore$feesIndex");
        //         $cf->cf_payment_frequency = $r->input("extrafeesfrequency$feesIndex");
        //         $cf->updated_at = $dt;
        //         $cf->save();

        //         $feesIndex++;
        //     }

        //     if ($postedFeesCount < count($courseFees)) {
        //         for ($i = $feesIndex; $i <= count($courseFees); $i++) {
        //             $remainingFee = $courseFees->get($i - 1);
        //             if ($remainingFee) {
        //                 $remainingFee->cf_status = 99;
        //                 $remainingFee->save();
        //             }
        //         }
        //     } else if ($postedFeesCount > count($courseFees)) {
        //         $newfeesIndex = $feesIndex;
        //         while ($r->has("extrafeestype$newfeesIndex")) {
        //             // Process each new qualification
        //             $cf_type = $r->input("extrafeestype$newfeesIndex");
        //             $cf_name = $r->input("extrafeesname$newfeesIndex");
        //             $cf_amount = $r->input("extrafeesamount$newfeesIndex");
        //             $cf_amount_bumi = $r->input("extrafeesamountbumi$newfeesIndex");
        //             $cf_amount_international = $r->input("extrafeesamountfore$newfeesIndex");
        //             $cf_payment_frequency = $r->input("extrafeesfrequency$newfeesIndex");

        //             CourseFee::create([
        //                 'cf_c_id' => $id,
        //                 'cf_type' => $cf_type,
        //                 'cf_name' => $cf_name,
        //                 'cf_amount' => $cf_amount,
        //                 'cf_amount_bumi' => $cf_amount_bumi,
        //                 'cf_amount_international' => $cf_amount_international,
        //                 'cf_payment_frequency' => $cf_payment_frequency,
        //                 'cg_status' => 1,
        //                 'created_at' => $dt,
        //                 'updated_at' => $dt,
        //             ]);

        //             $newfeesIndex++;
        //         }
        //     }

        //     return true; 
        // } catch (\Exception $e) {
        //     \Log::error($e);
        // }
    
        // return null;
    }


    /***********************************************************************
     * Array
     ***********************************************************************/
    public static function typeArr($index = NULL)
	{
		$arr[1] = 'Fee';
    	$arr[2] = 'Deposit';

		if (isset($index)) {
			return $arr[$index];
		}

		return $arr;
	}

    public static function frequencyArr($index = NULL)
	{
		$arr[1] = 'Monthly';
    	$arr[2] = 'Yearly';
    	$arr[3] = 'One Time';
    	$arr[4] = 'Per Semester';
    	$arr[5] = 'Per Science Subject';

		if (isset($index)) {
			return $arr[$index];
		}

		return $arr;
	}

}