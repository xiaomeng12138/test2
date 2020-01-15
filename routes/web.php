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

Route::get('/login','UserController@index'); //普通登录页面
Route::get('/wechat_login','UserController@wechat_login');  //微信扫码页面
Route::any('/check_login','UserController@check_login');  //检测用户是否扫码
Route::post('/login_do','UserController@login_do');
Route::any('/quit','UserController@quit');  //退出

Route::middleware('login')->group(function(){
    Route::get('/list','UserController@list');  //列表
});

//微信
Route::any('login/index','LoginController@index');
