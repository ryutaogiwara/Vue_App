<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Comment extends Model
{
    // json表示設定
    protected $visible = [
        'author', 'content',
    ];

    // リレーション：ユーザー
    public function author () {
        return $this->belongsTo('App\User', 'user_id', 'id', 'users');
    }
}
