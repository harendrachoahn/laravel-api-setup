<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Repositories\Interfaces\SellerRepositoryInterface;
use App\Models\Seller;
use App\Helpers\Helper;
use Exception;
use JWTAuth;
use Illuminate\Http\Response;
use Hash;
use Storage;

class SellerController extends Controller
{
    private $sellerRepository;

    public function __construct( SellerRepositoryInterface $sellerRepository)
    {
        $this->sellerRepository = $sellerRepository;
    }
      
    /**
     * Get profile 
     * @param Request $request
     * @return Json output
     */
    public function getprofile(Request $request)
    {
        // auth check
        $user = JWTAuth::parseToken()->authenticate();
        $seller_id = $user->id;
       // dd($user);

        $seller = $this->sellerRepository->getUserById($seller_id);
        $seller->user_type = Seller::TYPE;

        $data = ['message' => trans('messages.SELLER_PROFILE_SHOW'), 'data' => $seller];
        return Helper::responseJson($data, Response::HTTP_OK);
    }
             
    /** 
     * update Seller
     * @param Request $request
     * @return Json  
     */
    public function updateProfile(Request $request)
    {
        $validation_data = [
            'name' => 'required|string|max:100',
            'contact_name' => 'required|max:200',
            'fax' => 'required|max:20',
            'apply_gst' => 'required',
            'abn' => 'required',
            'account_bsb' => 'required|min:6',            
            'account_number' => 'required|unique:sellers|numeric',
            'account_name' => 'required|string|max:100',
            'web_name' => 'required|string|max:100',
            'phone' => 'required',
        ];

        $response = Helper::validationCheck($request, $validation_data);
        if (!is_bool($response)) {
            return $response;
        }

        $user = JWTAuth::parseToken()->authenticate();
        $seller_id = $user->id;

        $seller = $this->sellerRepository->getUserById($seller_id);

        if (!$seller) {
            $info = ['message' => trans('messages.SELLER_NOT_FOUND')];
            return Helper::responseJson($info, Response::HTTP_BAD_REQUEST);
        }

        $requestData = $request->only('name','contact_name','fax','apply_gst','abn','account_bsb','account_number','account_name','web_name','phone');
      
        // use Seller repository for update agent
        $seller = $this->sellerRepository->updateSeller($requestData, $seller_id);

        $data = ['message' => trans('messages.SELLER_UPDATE'), 'data' =>  $seller];
        return Helper::responseJson($data, Response::HTTP_OK);
    }
        /** 
     * Change Seller Password
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

        $user = JWTAuth::parseToken()->authenticate();
        if (!Hash::check($request->current_password, $user->password)) {
            $data = ['message' => trans('messages.INCORRECT_PASSWORD')];
            return Helper::responseJson($data, Response::HTTP_BAD_REQUEST);
        }

        $data = array('password'=>bcrypt($request->password));
        // use Seller repository for update Seller
        $seller = $this->sellerRepository->updateSeller($data, $user->id);

        $data = ['message' => trans('messages.PASSWORD_CHANGE')];
        return Helper::responseJson($data, Response::HTTP_OK);
    }


    public function uploadSellerImage(Request $request){
        // check if image has been received from form
        if($request->file('image')){

            // check if user has an existing avatar
            $user = JWTAuth::parseToken()->authenticate();

            if($user ->image != NULL){
                // delete existing image file
                Storage::disk('seller_images')->delete($user ->image);
            }
            
    
            // processing the uploaded image
            $avatar_name =  time().'.'.$request->file('image')->getClientOriginalExtension();
            $avatar_path = $request->file('image')->storeAs('',$avatar_name, 'seller_images');
    
            // Update user's avatar column on 'users' table
            // $profile = Seller::find($user->id);
            // $profile->image = $avatar_path;

            $data = array('image'=>$avatar_path);
            // use Seller repository for update Seller
            $seller = $this->sellerRepository->updateSeller($data, $user->id);
    
            if($seller){
                $data = ['message' => trans('messages.PROFILE_UPLOAD'),
                        'avatar_url'=>  url('storage/seller-image/'.$avatar_path)];
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
