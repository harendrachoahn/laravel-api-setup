<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Http\Response;
use App\Helpers\Helper;
use App\Models\Seller;
use App\Models\Buyer;
use App\Models\Otp;
use Illuminate\Support\Str;
use Validator;
use Carbon\Carbon;



class ForgotPasswordController extends Controller
{
    /**
     * Send mail
     */
    public function restPasswordRequest(Request $request)
    {    
        //vaildation check
        $validator = Validator::make($request->all(), [ 'email_or_mobile' => 'required',]);  

        if ($validator->fails()) {   
                return response()->json(['error'=>$validator->errors()], Response::HTTP_UNPROCESSABLE_ENTITY);  
        }

        // If email or phone does not exist
        if (!Self::validEmailOrPhone($request->email_or_mobile)) {

            $additionalData = ['code' => Response::HTTP_NOT_FOUND, 'messages' => 'Does not exist.'];
            return Helper::responseJson($additionalData, Response::HTTP_NOT_FOUND);  

        } else { 
            $response = Self::checkEmailMobileAndSendOtp($request->country_code, $request->email_or_mobile);
            return $response;
        }
    }

    // Check valid email and phone number 
    public function validEmailOrPhone($email_or_mobile)
    {
        $seller = Seller::where('email','=',$email_or_mobile)->orWhere('phone','=', $email_or_mobile)->first();
        $buyer = Buyer::where('email','=',$email_or_mobile)->orWhere('phone','=', $email_or_mobile)->first();
        return  !$seller && !$buyer ? false : true;   
    }
    
    // Check valid email and checkRequestdataphone number 
    public function checkEmailMobileAndSendOtp($country_code, $email_or_mobile)
    {
        //send email
        if(Helper::isValidEmail($email_or_mobile)){
            // If email exists
            $res = $this->sendOtpMail($email_or_mobile);

        }else if(Helper::isVaildMobile($email_or_mobile)){

            $optModel = Otp::generateOtpForMobile($country_code,$email_or_mobile);
            $response = $optModel->only(['country_code', 'phone',]); 

            $message = trans('messages.OTP_SMS_SEND'). $optModel->otp;		
            // SMS send 
            $res = Helper::sendSMS($message, $country_code, $email_or_mobile); 
            
        }else{
            $data = ['message' => trans('messages.INVAILD_INPUT')];
            return Helper::responseJson($data, Response::HTTP_BAD_REQUEST);
        }

        if(!$res){
            $data = ['message' => trans('messages.OTP_SMS_FAILED_SEND')];
            return Helper::responseJson($data, Response::HTTP_BAD_REQUEST);            
        }

        $data = ['message' => trans('messages.OTP_SEND')];
        return Helper::responseJson($data, Response::HTTP_OK);

    }

    /***
     * Send Otp to mail
     * 
     */    

    public function sendOtpMail($to)
    {
        $optModel = Otp::generateOtpByEmail($to);
        
        $message = trans('messages.OTP_SMS_SEND').$optModel->otp;
        $subject = "Otp Verification.";	

        $data = array('otp' => $message);
        return Helper::sendEmail($to, $subject, $data, 'otp_varification');
    }


    /***
     * set password
     * 
     */
    public function passwordResetProcess(Request $request)
    {
           // Validation Check 
           $validation_data = [
            'email_or_mobile' => 'required',
            'verification_hashcode' => 'required',
            'password' => 'required|string|min:8',  
            'c_password' => 'required|same:password', 
        ];

        $response = Helper::validationCheck($request,$validation_data);
        if(!is_bool($response)) {
            return $response;
        }

        return $this->updatePasswordRow($request)->count() > 0 ? $this->resetPassword($request) : $this->tokenNotFoundError();
    }

    // Verify if token is valid
    private function updatePasswordRow($request)
    {
        return Otp::where(['verification_hashcode' => $request->verification_hashcode,'is_verify' => 1]);
    }

    // Token not found response
    private function tokenNotFoundError()
    {
        $data = ['message' => trans('messages.VERIFICATION_NOT_MATCH')];
        return Helper::responseJson($data, Response::HTTP_UNPROCESSABLE_ENTITY);
    }

    // Reset password
    private function resetPassword($request)
    {
        $email_or_mobile = $request->email_or_mobile;        
        // find email or phone
        $seller = Seller::where('email','=',$email_or_mobile)->orWhere('phone','=', $email_or_mobile)->first();
        $buyer = Buyer::where('email','=',$email_or_mobile)->orWhere('phone','=', $email_or_mobile)->first();

        $userData = $buyer <> null ? $buyer : $seller; 
        // update password
        $userData->update([
            'password' => bcrypt($request->password)
        ]);
        // remove verification data from db
        $this->updatePasswordRow($request)->delete();
       
        // reset password response
        $data = ['message' => trans('messages.PASSWORD_UPDATED')];
        return Helper::responseJson($data, Response::HTTP_OK);
    }
}
