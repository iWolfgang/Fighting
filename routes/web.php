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

// Route::get('/', function () {
//     return view('demo',['name' => '早上好']);
// });

Route::get('/ssl', array('https' => true, function() {
        return View('welcome');
    })
);
Route::post('/SmsCode/sendCode', 'SmsCodeController@sendCode');//发送短信验证码
Route::post('/SmsCode/checkCode', 'SmsCodeController@checkCode');//校验验证码是否正确
Route::post('/User/regist', 'UserController@regist');//用户注册
Route::post('/User/login', 'UserController@login');//用户登录
Route::post('/User/userinfo', 'UserController@userinfo');//用户信息
Route::post('/User/userinfo_add', 'UserController@userinfo_add');//用户信息补全
Route::post('/User/update_mobile', 'UserController@update_mobile');//用户修改手机号
Route::get('/User/apijson', 'UserController@apijson');
Route::post('/CheckCode/formatPY', 'ArticleController@formatPY');//搜索
Route::post('/CheckCode/search', 'ArticleController@search');//搜索
Route::get('/CheckCode/history_Search', 'ArticleController@history_Search');//搜索


Route::get('/Article/getArticleInfo', 'ArticleController@getArticleInfo');//长文章详情页信息
Route::post('/Article/addArticleRead', 'ArticleController@addArticleRead');//文章阅读量
Route::get('/Article/getD_ArtInfo', 'ArticleController@getD_ArtInfo');//短资讯文章详情页信息
Route::get('/Game/game_Info', 'GameController@game_info');//游戏详情页
Route::get('/HomePage/video_info', 'HomePageController@video_info');//视频资讯详情页信息

Route::post('/ArticleComment/addCommentLike', 'ArticleCommnetController@addCommentLike');//点赞
Route::post('/ArticleComment/addComment', 'ArticleCommnetController@addComment');//添加评论
Route::get('/ArticleComment/ArticleComment_list', 'ArticleCommnetController@articleCommnet_list');//一级评论列表
Route::get('/ArticleComment/ArticleComment_twoList', 'ArticleCommnetController@ArticleCommnet_twoList');//二级评论列表
Route::delete('/ArticleComment/DeleteComment', 'ArticleCommnetController@DeleteComment');//二级评论列表
// ======
Route::post('/ArticleComment/ArticleDel', 'ArticleCommnetController@ArticleDel');//删除文章
Route::post('/ArticleComment/Art_Com_reply', 'ArticleCommnetController@Art_Com_reply');//回复列表
Route::post('/ArticleCollect/Art_col', 'ArticleCollectController@Art_col');//文章收藏
Route::post('/ArticleCollect/Art_col_reply', 'ArticleCollectController@Art_col_reply');//文章收藏列表
Route::get('/ArticleCollect/demo_db', 'ArticleCollectController@demo_db');//同时连接两个db模拟

Route::get('/HomePage/slideshow', 'HomePageController@slideshow');//轮播图展示
Route::get('/HomePage/long_articlelist', 'HomePageController@long_articlelist');//长资讯列表展示
Route::get('/HomePage/Evaluation_list', 'HomePageController@Evaluation_list');//测评列表展示
Route::get('/HomePage/short_articlelist', 'HomePageController@short_articlelist');//短资讯列表展示D
// Route::get('/HomePage/game_videolist', 'HomePageController@game_videolist');//游戏视频列表展示
Route::get('/HomePage/videolist', 'HomePageController@videolist');//视频资讯列表展示
Route::post('/HomePage/full', 'HomePageController@full');//资讯混合页
Route::get('/HomePage/q_question', 'HomePageController@q_question');//问列表展示
Route::get('/HomePage/q_ask', 'HomePageController@q_ask');//答列表展示
Route::get('/HomePage/full', 'HomePageController@full');//首页展示
Route::post('/Article/Like_zan', 'ArticleController@Like_zan');//点赞 
Route::post('/Article/PageViews', 'ArticleController@PageViews');//浏览量
Route::get('/Game/game_list', 'GameController@game_list');//游戏列表页展示
Route::get('/Game/in_vogue', 'GameController@in_vogue');//游戏列表 精品 页展示
Route::get('/Game/new_Arrival', 'GameController@new_Arrival');//游戏列表 新品 页展示
Route::get('/Game/discounts', 'GameController@discounts');//游戏列表 优惠 页展示
Route::get('/Game/sell_hot', 'GameController@sell_hot');//游戏列表 热销 页展示


