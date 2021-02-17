<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Carbon\Carbon;


class Otp extends Model
{
    use SoftDeletes;
   
     protected $fillable = [
        'opt','phone','country_code',
    ];

	/*
    *Generate OTP
    */
    public static function generateOtpForMobile($country_code,$phone)
    {
		//Softdelete old OTP 
		$otpModel = Otp::where(['phone'=>$phone,'is_verify'=> '0'])->delete();

		$newOtp = rand(100000, 999999);		
		$now = Carbon::now();
        $expired = $now->addMinutes(15);
        $verification_hashcode = md5($newOtp);

        $optModel = new Otp();
        $optModel->country_code = $country_code;
        $optModel->phone = $phone;
        $optModel->otp = $newOtp;
        $optModel->verification_hashcode = $verification_hashcode;
        $optModel->expired = $expired;
		$optModel->save();
        return $optModel;
    }

    /*
    *Generate OTP
    */
    public static function generateOtpByEmail($email)
    {
		//Softdelete old OTP 
		$otpModel = Otp::where(['email'=>$email,'is_verify'=> '0'])->delete();

		$newOtp = rand(100000, 999999);		
		$now = Carbon::now();
        $expired = $now->addMinutes(15);
        $verification_hashcode = md5($newOtp);

        $optModel = new Otp();
        $optModel->email = $email;
		$optModel->otp = $newOtp;
		$optModel->verification_hashcode = $verification_hashcode;
        $optModel->expired = $expired;
		$optModel->save();
        return $optModel;
    }
}
