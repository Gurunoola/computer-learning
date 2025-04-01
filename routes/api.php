<?php

use App\Http\Controllers\AuthController;
use App\Http\Controllers\UsersController;
use App\Http\Controllers\QnAController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use Illuminate\Foundation\Auth\EmailVerificationRequest;

// Route::post('/login', [AuthController::class, 'login']);
// Route::post('/register', [AuthController::class, 'register']);
// Route::get('/verify-email/{token}', [AuthController::class, 'verifyEmail']);
// Route::post('/logout', [AuthController::class, 'logout']);


Route::post('/register', [AuthController::class, 'register']);
Route::post('/verify-otp', [AuthController::class, 'verifyOtp']);
Route::post('/login', [AuthController::class, 'login']);
Route::middleware('auth:sanctum')->post('/payment', [AuthController::class, 'processPayment']);
Route::middleware('auth:sanctum')->post('/logout', [AuthController::class, 'logout']);

Route::group(['middleware' => ['auth:sanctum','verified']], function () {

    
    // Admin-only routes
    Route::group(['middleware' => ['role:admin']], function () {
        
        //users route
        Route::get('/user', function (Request $request) {return response()->json($request->user());});
        Route::post('/users/{id}/restore', [UsersController::class, 'restore']);
        Route::delete('/users/{id}/force', [UsersController::class, 'forceDelete']);
        Route::get('/users/trashed', [UsersController::class, 'trashed']);
        Route::apiResource('/users', UsersController::class);
        
    });

    Route::group(['middleware' => ['role:admin,user']], function () {        
        //QnA
        Route::post('/qna/{id}/restore', [QnAController::class, 'restore']);
        Route::get('/qna/trashed', [QnAController::class, 'trashed']);
        Route::delete('/qna/{id}/force', [QnAController::class, 'forceDelete']);
        Route::apiResource('/qna', QnAController::class);
        
    });
});
