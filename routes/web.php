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
Route::get('/ArticleCollect/demo_db', 'ArticleCollectController@demo_db');//同时连接两个db模拟
Route::post('/HomePage/slideshow', 'HomePageController@slideshow');//轮播图展示
Route::get('/HomePage/long_articlelist', 'HomePageController@long_articlelist');//长资讯展示
Route::get('/HomePage/short_articlelist', 'HomePageController@short_articlelist');//短资讯展示
Route::get('/HomePage/game_videolist', 'HomePageController@game_videolist');//游戏视频展示
Route::get('/HomePage/videolist', 'HomePageController@videolist');//视频资讯展示
Route::get('/HomePage/q_ask', 'HomePageController@q_ask');//问答展示
Route::get('/HomePage/full', 'HomePageController@full');//问答展示



Route::get('/Rbac/index', 'RbacController@index');//管理登陆界面
Route::post('/Rbac/login', 'RbacController@login');//管理登陆模块
Route::get('/Rbac/main', 'RbacController@main');//管理主模块
Route::get('/Rbac/regist', 'RbacController@regist');//管理注册模块
Route::post('/Rbac/regist_do', 'RbacController@regist_do');//管理注册模块
Route::get('/Rbac/left', 'RbacController@left');//管理注册模块
Route::get('/Rbac/swich', 'RbacController@swich');//管理注册模块
Route::get('/Rbac/mains', 'RbacController@mains');//管理注册模块
Route::get('/Rbac/top', 'RbacController@top');//管理注册模块
Route::get('/Rbac/bottom', 'RbacController@bottom');//管理注册模块
Route::get('Rbac/userlist', 'RbacController@userlist');//管理注册模块
Route::get('Rbac/banner', 'RbacController@banner');//管理注册模块
Route::post('Rbac/banner_info', 'HomePageController@slideshow_add');
Route::get('Rbac/shorts', 'ArticleController@shorts');//短资讯后端添加界面
Route::get('Rbac/shorts_add', 'ArticleController@shorts_add');//短资讯后端添加
Route::get('Rbac/game_video', 'RbacController@game_video');//视频添加
Route::post('Rbac/game_video_info', 'RbacController@game_video_info');//视频添加
Route::get('Rbac/article', 'RbacController@article');//文章添加

