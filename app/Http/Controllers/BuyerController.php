<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Repositories\Interfaces\BuyerRepositoryInterface;
use App\Models\Buyer;
use App\Helpers\Helper;
use Exception;
use JWTAuth;
use Illuminate\Http\Response;
use Hash;
use Storage;

class BuyerController extends Controller
{
    private $buyerRepository;

    public function __construct( BuyerRepositoryInterface $buyerRepository)
    {
        $this->buyerRepository = $buyerRepository;
    }
      
    /**
     * Get profile 
     * @param Request $request
     * @return Json output
     */
    public function getprofile(Request $request)
    {
        // auth check
        $user = JWTAuth::toUser(JWTAuth::getToken());
        $buyer_id = $user->id;

        $buyer = $this->buyerRepository->getUserById($buyer_id);
        $buyer->user_type = Buyer::TYPE;

        $data = ['message' => trans('messages.BUYER_PROFILE_SHOW'), 'data' => $buyer];
        return Helper::responseJson($data, Response::HTTP_OK);
    }
             
    /** 
     * Update Buyer
     * @param Request $request
     * @return Json  
     */
    public function updateProfile(Request $request)
    {
        $validation_data = [
            'name' => 'required|string|max:100',
            'dob' => 'required',
            'gender' => 'required',
            'married' => 'required',
            'phone' => 'required|min:10',
        ];

        $response = Helper::validationCheck($request, $validation_data);
        if (!is_bool($response)) {
            return $response;
        }

        $buyer_id = Helper::getUserId();

        if (!$buyer_id) {           
            $info = ['message' => trans('messages.USER_N0T_FOUND')];
            return Helper::responseJson($info, Response::HTTP_UNAUTHORIZED);
        }

        $buyer = $this->buyerRepository->getUserById($buyer_id);

        if (!$buyer) {
            $info = ['message' => trans('messages.BUYER_NOT_FOUND')];
            return Helper::responseJson($info, Response::HTTP_BAD_REQUEST);
        }

        $requestData = $request->only('name','dob','gender','married','phone');
      
        // use Buyer repository for update Buyer
        $buyer = $this->buyerRepository->updateBuyer($requestData, $buyer_id);

        $data = ['message' => trans('messages.BUYER_UPDATE'), 'data' =>  $buyer];
        return Helper::responseJson($data, Response::HTTP_OK);
    }
        /** 
     * Change Buyer Password
     * @param Request $request
     * @return Json  
     */
    public function changePassword(Request $request)
    {
        $validation_data = [
            'current_password' => 'required|string|min:8',
            'password' => 'required|string|min:8',
            'confirm_password' => 'required|same:password',
        ];

        $response = Helper::validationCheck($request, $validation_data);

        if (!is_bool($response)) {
            return $response;
        }

        $user = Helper::getUser();

        if (!$user) {           
            $info = ['message' => trans('messages.USER_N0T_FOUND')];
            return Helper::responseJson($info, Response::HTTP_UNAUTHORIZED);
        }

        if (!Hash::check($request->current_password, $user->password)) {
            $data = ['message' => trans('messages.INCORRECT_PASSWORD')];
            return Helper::responseJson($data, Response::HTTP_BAD_REQUEST);
        }

        $data = array('password'=>bcrypt($request->password));

        // use Seller repository for update Seller
        $seller = $this->buyerRepository->updateBuyer($data, $user->id);

        $data = ['message' => trans('messages.PASSWORD_CHANGE')];
        return Helper::responseJson($data, Response::HTTP_OK);
    }

    /** 
     * Upload Buyer Image
     * @param Request $request
     * @return Json  
     */
    public function uploadBuyerImage(Request $request){
        // check if image has been received from form
        if($request->file('image')){

            // check if user has an existing avatar
            $user = Helper::getUser();
            if (!$user) {           
                $info = ['message' => trans('messages.USER_N0T_FOUND')];
                return Helper::responseJson($info, Response::HTTP_UNAUTHORIZED);
            }

            if($user ->image != NULL){
                // delete existing image file
                Storage::disk('buyers_images')->delete($user ->image);
            }            
    
            // processing the uploaded image
            $avatar_name =  time().'.'.$request->file('image')->getClientOriginalExtension();
            $avatar_path = $request->file('image')->storeAs('',$avatar_name, 'buyers_images');

            $data = array('image'=>$avatar_path);
            // use Seller repository for update Seller
            $seller = $this->buyerRepository->updateBuyer($data, $user->id);
    
            if($seller){
                $data = ['message' => trans('messages.PROFILE_UPLOAD'),
                        'avatar_url'=>  url('storage/buyer-image/'.$avatar_path)];
                return Helper::responseJson($data, Response::HTTP_OK);
            }else{
                $data = ['message' => trans('messages.NOT_IMAGE_UPLOAD')];
                return Helper::responseJson($data, Response::HTTP_BAD_REQUEST);
            }
    
        }

        $data = ['message' => trans('messages.NOT_IMAGE_UPLOAD')];
        return Helper::responseJson($data, Response::HTTP_BAD_REQUEST);
    }
    
}
