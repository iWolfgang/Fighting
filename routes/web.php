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

Route::post('/SmsCode/sendCode', 'SmsCodeController@sendCode');//发送短信验证码
Route::post('/SmsCode/checkCode', 'SmsCodeController@checkCode');//校验验证码是否正确
Route::post('/User/regist', 'UserController@regist');//用户注册
Route::post('/User/login', 'UserController@login');//用户登录
Route::get('/User/apijson', 'UserController@apijson');
Route::post('/CheckCode/formatPY', 'ArticleController@formatPY');//搜索
Route::post('/CheckCode/search', 'ArticleController@search');//搜索
Route::post('/CheckCode/Article_msg', 'ArticleController@Article_msg');//文章信息 差

Route::post('/Article/getArticleInfo', 'ArticleController@getArticleInfo');//文章信息 好

Route::post('/ArticleComment/addCommentLike', 'ArticleCommnetController@addCommentLike');//点赞
Route::post('/ArticleComment/addComment', 'ArticleCommnetController@addComment');//添加评论
Route::post('/ArticleComment/ArticleComment_list', 'ArticleCommnetController@articleCommnet_list');//评论列表
Route::post('/ArticleComment/ArticleDel', 'ArticleCommnetController@ArticleDel');//删除文章
Route::post('/ArticleComment/Art_Com_reply', 'ArticleCommnetController@Art_Com_reply');//回复列表
Route::post('/ArticleCollect/Art_col', 'ArticleCollectController@Art_col');//文章收藏
Route::post('/ArticleCollect/Art_col_reply', 'ArticleCollectController@Art_col_reply');//文章收藏列表

