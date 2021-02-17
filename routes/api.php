<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| is assigned the "api" middleware group. Enjoy building your API!
|
*/

//Unauthenticated routes for customers here
Route::get('/request-otp','OtpController@requestOtp')->name('request otp for signup');
Route::get('/resend-otp','OtpController@requestOtp')->name('resend for signup');
Route::post('/verify-otp','OtpController@verifyOtp')->name('verify signup and forgot password');

Route::post('/sign-up','JwtAuthController@register')->name('seller-buyer-signup');
Route::post('/login','AuthController@login')->name('seller-buyer-login');

//Forgot Password routes
Route::post('reset-password-request', 'ForgotPasswordController@restPasswordRequest');
Route::get('forgot-resend-otp','ForgotPasswordController@restPasswordRequest')->name('resend for forgot password');
Route::post('reset-password', 'ForgotPasswordController@passwordResetProcess');


//Auth buyer routes
Route::group(['prefix' => 'buyer','middleware' => ['assign.guard:buyers','jwt.verify']],function ()
{  
	Route::get('/get-profile','BuyerController@getProfile');	
});

/// Seller Auth routes
Route::group(['prefix' => 'seller','middleware' => ['assign.guard:sellers','jwt.verify']],function ()
{  

	Route::get('/get-profile','SellerController@getProfile');

	Route::put('/update-profile','SellerController@updateProfile');

	
	
});
