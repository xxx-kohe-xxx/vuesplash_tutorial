<?php

namespace Tests\Feature;

use App\Photo;
use App\User;
use Tests\TestCase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\Storage;

class PhotoSubmitApiTest extends TestCase
{
  use RefreshDatabase;

  public function setUp(): void
  {
    parent::setUp();

    $this->user = factory(User::class)->create(); 
  }
  /**
   * A basic feature test example.
   *
   * @test
   */
  public function should_ファイルをアップロードできる()
  {
    // S3ではなくテスト用のストレージを使用する
    // →storage/framework/testing
    Storage::fake('s3');

    $response = $this->actingAs($this->user)
      ->json('POST', route('photo.create'),[
        // ダミーファイルを作成して送信してる
        'photo' => UploadedFile::fake()->image('photo.jpg'),
      ]);
    
    // レスポンスが201であること
    $response->assertStatus(201);

    $photo = Photo::first();

    // 写真のIDが12桁のランダムな文字列であること
    $this->assertRegExp('/^[0-9a-zA-Z-_]{12}$/', $photo->id);
    
    // DBに挿入されたファイル名のファイルがストレージに保存されていること
    Storage::cloud()->assertExists($photo->filename);
  }

  /**
   * @test
   */
  public function should_データベースエラーの場合はファイルを保存しない()
  {
    Schema::drop('photo'); // 無理やりDBエラーを起こす

    Storage::fake('s3');

    $response = $this->actingAs($this->user)
      ->json('POST', route('photo.create'), [
        'photo' => UploadedFile::fake()->image('photo.jpg'),
      ]);

    $response->assertStatus(500); //レスポンスがINTERNAL_SERVER_ERRORとなること

    $this->assertEquals(0,count(Storage::cloud()->first()));
  }

  /**
  * @test
  */
  public function should_ファイル保存エラーの場合はDBへの挿入はしない()
  {
    // ストレージを持っくして保存時にエラーを起こさせる
    Storage::shouldReceive('cloud')
      ->once()
      ->andReturnNull();
    
    $response = $this->actingAs($this->user)
      ->json('POST', route('photo.create'), [
        'photo' => UploadedFile::fake()->image('photo.jpg'),
      ]);
    
    $response->assertStatus(500);

    $this->assertEmpty(Photo::all());
  }
}
