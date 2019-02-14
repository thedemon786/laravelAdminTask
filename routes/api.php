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

Route::middleware('auth:api')->get('/user', function (Request $request) {
    return $request->user();
});

 //list users
 Route::get('users', 'UserController@index');

 //list single user
 Route::get('user/{id}', 'UserController@show');
 
 //create user
 Route::post('user', 'UserController@store');
 
 //update user
 Route::put('user', 'UserController@store');
 
 //delete user
 Route::delete('user/{id}', 'UserController@destroy');

   
Route::group(['middleware'=>'auth:admin'], function(){
   
    });

