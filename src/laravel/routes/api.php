<?php

use Illuminate\Http\Request;

// 会員登録
Route::post('/register', 'Auth\RegisterController@register')->name('register');

// ログイン
Route::post('/login', 'Auth\LoginController@login')->name('login');

// ログアウト
Route::post('/logout', 'Auth\LoginController@logout')->name('logout');

// ログインユーザー
Route::get('/user', fn () => Auth::user())->name('user');

// 写真投稿
Route::post('/photos', 'PhotoController@create')->name('photo.create');

// 写真一覧
Route::get('/photos', 'PhotoController@index')->name('photo.index');

// 写真詳細
Route::get('/photos/{id}', 'PhotoController@show')->name('photo.show');

// コメント送信
Route::post('/photos/{photo}/comments', 'PhotoController@addComment')->name('photo.comment');

// いいね
Route::put('/photos/{id}/like', 'PhotoController@like')->name('photo.like');

// いいね削除
Route::delete('/photos/{id}/like', 'PhotoController@unlike');

// CSRFトークンリフレッシュ
Route::get('/refresh-token', function (Illuminate\Http\Request $request) {
  $request->session()->regenerateToken();
  return response()->json();
});



// Route::middleware('auth:api')->get('/user', function (Request $request) {
//     return $request->user();
// });
