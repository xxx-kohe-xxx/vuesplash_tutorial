<?php

// 会員登録
Route::post('/register', 'Auth\RegisterController@register')->name('register');
Route::post('/login', 'Auth\LoginController@login')->name('login');
Route::post('/logout', 'Auth\LoginController@logout')->name('logout');
Route::get('/user', function(){
  return Auth::user();
})->name('user');
Route::post('/photo', 'PhotoController@create')->name('photo.create'); // 写真投稿