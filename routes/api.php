<?php

use App\Http\Controllers\api\VerificationCodeController;
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

Route::middleware('auth:api')->get('/user', function (Request $request) {
    return $request->user();
});

Route::prefix('verification')->group(function(){
    Route::get('/get-code', [VerificationCodeController::class, 'getCode']);
});


// Route::get('/test-code', [VerificationCodeController::class, 'testCode']);
