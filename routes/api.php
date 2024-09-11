<?php

use App\Http\Controllers\api\FollowController;
use App\Http\Controllers\Api\loginController;
use App\Http\Controllers\api\TweetController;
use App\Http\Controllers\api\AuthController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;


Route::get('/user', function (Request $request) {
    return $request->user();
})->middleware('auth:sanctum');



Route::prefix('auth')->controller(AuthController::class)->group(function () {

    Route::post('/register', 'register');
    Route::post('/login', 'login');
    Route::delete('/logout', 'logout')->middleware('auth:sanctum');

});

Route::apiResource('tweets', TweetController::class)->middleware('auth:sanctum');



Route::prefix('/users/{user}/followers')
->middleware('auth:sanctum')
->controller(FollowController::class)->group(function () {
    Route::post('/', 'store');
    Route::delete('/', 'destroy');
});