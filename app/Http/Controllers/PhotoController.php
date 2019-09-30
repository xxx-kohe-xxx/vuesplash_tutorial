<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request\StorePhoto;
use App\Photo;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;

class PhotoController extends Controller
{
  public function __construct()
  {
    // 認証が必要
    $this->middleware('auth');
  }

  // 写真投稿
  public function create(StorePhoto $request)
  {
    $extension = $request->photo->extension();

    $photo = new Photo();

    // インスタンス生成時に割当てられたランダムなIDと本来の拡張子を組み合わせてファイル名とする
    $photo->filename = $photo->id.'.'.$extension;

    // S3にファイルを保存する
    // 第三引数の'public'はファイルを公開状態で保存
    Storage::cloud()->putFileAs('',$request->photo, $photo->filename, 'public');

    // データベースエラー時にファイル削除を行うためトランザクションを利用する
    DB::beginTransaction();

    try{
      Auth::user()->photos()->save($photo);
      DB::commit();
    }catch (\Exception $exception){
      DB::rollBack();
      // DBとの不整合を避けるためアップロードしたファイルを削除
      Storage::cloud()->delete($photo->filename);
      throw $execute;
    }

    // リソースの新規作成なのでレスポンスコードは201(CREATED)を返却する
    return response($photo, 201);
  }
}
