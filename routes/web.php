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
    return view('welcome');
});

Route::get('/login','UserController@index');
Route::post('/login_do','UserController@login_do');
Route::any('/quit','UserController@quit');

Route::middleware('login')->group(function(){
    Route::get('/list','UserController@list');
});

//微信扫码登录
Route::any('login/index','LoginController@index');
Route::any('/GetQrcode','LoginController@GetQrcode');  //二维码
// Route::any('/Access_Token','LoginController@Access_Token');