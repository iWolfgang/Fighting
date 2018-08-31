<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Http\Redirect;
use App\HomePageModel;
use App\ArticleModel;
use App\GameModel;

class HomePageController extends Controller
{


/**
 *混连资讯
 * Author Amber
 * Date 2018-06-06
 * Params [params]
 * @return [type] [description]
 */
  public function full(Request $request)
    {
        // $game_id = $request->input("game_id");,$game_id
        $more = $request->input("more");
        $HomePageModel = new HomePageModel();
        $long_articlelist = $HomePageModel->long_articlelist($more);
        $short_articlelist = $HomePageModel->short_articlelist($more);

       // $videolist = $HomePageModel->videolist($more);,$videolist
        $ret = array_merge($long_articlelist,$short_articlelist);
        $orderFile = array();
            foreach($ret as $vo){
               $orderFile[]=$vo['created_at'];
               }
            array_multisort($orderFile ,SORT_DESC, $ret);
            $order = array_values($ret);
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

        $ret = $HomePageModel->slideshow();
     
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
        $slideshow_title = $request->input("title");
        $slideshow_type = $request->input("slideshow_type");
        $slideshow_url = $request->input("slideshow_url");
        
        

        $HomePageModel = new HomePageModel();

        $ret = $HomePageModel->slideshow_add( $slideshow,  $slideshow_title, $slideshow_url, $slideshow_type);
        if($ret == FALSE){
             echo "<script>alert('添加失败');window.location.href = 'http://api.mithrilgaming.com/Rbac/banner'</script>";
        }else{
            echo "<script>alert('添加成功');window.location.href = 'http://api.mithrilgaming.com/Rbac/banner'</script>";
           

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
        // $game_id = $request->input("game_id");
        $more = $request->input("more");
        $HomePageModel = new HomePageModel();

        $ret = $HomePageModel->long_articlelist($more);

            if($ret == FALSE){
            $res = array(
                "errNo" => "0003",
                "errMsg" => "内容为空"
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

    public function short_articlelist(Request $request)
    {
        $more = $request->input("more");
        // echo $more;die;
        $HomePageModel = new HomePageModel();

        $ret = $HomePageModel->short_articlelist($more);

            if($ret == FALSE){
            $res = array(
                "errNo" => "0003",
                "errMsg" => "内容为空"
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

    public function Evaluation_list(Request $request)
    {
        $more = $request->input("more");
        // echo $more;die;
        $HomePageModel = new HomePageModel();

        $ret = $HomePageModel->Evaluation_list($more);

            if($ret == FALSE){
            $res = array(
                "errNo" => "0003",
                "errMsg" => "内容为空"
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


    public function videolist(Request $request)
    {
        // 
        $more = $request->input("more");
        $HomePageModel = new HomePageModel();

        $ret = $HomePageModel->videolist($more);

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
                "errMsg" => "文章不存在"
            );
            $this->_response($res);
        }
        $HomePageModel = new HomePageModel();
        $GameModel = new GameModel();

        $video_info = $HomePageModel->video_info($article_id);//视频详情
        // print_r($video_info);die;
        $game_id = $video_info['fk_game_id'];
        $ids = explode(',',$game_id);
        $game_info = $GameModel->game_correlation($ids);//相关游戏产品

        $tapids = substr($video_info['tapid'],1,-1);
        $video_like = $GameModel->videotap_correlation($tapids);//相关视频详情

        $ArticleModel = new ArticleModel();
        $pinglun_type = "video";
        $formArticleComment = $ArticleModel->formArticleComment($article_id,$pinglun_type);//评论信息

        // if($video_info == FALSE){
        //     $res = array(
        //         "errNo" => "0003",
        //         "errMsg" => "没有此视频"
        //     );
        //     $this->_response($res);
        // }
        // if($formArticleComment == FALSE){
        //     $res = array(
        //         "errNo" => "0003",
        //         "errMsg" => "暂无评论"
        //     );
        //     $this->_response($res);
        // }
        $res = array(
            "errNo" => 0,
            'errMsg' => 'success',
            "video_info" => $video_info,
            "game_info" => $game_info,
            "video_like" => $video_like,
            "formArticleComment" => $formArticleComment
        );

        $this->_response($res);
    }
    public function q_question(){
        $HomePageModel = new HomePageModel();

        $ret = $HomePageModel->q_question();

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
    public function q_ask(Request $request)
    {
     //  $user_id = $request->input("user_id");$user_id,
        $id = $request->input("id");
        $HomePageModel = new HomePageModel();

        $ret = $HomePageModel->q_ask($id);

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
