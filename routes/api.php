<?php

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

Route::post('/create-user', 'Api\AuthController@createUser');

Route::post('/login', 'Api\AuthController@login');


Route::middleware('auth:api')->group(function () {
    Route::get('/users', 'Api\UserController@getUsers');
    Route::get('/user/{id}', 'Api\UserController@getUser');

    Route::get('/profile', 'Api\UserController@profile');
    Route::get('/logout', 'Api\AuthController@logout');
});