Route::get('/Lpush/push', 'LpushController@push');//推送
// ===========================================电商=========电商================================================================
Route::get('/Goods/slideshow', 'GoodsController@slideshow');//商品页的轮播图
Route::get('/Goods/goods_list', 'GoodsController@goods_list');//商品列表
Route::get('/Goods/subject_goods', 'GoodsController@subject_goods');//商品列表

Route::get('/GoodsCat/homepage_list', 'GoodsCatController@homepage_list');//电商一级分类列表列表
Route::get('/GoodsCat/homepagetwo_list', 'GoodsCatController@homepagetwo_list');//电商二级分类列表列表
Route::get('/Goods/detail_page', 'GoodsController@detail_page');//商品详情页
Route::get('/GoodsBuyCar/willJoin_Buycart', 'GoodsBuyCarController@willJoin_Buycart');//添加购物车
Route::post('/GoodsBuyCar/add_buycar', 'GoodsBuyCarController@add_buycar');//添加购物车
Route::post('/GoodsBuyCar/show_buycar', 'GoodsBuyCarController@show_buycar');//展示购物车


Route::post('/Order/creat_orders', 'OrderController@creat_orders');//创建订单 


Route::post('/Order/PlaceOrder', 'CreatOrderController@PlaceOrder');//创建订单
Route::post('/Order/wait_paylist','OrderController@wait_paylist');//待付款订单列表
Route::post('/Order/wait_pay', 'OrderController@wait_pay');//待付款订单详情页
Route::post('/Order/wait_sendlist', 'OrderController@wait_sendlist');//待发货列表
Route::get('/Order/wait_senditem', 'OrderController@wait_senditem');//待发货详情页
Route::get('/Logistics/selectLog', 'LogisticsController@selectLog');//查看物流
//===================================支付====================
Route::get('alipay', function() {
    return app('alipay')->web([
        'out_trade_no' => time(),
        'total_amount' => '1',
        'subject' => 'test subject - 测试',
    ]);
});
Route::get('/Pay/index', 'PayController@index');//支付宝
Route::get('/Pay/notify', 'PayController@notify');//支付宝回调
Route::get('/WePay/index', 'WePayController@index');//微信支付
Route::get('/WePay/rollback', 'WePayController@rollback');//微信回调
Route::get('/WePay/getkeys', 'WePayController@getkeys');//sign







Route::get('/Rbac/index', 'RbacController@index');//管理登陆界面
Route::post('/Rbac/login', 'RbacController@login');//管理登陆模块
Route::get('/Rbac/main', 'RbacController@main');//管理主模块
Route::get('/Rbac/regist', 'RbacController@regist');//管理注册模块
Route::post('/Rbac/regist_do', 'RbacController@regist_do');//管理注册模块
Route::get('/Rbac/left', 'RbacController@left');//管理注册模块
Route::get('/Rbac/swich', 'RbacController@swich');//管理注册模块
Route::get('/Rbac/mains', 'RbacController@mains');//管理注册模块
Route::get('/Rbac/top', 'RbacController@top');
Route::get('/Rbac/bottom', 'RbacController@bottom');
Route::get('Rbac/userlist', 'RbacController@userlist');
Route::get('Rbac/banner', 'HomePageController@banner');//添加轮播图
Route::post('Rbac/banner_info', 'HomePageController@slideshow_add');//添加轮播图
Route::get('Rbac/banner', 'RbacController@banner');//管理注册模块
Route::post('Rbac/banner_info', 'HomePageController@slideshow_add');
Route::get('Rbac/shorts', 'ArticleController@shorts');//短资讯后端添加界面
Route::get('Rbac/shorts_add', 'ArticleController@shorts_add');//短资讯后端添加
Route::get('Rbac/game_video', 'RbacController@game_video');//视频添加
Route::post('Rbac/game_video_info', 'RbacController@game_video_info');//视频添加
Route::get('Rbac/article', 'RbacController@article');//文章添加
Route::post('Rbac/article_add', 'RbacController@article_add');//文章添加

