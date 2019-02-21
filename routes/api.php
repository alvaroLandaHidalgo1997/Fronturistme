<?php

use Illuminate\Http\Request;


Route::middleware('auth:api')->get('/user', function (Request $request) {
    return $request->user();
});

Route::apiResource('place', 'PlaceController');
Route::apiResource('user', 'UserController');
Route::post('store', 'PlaceController@store');
Route::post('updatePlace','PlaceController@updatePlace');
Route::post('login', 'LoginController@login');