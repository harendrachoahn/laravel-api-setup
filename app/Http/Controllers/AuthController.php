<?php

namespace App\Http\Controllers;

use App\Models\Seller;
use App\Models\Buyer;
use Illuminate\Http\Request;
use App\Helpers\Helper;
use Illuminate\Support\Facades\Validator;
use Illuminate\Http\Response;
use Exception;
use JWTAuth;

class AuthController extends Controller
{

    /**
     * Create a new AuthController instance.
     *
     * @return void
     */
    public function __construct()
    {   
        $this->middleware('auth:api', ['except' => ['login', 'register']]);
    }

    /**
     * Common login for Buyer & Seller
     * @param Request $request
     * @return Json output
     */
    public function login(Request $request)
    {
        // Validation Check
        $validation_data = [
            'email_or_mobile' => 'required',
            'password' => 'required|string|min:8'
        ];

        $response = Helper::validationCheck($request, $validation_data);
        if (!is_bool($response)) {
            return $response;
        }

        $seller_res = self::checkSellerExist($request->email_or_mobile);
        $buyer_res = self::checkBuyerExist($request->email_or_mobile);



        if(($seller_res && $seller_res['type'] == Seller::TYPE) && !empty($seller_res['email'])){
            // get password from request and email from model
            $credentials = array(
                'email' => $seller_res['email'],
                'password' => $request->password
                );
        }
        elseif(($buyer_res && $buyer_res['type'] == Buyer::TYPE) && !empty($buyer_res['email'])){  
            // get password from request and email from model
            $credentials = array(
                'email' => $buyer_res['email'],
                'password' => $request->password
                );                 
        }
        else{
            self::InvalidCredential();
        }
        

        $data =  (!is_bool($seller_res) && $seller_res['type'] == Seller::TYPE ? self::sellerLogIn($credentials) : self::InvalidCredential());
        $data = (!is_bool($buyer_res) && $buyer_res['type'] == Buyer::TYPE  ? self::buyerLogIn($credentials) : $data);

        return $data;
    }

    /**
     * Invalid Credential Response
     */
    private static function InvalidCredential()
    {
        $data = ['message' => trans('messages.INVALID_CREDENTIALS')];
        return Helper::responseJson($data, Response::HTTP_BAD_REQUEST);
    }

    /**
     * Check email exist in Seller 
     */
    private static function checkSellerExist($email_or_mobile)
    {
        $seller = Seller::where('email','=',$email_or_mobile)->orWhere('phone','=', $email_or_mobile)->first();          

        if (!empty($seller)) {
            $seller['type'] = Seller::TYPE;
            return $seller;
        }
        return false;
    }

    /**
     * Check email exist in Buyer
     */
    private static function checkBuyerExist($email_or_mobile)
    {
        $buyer = Buyer::where('email','=',$email_or_mobile)->orWhere('phone','=', $email_or_mobile)->first();

        if (!empty($buyer)) {
            $buyer['type'] = Buyer::TYPE;
            return $buyer;
        }
        return false;
    }

    /**
     * Seller logIn
     */
    private static function sellerLogIn($credentials)
    {        
        config()->set( 'auth.defaults.guard', 'sellers');

        \Config::set('jwt.sellers', 'App\Models\Seller');
        \Config::set('auth.providers.sellers.model', \App\Models\Seller::class);

        if (!$token = JWTAuth::attempt($credentials)) {  
            $data = ['message' => 'Invalid Credentials'];
            return Helper::responseJson($data, Response::HTTP_BAD_REQUEST);
        }

        $user = JWTAuth::setToken($token)->toUser();
        $user->user_type = 'Seller';
        $data = ['message' => 'Seller Logged in Successfuly!', 'data' => $user, 'token' => $token];
        return Helper::responseJson($data, Response::HTTP_OK);
    }

    /**
     * Buyer login
     */
    private static function buyerLogIn($credentials)
    {
       config()->set( 'auth.defaults.guard', 'buyers');

       \Config::set('jwt.buyers', 'App\Models\Buyer');
       \Config::set('auth.providers.buyers.model', \App\Models\Buyer::class);        

        if (!$token = JWTAuth::attempt($credentials)) {
            $data = ['message' => 'Invalid Credentials'];
            return Helper::responseJson($data, Response::HTTP_BAD_REQUEST);
        }

        $user = JWTAuth::setToken($token)->toUser();
        
        $data = ['message' => 'Login Successfuly!', 'data' => $user, 'token' => $token];
        return Helper::responseJson($data, Response::HTTP_OK);
    }


    /**
     * ********************************************************
     *  method for Destroy token for logout
     * ********************************************************
     *
     * Inputs:
     * @param token  
     *
     * Output:
     * 		return user data
     */

    public function logout(Request $request)
    {
        $token = JWTAuth::getToken();
        JWTAuth::invalidate($token);
        return response()->json(['success' => true, 'message' =>  'Logout Successfully']);
    }


    /**
     * Refresh a token.
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function refresh()
    {
        $token = JWTAuth::refresh(JWTAuth::getToken());

        $data = ['message' => 'Token refreshed Successfuly!', 'token' => $token];
        return Helper::responseJson($data, Response::HTTP_OK);
    }
}
