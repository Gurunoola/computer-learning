<?php

use App\Http\Controllers\AuthController;
use App\Http\Controllers\UsersController;
use App\Http\Controllers\QnAController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use Illuminate\Foundation\Auth\EmailVerificationRequest;
use App\Http\Controllers\CategoriesController;
use App\Http\Controllers\TopicsController;
use App\Http\Controllers\UserProgressController;
use App\Http\Controllers\UserTestResultController;
use App\Http\Controllers\LearningContentController;


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
        Route::post('/qna/{id}/restore', [QnAController::class, 'restore']);
        Route::get('/qna/trashed', [QnAController::class, 'trashed']);
        Route::delete('/qna/{id}/force', [QnAController::class, 'forceDelete']);
        Route::get('/user-test-results/trashed', [UserTestResultController::class, 'trashed']);
        Route::post('/user-test-results/restore/{id}', [UserTestResultController::class, 'restore']);
        Route::get('/categories/trashed', [CategoriesController::class, 'trashed']);
        Route::post('/categories/{id}/restore', [CategoriesController::class, 'restore']);
        Route::delete('/categories/{id}/force', [CategoriesController::class, 'forceDelete']);
        Route::get('/topics/trashed', [TopicsController::class, 'trashed']);
        Route::post('/topics/{id}/restore', [TopicsController::class, 'restore']);
        Route::delete('/topics/{id}/force', [TopicsController::class, 'forceDelete']);
        Route::get('/user-progress/trashed', [UserProgressController::class, 'trashed']);
        Route::post('/user-progress/{id}/restore', [UserProgressController::class, 'restore']);
        Route::delete('/user-progress/{id}/force', [UserProgressController::class, 'forceDelete']);

        Route::get('/learning-contents/trashed', [LearningContentController::class, 'trashed']);
        Route::delete('/learning-contents/{id}/force', [LearningContentController::class, 'forceDelete']);
        Route::post('learning-contents/{id}/restore', [LearningContentController::class, 'restore']);
    
        

        
    });

    Route::group(['middleware' => ['role:admin,user']], function () {        
        //QnA
        Route::apiResource('/qna', QnAController::class);

        //cat
        Route::apiResource('/categories', CategoriesController::class);

        //topics
        Route::apiResource('/topics', TopicsController::class);

        //prgress
        Route::apiResource('/user-progress', UserProgressController::class);
        Route::get('/user-progress/user/{userId}', [UserProgressController::class, 'getUserProgress']);

        //Test results
        Route::apiResource('/user-test-results', UserTestResultController::class);

        Route::apiResource('learning-contents', LearningContentController::class);


        
    });
});
