<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Otp;
use App\Models\Seller;
use App\Models\Buyer;
use Illuminate\Http\Response;
use App\Helpers\Helper;
use Carbon\Carbon;
use Validator;
use DB;

class OtpController extends Controller
{
	/*
	Request OTP 
	Registerations time generate Otp
	*/
	public function requestOtp(Request $request){
		//vaildation check
         $validator = Validator::make($request->all(), 
                      [ 
                      'type' => 'required|numeric',
                      'country_code' => 'required',
                      'phone' => 'required|min:10|numeric',
					 ]); 

         if ($validator->fails()) {   
               return response()->json(['error'=>$validator->errors()], Response::HTTP_UNPROCESSABLE_ENTITY);  
			}

		//Requested mobile number's entry is already exists in table then get the OTP and send.
		$this->checkMobileExit($request);

		$optModel = Otp::generateOtpForMobile($request->country_code,$request->phone);
		
		Self::sendOtp($optModel->otp, $request->country_code,$request->phone);

		$response = $optModel->only(['country_code', 'phone',]); 
		$data = ['message' => trans('messages.OTP_SEND'),'data' => $response];

		return Helper::responseJson($data, Response::HTTP_OK);
	}


	/**
	 * Check Mobile already exits or not
	 * @param request
	 * @response true and flase
	 */
	protected function checkMobileExit($request){	
		///Seller already exits
		if($request->type == Seller::TYPE){			


		}else if($request->type == Buyer::TYPE){

		}
	}


	/**
	 * Send OTP 
	 * @param otp, country code, and mobile number
	 * @response data
	 */
	protected function sendOtp($otp, $country_code, $phone){		

		$message = trans('messages.OTP_SMS_SEND').$otp;
		
		// SMS send 
		$res = Helper::sendSMS($message, $country_code, $phone); 
		if(!$res){
			$data = ['message' => trans('messages.OTP_SMS_FAILED_SEND')];
			return Helper::responseJson($data, Response::HTTP_BAD_REQUEST);
		}	

	}

	/*
	Verify Otp
	Registerations time Otp Verify
	*/
	public function verifyOtp(Request $request){

		//vaildation check
        $validator = Validator::make($request->all(), 
                      [ 
                      'otp' => 'required|min:6|numeric',
                     ]);  
 
        if ($validator->fails()) {   
               return response()->json(['error'=>$validator->errors()], Response::HTTP_UNPROCESSABLE_ENTITY);  
        }
       
        $otpModel = Otp::where(['otp'=>$request->otp])->first();
       
        if($otpModel){
        	//update status
        	$otpModel->is_verify ='1';
			$otpModel->save();
			$response = $otpModel->only(['verification_hashcode']);
			
			$data = ['message' => trans('messages.OTP_VERIFIED'),'data' => $response];
			return Helper::responseJson($data, Response::HTTP_OK);

	    }else{
			$data = ['message' => trans('messages.OTP_NOT_MATCH')];
			return Helper::responseJson($data, Response::HTTP_NOT_FOUND);
	    }

	}


}