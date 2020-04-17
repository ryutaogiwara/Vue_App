<?php

namespace App\Http\Controllers;

use App\Http\Requests\StorePhoto;
use App\Http\Requests\StoreComment;
use App\Photo;
use App\Comment;
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
        $this->middleware('auth')->except(['index', 'download', 'show']);
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
         * コントローラクラスからモデルクラスのインスタンスをreturnすると自動的にlaravelがJSONに変換してレスポンス(response.data)が生成される
         * JSONに変換される場合にwithで指定したリレーションは自動的に解決されるが任意に指定したアクセサは処理に含まれないためモデルクラス内で$appendプロパティに登録しておく必要がある
         */
        return $photos;
    }

    /**
     * 写真ダウンロード
     * @param Photo $photo
     * @return \Illuminate\Http\Response
     */
    public function download(Photo $photo)
    {
        // 写真の存在チェック
        if (!Storage::cloud()->exists($photo->filename)) {
            abort(404);
        }

        // $dispositionにattachment及びfilenameを指定しておく
        // レスポンスヘッダ Content-Disposition内に$dispositionを含めると表示ではなく保存ダイアログに切り替わる
        $disposition = 'attachment; filename="' . $photo->filename . '"';
        $headers = [
            'Content-Type' => 'application/octet-stream',
            // レスポンス内容を表示ではなく保存ログで開くため
            'Content-Disposition' => $disposition,
        ];

        return response(Storage::cloud()->get($photo->filename), 200, $headers);
    }

    /**
     * 写真詳細
     * @param string $id
     * @return Photo
     */
    public function show(string $id)
    {
        // commments.authorのようにプロパティに.リレーション名でそれにひもづくデータを取得できる
        $photo = Photo::where('id', $id)->with(['owner', 'comments.author'])->first();

        return $photo ?? abort(404);
    }

    /**
     * コメント投稿
     * @param Photo $photo
     * @param StoreComment $request
     * @return \Illuminate\Http\Response
     */
    public function addComment(Photo $photo, StoreComment $request) {
        $comment = new Comment();
        $comment->content = $request->get('content');
        $comment->user_id = Auth::user()->id;
        $photo->comments()->save($comment);

        // authorリレーションをロードするためにコメントを取得しなおす
        $new_comment = Comment::where('id', $comment->id)->with('author')->first();

        return response($new_comment, 201);
    }
}
