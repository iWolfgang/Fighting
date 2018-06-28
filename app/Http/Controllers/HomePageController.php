<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Http\Redirect;
use App\HomePageModel;
use App\ArticleModel;

class HomePageController extends Controller
{


/**
 * 首页 
 * Author Amber
 * Date 2018-06-06
 * Params [params]
 * @return [type] [description]
 */
  public function full()
    {
        $HomePageModel = new HomePageModel();
        $ret = array();
        $ret['slideshow'] = $HomePageModel->slideshow();
        $ret['long_articlelist'] = $HomePageModel->long_articlelist();
        $ret['short_articlelist'] = $HomePageModel->short_articlelist();
        $ret['game_videolist'] = $HomePageModel->game_videolist();
        $ret['videolist'] = $HomePageModel->videolist();
        $ret['q_ask'] = $HomePageModel->q_ask();
        
        if($ret == FALSE){
            $res = array(
                "errNo" => "0003",
                "errMsg" => "系统错误"
            );
            $this->_response($res);
        }

        $res = array(
            "errNo" => 0,
            'errMsg' => 'success',
            "data" => $ret
        );

        $this->_response($res);

    }
    /**
     * 首页的轮播图 
     * Author Amber
     * Date 2018-05-08
     */
    public function slideshow(Request $request)
    {

        $HomePageModel = new HomePageModel();

        $ret = $HomePageModel->slideshow($slideshow_type);
     
        if($ret == FALSE){
            $res = array(
                "errNo" => "0003",
                "errMsg" => "轮播图类型不符"
            );
            $this->_response($res);
        }

        $res = array(
            "errNo" => 0,
            'errMsg' => 'success',
            "data" => $ret
        );

        $this->_response($res);

    }
    /**
     * 轮播图 添加
     * Author Amber
     * Date 2018-05-08
     */
    public function slideshow_add(Request $request){

        $slideshow = $request->file("slideshow");
        
        $slideshow_url = $request->input("slideshow_url");
        $slideshow_type = $request->input("slideshow_type");
        $slideshow_title = $request->input("title");

        $HomePageModel = new HomePageModel();

        $ret = $HomePageModel->slideshow_add( $slideshow,  $slideshow_title, $slideshow_url, $slideshow_type);
        if($ret == FALSE){
             echo "<script>alert('添加失败');window.location.href = 'http://api.mithrilgaming.com:8000/Rbac/banner'</script>";
        }else{
            echo "<script>alert('添加成功');window.location.href = 'http://api.mithrilgaming.com:8000/Rbac/banner'</script>";
           

        }

     
    }
/**
 * 长资讯的列表
 * Author Amber
 * Date 2018-06-04
 * Params [params]
 * @return [type] [description]
 */
    public function long_articlelist(Request $request)
    {
        $game_id = $request->input("game_id");
        
        $HomePageModel = new HomePageModel();

        $ret = $HomePageModel->long_articlelist($game_id);

            if($ret == FALSE){
            $res = array(
                "errNo" => "0003",
                "errMsg" => "系统错误"
            );
            $this->_response($res);
        }

        $res = array(
            "errNo" => 0,
            'errMsg' => 'success',
            "data" => $ret
        );

        $this->_response($res);
    }

    public function short_articlelist()
    {
        $HomePageModel = new HomePageModel();

        $ret = $HomePageModel->short_articlelist();

            if($ret == FALSE){
            $res = array(
                "errNo" => "0003",
                "errMsg" => "系统错误"
            );
            $this->_response($res);
        }

        $res = array(
            "errNo" => 0,
            'errMsg' => 'success',
            "data" => $ret
        );

        $this->_response($res);
    }

    public function game_videolist()
    {
        $HomePageModel = new HomePageModel();

        $ret = $HomePageModel->game_videolist();

            if($ret == FALSE){
            $res = array(
                "errNo" => "0003",
                "errMsg" => "系统错误"
            );
            $this->_response($res);
        }

        $res = array(
            "errNo" => 0,
            'errMsg' => 'success',
            "data" => $ret
        );

        $this->_response($res);
    }


    public function videolist()
    {
        $HomePageModel = new HomePageModel();

        $ret = $HomePageModel->videolist();

            if($ret == FALSE){
            $res = array(
                "errNo" => "0003",
                "errMsg" => "系统错误"
            );
            $this->_response($res);
        }

        $res = array(
            "errNo" => 0,
            'errMsg' => 'success',
            "data" => $ret
        );

        $this->_response($res);
    }
/**
 * 视频资讯详情页信息
 * Author Amber
 * Date 2018-06-22
 * Params [params]
 * @param  string $value [description]
 * @return [type]        [description]
 */
    public function video_info(Request $request)
    {
     //  $user_id = $request->input("user_id");$user_id,
        $article_id = $request->input("article_id");
        if($article_id <= 0){
            $res = array(
                "errNo" => "0003",
                "errMsg" => "文章有误"
            );
            $this->_response($res);
        }
        $HomePageModel = new HomePageModel();

        $ret = $HomePageModel->video_info($article_id);
        $g_id = $ret['fk_game_id'];
        $ArticleModel = new ArticleModel();
        $game = $ArticleModel->getGameInfoByGameId($g_id);
        $ret['game'] = $game;
        if($ret == FALSE){
            $res = array(
                "errNo" => "0003",
                "errMsg" => "系统错误"
            );
            $this->_response($res);
        }

        $res = array(
            "errNo" => 0,
            'errMsg' => 'success',
            "data" => $ret
        );

        $this->_response($res);
    }
    public function q_ask()
    {
        $HomePageModel = new HomePageModel();

        $ret = $HomePageModel->q_ask();

            if($ret == FALSE){
            $res = array(
                "errNo" => "0003",
                "errMsg" => "系统错误"
            );
            $this->_response($res);
        }

        $res = array(
            "errNo" => 0,
            'errMsg' => 'success',
            "data" => $ret
        );

        $this->_response($res);
    }
}
