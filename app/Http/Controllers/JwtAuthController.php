<?php

namespace App\Http\Controllers;
 
use JWTAuth;
use Validator;
use App\Models\Seller;
use App\Models\Buyer;
use Illuminate\Http\Request;
use App\Http\Requests\RegisterAuthRequest;
use Tymon\JWTAuth\Exceptions\JWTException;
use Illuminate\Http\Response;
 
class JwtAuthController extends Controller
{
    public $token = true;

    /**
     * Create a new AuthController instance.
     *
     * @return void
     */
    public function __construct()
    {   
       // $this->middleware('auth:api', ['except' => ['login', 'register']]);
    }

    /*
    *Registeration function for Seller and Buyer
    */
    public function register(Request $request)
    {
 
         $validator = Validator::make($request->all(), 
                      [ 
                      'first_name' => 'required',
                      'last_name' => 'required',
                      'email' => 'required|email',
                      'type' => 'required',
                      'password' => 'required|string|min:8',  
                      'c_password' => 'required|same:password', 
                     ]);
        	if ($validator->fails()) {  
               return response()->json(['error'=>$validator->errors()], 401);  
            }

        /*  Create Seller */
        if($request->type == Seller::TYPE){ 

        $data = $request->only('phone','email');       
        $data['contact_name'] = $request->first_name;
        $data['account_name'] = $request->first_name;
        $data['name'] = $request->first_name;
        $data['web_name'] = $request->first_name;
        $data['password'] = bcrypt($request->password);

        $response = Seller::create($data);           

        } /*  Create Buyer */
        else if($request->type == Buyer::TYPE){
            $data = $request->only('phone','email');       
            $data['name'] = $request->first_name;
            $data['agrees_to_terms'] = $request->agrees_to_terms;
            $data['password'] = bcrypt($request->password);
            $response = Buyer::create($data); 

         }

         if($response){
            return response()->json([
            'data'=>$response,
            'message' => 'Successfully registered',],
            Response::HTTP_OK
            );
         }
 
    }

}