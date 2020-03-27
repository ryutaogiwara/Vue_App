<?php

namespace App\Http\Controllers;

use App\Http\Requests\StorePhoto;
use App\Photo;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;

class PhotoController extends Controller
{
    public function __construct() {
        /**
         * 画像保存関連は認証が必要
         * 画像一覧表示に関しては認証を必要としないためexceptで回避させる
         */
        $this->middleware('auth')->except(['index', 'download']);
    }


    /**
     * 写真投稿
     * @param StorePhoto $request
     * @return \Illuminate\Http\Response
     */
    public function create(StorePhoto $request)
    {
        // ファイル拡張子の取得
        $extension = $request->photo->extension();

        // インスタンス作成
        $photo = new Photo();

        // インスタンス生成時にモデルで作成されたランダムIDと拡張子を結合
        $photo->filename = $photo->id . '.' . $extension;

        // S3に保存
        // 第三引数の'public'はファイルを公開状態で保存するため
        // cloud()を呼んだ場合は config/filesystems.phpのcloudの設定にしたがって使用されるストレージが決まる
        Storage::cloud()
            ->putFileAs('', $request->photo, $photo->filename, 'public');

        // データベースエラー時にファイル削除を行うため
        // トランザクションを利用する
        DB::beginTransaction();

        try {
            Auth::user()->photos()->save($photo);
            DB::commit();
        } catch (\Exception $exception) {
            DB::rollBack();
            // DBとの不整合を避けるためアップロードしたファイルを削除
            Storage::cloud()->delete($photo->filename);
            throw $exception;
        }

        // リソースの新規作成なのでレスポンスコードは201(CREATED)を返却する
        return response($photo, 201);
    }

    public function index()
    {
        /** 
         * withメソッドは引数に指定したリレーションを参照してデータ取得を行う
         * foreachなどでループ処理させる際に都度SQLを発行すると処理が遅くなるN＋1問題を回避するために用いる
         * paginate()メソッドはgetメソッド＋ページ送り機能がついた取得メソッド
         */
        $photos = Photo::with(['owner'])
            ->orderBy(Photo::CREATED_AT, 'desc')->paginate();

        /**
         * コントローラクラスからモデルクラスのインスタンスをreturnすると自動的にlaravelがJSONに変換してレスポンスが生成される
         * JSONに変換される場合にwithで指定したリレーションは自動的に解決されるが任意に指定したアクセサは処理に含まれないためモデルクラス内で$appendプロパティに登録しておく必要がある
         */
        return $photos;
    }
}
