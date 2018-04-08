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

Route::post('/SmsCode/sendCode', 'SmsCodeController@sendCode');
Route::post('/SmsCode/checkCode', 'SmsCodeController@checkCode');
Route::post('/User/regist', 'UserController@regist');
Route::post('/User/login', 'UserController@login');