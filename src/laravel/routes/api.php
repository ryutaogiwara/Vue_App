<?php

use Illuminate\Http\Request;

// 会員登録
Route::post('/register', 'Auth\RegisterController@register')->name('register');

// Route::middleware('auth:api')->get('/user', function (Request $request) {
//     return $request->user();
// });
