<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Arr;
use Illuminate\Support\Facades\Storage;


class Photo extends Model
{
    /** プライマリキーの型 */
    protected $keyType = 'string';

    /** JSONに含める属性
     * 余計な項目(user_id, filename)を表示しない
     */
    protected $visible = [
        'id', 'owner', 'url',
    ];

    /** 
     * JSONに含める属性
     * アクセサで定義した内容で実際にJSONとして反映させる項目
     */
    protected $appends = [
        'url',
    ];



    /** IDの桁数 */
    const ID_LENGTH = 12;

    public function __construct(array $attributes = [])
    {
        parent::__construct($attributes);

        if (!Arr::get($this->attributes, 'id')) {
            $this->setId();
        }
    }

    /**
     * ランダムなID値をid属性に代入する
     */
    private function setId()
    {
        $this->attributes['id'] = $this->getRandomId();
    }

    /**
     * ランダムなID値を生成する
     * @return string
     */
    private function getRandomId()
    {
        $characters = array_merge(
            range(0, 9),
            range('a', 'z'),
            range('A', 'Z'),
            ['-', '_']
        );

        $length = count($characters);

        $id = "";

        for ($i = 0; $i < self::ID_LENGTH; $i++) {
            $id .= $characters[random_int(0, $length - 1)];
        }

        return $id;
    }

    /**
     * リレーションシップ - usersテーブル
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function owner()
    {
        // メソッド名ownerのようにリレーション先(user)と異なる場合は引数に参照する値を書く必要あり
        return $this->belongsTo('App\User', 'user_id', 'id', 'users');
    }

    /**
     * ユーザー定義のアクセサ - url
     * @return string
     * urlメソッドはS3上の公開URL(.envのAWS_URLと引数のファイル名の結合)が返却される
     * アクセサは定義しただけだとモデルのJSON表現には現れないため別途$appendに出力内容を指定する必要あり
     */
    public function getUrlAttribute()
    {
        return Storage::cloud()->url($this->attributes['filename']);
    }
}
