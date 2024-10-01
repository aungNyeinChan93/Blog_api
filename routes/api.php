<?php

use App\Http\Controllers\Api\AuthController;
use App\Http\Controllers\Api\CategoryController;
use App\Http\Controllers\Api\PostController;
use App\Http\Controllers\Api\UserProfileController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

Route::get('/user', function (Request $request) {
    return $request->user();
})->middleware('auth:sanctum');

Route::post("register",[AuthController::class,"register"])->name("Auth#register");
Route::post("login",[AuthController::class,"login"])->name("Auth#login");


Route::group(["middleware"=>"auth:sanctum"],function(){
    Route::post("logout",[AuthController::class,"logout"]);

    // profile
    Route::get("profile",[UserProfileController::class,"profile"]);

    // categories
    Route::get("categories",[CategoryController::class,"list"]);

    // Posts
    Route::post("posts",[PostController::class,"create"]);
});
