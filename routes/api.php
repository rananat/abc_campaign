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

Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
    return $request->user();
});

Route::group(['namespace' => 'App\Http\Controllers'], function(){
    Route::apiResource('customer', CustomerController::class);
    Route::apiResource('customercampaign', CustomerCampaignController::class);
    Route::apiResource('voucher', VoucherController::class);    
});

Route::get('/isEligibleForCampaign/{id}', [App\Http\Controllers\CustomerCampaignController::class, "isEligible"]);
Route::post('/isEligibleForCampaign', [App\Http\Controllers\CustomerCampaignController::class, "isEligible"]);

//Get method added for validatesubmission for easy testing. For image upload, only POST method is supported.
Route::get('/validateSubmission/{id}', [App\Http\Controllers\CustomerCampaignController::class, "validateSubmission"]);
Route::post('/validateSubmission', [App\Http\Controllers\CustomerCampaignController::class, "validateSubmission"]);

/*Route::get('iseligibleforcampaign/{id}', function($id)
{
    
});*/
