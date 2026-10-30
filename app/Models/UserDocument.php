<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Http\Request;

use Session;

class UserDocument extends Model
{
    public $timestamps = false;
	public $primaryKey = "ud_id";
	protected $table = "user_documents";

    protected $fillable = [
		'ud_user_id', 'ud_type', 'ud_document', 'ud_status', 'created_at', 'updated_at'
	];

	/***********************************************************************
	 * Get
	 ***********************************************************************/
	public static function getUserDocument($id)
	{
		try{
			return UserDocument::where([
				['ud_user_id', '=', $id],
				['ud_status', '!=', 99],
			])
			->get();
		} catch (\Exception $e) {
			\Log::error($e);
		}

		return null;
	}

	public static function getById($id)
	{
		try {
			if(isset($id)) {
				return UserDocument::where('ud_id', '=', $id)->first();
			}
		} catch (\Exception $e) {
			\Log::error($e);
		}

		return null;
	}
    /***********************************************************************
	 * Add
	 ***********************************************************************/
    public static function addDocument($r)
    {
		try{
			$id = Session::get('user_id');
			$dt = Gen::currTime();

			if($r->ic_front != null && $r->ic_front != 'undefined') {
				$doc = "ic-front-" . uniqid() . '.jpg';
				$add = UserDocument::create([
					'ud_user_id' => $id,
					'ud_type' => 1,
					'ud_document' => $doc,
					'ud_status' => 1,
					'created_at' => $dt,
					'updated_at' => $dt,
				]);

				if(isset($add)) {
					$r->ic_front->move(
                        public_path('/images/document'), $doc
                    );
				}
			}

			if($r->ic_back != null && $r->ic_back != 'undefined') {
				$doc = "ic-back-" . uniqid() . '.jpg';
				$add = UserDocument::create([
					'ud_user_id' => $id,
					'ud_type' => 2,
					'ud_document' => $doc,
					'ud_status' => 1,
					'created_at' => $dt,
					'updated_at' => $dt,
				]);

				if(isset($add)) {
					$r->ic_back->move(
                        public_path('/images/document'), $doc
                    );
				}
			}

			if(isset($r->result) && count($r->result) > 0) {
				foreach ($r->result as $re) {
					$extension = $re->extension();
					if($extension == 'pdf') {
						$doc = "result-" . uniqid() . '.pdf';
					}else {
						$doc = "result-" . uniqid() . '.jpg';
					}

					$add = UserDocument::create([
						'ud_user_id' => $id,
						'ud_type' => 3,
						'ud_document' => $doc,
						'ud_status' => 1,
						'created_at' => $dt,
						'updated_at' => $dt,
					]);

					if(isset($add)) {
						$re->move(
							public_path('/images/document'), $doc
						);
					}
				}
			}

			if(isset($r->resume) && count($r->resume) > 0) {
				foreach ($r->resume as $re) {
					$extension = $re->extension();
					if($extension == 'pdf') {
						$doc = "resume-" . uniqid() . '.pdf';
					}else {
						$doc = "resume-" . uniqid() . '.jpg';
					}

					$add = UserDocument::create([
						'ud_user_id' => $id,
						'ud_type' => 4,
						'ud_document' => $doc,
						'ud_status' => 1,
						'created_at' => $dt,
						'updated_at' => $dt,
					]);

					if(isset($add)) {
						$re->move(
							public_path('/images/document'), $doc
						);
					}
				}
			}

			if(isset($add)) {
				return $add;
			}

		} catch (\Exception $e) {
			\Log::error($e);
		}

		return false;
    }
    /***********************************************************************
	 * Type
	 ***********************************************************************/
    public static function typeArr()
	{
		$arr[1] = 'IC Front';
        $arr[2] = 'IC Back';
        $arr[3] = 'Academic Result';
        $arr[4] = 'Resume';

		if (isset($index)) {
			return $arr[$index];
		}

		return $arr;
	}

	public static function statusArr()
	{
		$arr[1] = 'Pending';
        $arr[2] = 'Approve';
        $arr[3] = 'Rejected';

		if (isset($index)) {
			return $arr[$index];
		}

		return $arr;
	}
}