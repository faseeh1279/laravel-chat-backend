<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\MessageController;
use App\Http\Controllers\Api\AuthController;
use App\Http\Controllers\Api\ConversationController;
use App\Http\Controllers\Api\UserController;

Route::get('/user', function (Request $request) {
    return $request->user();
})->middleware('auth:sanctum');

Route::middleware('auth:sanctum')->post('/messages', [MessageController::class, 'store']);

Route::post('check-message', [MessageController::class, 'checkMessage']);

Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
    return $request->user();
});



// Route::group(function )
Route::post('register',[AuthController::class,'register']);
Route::post('login',[AuthController::class,'login']);
Route::post('logout',[AuthController::class,'logout'])
  ->middleware('auth:sanctum');

Route::post('verify', [AuthController::class, 'verifyEndpoint'])->middleware('auth:sanctum');


Route::middleware('auth:sanctum')->group(function () {

    Route::get(
        '/conversations/{conversation}/messages',
        [MessageController::class, 'index']
    );

    Route::post(
        '/conversations/{conversation}/messages',
        [MessageController::class, 'store']
    );

    Route::get('/users', [UserController::class, 'getUsersList']);
    
    Route::get('/conversations', [ConversationController::class, 'index']);

    Route::post('/conversations', [ConversationController::class, 'store']);

    Route::get('/conversations/{conversation}', [ConversationController::class, 'show']);
    });
    
Route::get('/get-users', [MessageController::class, 'getUsersWithConversationsList']);


