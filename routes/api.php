<?php

/*
 * |--------------------------------------------------------------------------
 * | API Routes
 * |--------------------------------------------------------------------------
 * |
 * | Here is where you can register API routes for your application. These
 * | routes are loaded by the RouteServiceProvider within a group which
 * | is assigned the "api" middleware group. Enjoy building your API!
 * |
 */
use Illuminate\Support\Facades\Route;

Route::group(["middleware" => "guest:api"], function () {
    Route::prefix('v1')->group(function () {
        Route::post("/login", "Api\AuthController@login");
    });
});

Route::group(["middleware" => "auth:api"], function () {
    Route::prefix('v1')->group(function () {
        Route::get("/me", "Api\AuthController@me");
        Route::get('getPostList', 'Api\PostsApiController@getPostList');
    });
});