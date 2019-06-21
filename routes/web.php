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


use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Route;

Route::group(['middleware' => 'web'], function () {

    Route::auth();

    Auth::routes(['verify' => true]);

    Route::get('/', 'HomeController@index');
    Route::get('/home', 'HomeController@index');

    Route::get('/users/{id}', 'UsersController@show')->where(['id' => '[0-9]+']);
    Route::get('/users/{id}/edit', 'UsersController@edit')->where(['id' => '[0-9]+']);
    Route::put('/users/update', 'UsersController@update');

    Route::post('/user_follows', 'UserFollowsController@store');
    Route::delete('/user_follows/{id}', 'UserFollowsController@destory')->where(['id' => '[0-9]+']);

    Route::get('/search/area', 'SearchController@area');
    Route::get('/search/station', 'SearchController@station');

    Route::get('/shops', 'ShopsController@index');
    Route::get('/shops/{shop_cd}', 'ShopsController@show')->where(['shop_cd' => '[a-z0-9]+']);
    Route::get('/shops/ranking', 'ShopsController@ranking');

    Route::get('/posts', 'PostsController@index');
    Route::get('/posts/create', 'PostsController@create');
    Route::post('/posts', 'PostsController@store');
    Route::get('/posts/{id}', 'PostsController@show')->where(['id' => '[0-9]+']);
    Route::delete('/posts/{id}', 'PostsController@destroy')->where(['id' => '[0-9]+']);

    Route::post('/post_comments', 'PostCommentsController@store');
    Route::delete('/post_comments/{id}', 'PostCommentsController@destroy')->where(['id' => '[0-9]+']);
});
