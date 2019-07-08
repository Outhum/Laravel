<?php

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/
Route::get('/', function () {
    return view('upload');
});
/*
Route::post('upload1','Controller@upload1');
Route::post('convert','Controller@convert');Route::post('convert','Controller@convert');
*/
Route::get('gif-upload', 'Controller@gifUpload')->name('gif.upload');

Route::post('gif-upload', 'Controller@gifUploadPost')->name('gif.upload.post');
//Route::get('convert','Controller@convert');
Route::post('image_video', 'Controller@imagetovideo')->name('image_video.post');

Route::post('video_upload','Controller@videoupload')->name('video_upload.post');