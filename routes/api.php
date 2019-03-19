<?php

use Illuminate\Http\Request;

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



// Authorize users
Route::get('user/auth', 'AuthController@login');

// user
Route::get('users/find/{q}', 'UserController@index');
Route::group(['middleware' => 'auth:api'], function(){
Route::get('user', 'UserController@show');
});
Route::post('user', 'UserController@store');
Route::put('user/{id}', 'UserController@update');
Route::delete('user/{id}', 'UserController@destroy');


// posts
Route::get('posts', 'PostController@index');
Route::get('post/{id}', 'PostController@show');
Route::post('post', 'PostController@store');
Route::put('post/{id}', 'PostController@update');
Route::delete('post/{id}', 'PostController@destroy');


//messages
Route::get('messages', 'MessageController@index');
Route::group(['prefix'=>'message'],function(){
Route::get('{id}', 'MessageController@show');
Route::post('', 'MessageController@store');
Route::put('{id}', 'MessageController@update');
Route::delete('{id}', 'MessageController@destroy');});
//comments
Route::get('comments', 'CommentController@index');
Route::get('comment/{id}', 'CommentController@show');
Route::post('comment', 'CommentController@store');
Route::put('comment/{id}', 'CommentController@update');
Route::delete('comment/{id}', 'CommentController@destroy');
//sections
Route::get('sections', 'SectionController@index');

// doctos
Route::get("doctors/recommended", "DoctorController@recommended");
Route::get('doctors/find/{q}', 'DoctorController@index');
Route::group(['middleware' => 'auth:api'], function(){
Route::get('doctor', 'DoctorController@show');});
Route::post('doctor', 'DoctorController@store');
Route::put('doctor/{id}', 'DoctorController@update');
Route::delete('doctor/{id}', 'DoctorController@destroy');
