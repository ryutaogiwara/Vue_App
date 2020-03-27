<?php

// 写真ダウンロード
// インデックスルート依も上におく必要あり('/{any?}'が先に当たるとルートをうまく振り分けられないため)
Route::get('/photos/{photo}/download', 'PhotoController@download');

// APIのURL以外のリクエストに対してはindexテンプレートを返す
// 画面遷移はフロントエンドのVueRouterが制御する
Route::get('/{any?}', fn () => view('index'))->where('any', '.+');


// Route::get('/', function () {
//     return view('welcome');
// });
