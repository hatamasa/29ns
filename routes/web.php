<?php

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/


use Illuminate\Support\Facades\Route;

Route::group(['middleware' => 'web'], function () {

    Route::auth();

    Route::get('/', 'HomeController@index');
    Route::get('/home', 'HomeController@index');

    Route::get('/search/area', 'SearchController@area');
    Route::get('/search/station', 'SearchController@station');

    Route::get('/shops', 'ShopsController@index');
    Route::get('/shops/{shop_cd}', 'ShopsController@show')->where(['shop_cd' => '[a-z0-9]+']);
    Route::get('/shops/ranking', 'ShopsController@ranking');

    Route::get('/posts', 'PostsController@index');
    Route::get('/posts/{id}', 'PostsController@show')->where(['id' => '[a-z0-9]+']);

    Route::post('/post_comments/{id}', 'PostCommentsController@store');
    Route::delete('/post_comments/{id}', 'PostCommentsController@destroy');
});
