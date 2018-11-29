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
        $more = $request->input("more");
        $page = $request->input("page");
        $HomePageModel = new HomePageModel();
        $long_articlelist = $HomePageModel->long_articlelist($more,$page);
        $short_articlelist = $HomePageModel->short_articlelist($more,$page);

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
 * @return [type] [description],$page
 */
    public function long_articlelist(Request $request)
    {
      
        $more = $request->input("more");
        $type = 'long';
        $user_id = 0;//是为了过去点赞数，临时添加的  想要点赞数就别去掉
        $gongneng = 1;

        $HomePageModel = new HomePageModel();
        $ArticleModel = new ArticleModel();

        $data = $HomePageModel->long_articlelist($more);
        foreach ($data as $key => $value) {
          $data[$key]['like_num'] = $ArticleModel->Like_zan_count($value['id'],$user_id,$type,$gongneng);
        }
   

        // print_r($data);die;
            if($data == FALSE){
            $res = array(
                "errNo" => "0003",
                "errMsg" => "内容为空"
            );
            $this->_response($res);
        }

        $res = array(
            "errNo" => 0,
            'errMsg' => 'success',
            "data" => $data
        );

        $this->_response($res);
    }

    public function short_articlelist(Request $request)
    {
        $more = $request->input("more");
        $HomePageModel = new HomePageModel();
        $ArticleModel = new ArticleModel();
        $type = 'short';
        $user_id = 0;//是为了过去点赞数，临时添加的  想要点赞数就别去掉
        $gongneng = 1;
        $ret = $HomePageModel->short_articlelist($more);
        
        foreach ($ret as $key => $value) {
          $ret[$key]['like_num'] = $ArticleModel->Like_zan_count($value['id'],$user_id,$type,$gongneng);
        }
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
        $HomePageModel = new HomePageModel();

        $ret = $HomePageModel->Evaluation_list($more);
        $ArticleModel = new ArticleModel();
        $type = 'long';
        $user_id = 0;//是为了过去点赞数，临时添加的  想要点赞数就别去掉
        $gongneng = 1;
        foreach ($ret as $key => $value) {
          $ret[$key]['like_num'] = $ArticleModel->Like_zan_count($value['id'],$user_id,$type,$gongneng);
        }
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
        $ArticleModel = new ArticleModel();
        $type = 'video';
        $user_id = 0;//是为了过去点赞数，临时添加的  想要点赞数就别去掉
        $gongneng = 1;
        foreach ($ret as $key => $value) {
          $ret[$key]['like_num'] = $ArticleModel->Like_zan_count($value['id'],$user_id,$type,$gongneng);
        }            
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
        $game_id = $video_info['fk_game_id'];
        $ids = explode(',',$game_id);
        $game_info = $GameModel->game_correlation($ids);//相关游戏产品

        $tapids = substr($video_info['tapid'],1,-1);
        $video_like = $GameModel->videotap_correlation($tapids);//相关视频详情

        $ArticleModel = new ArticleModel();
        $pinglun_type = "video";
        $formArticleComment = $ArticleModel->formArticleComment($article_id,$pinglun_type);//评论信息

        $data = array(
            "video_info" => $video_info,
            "game_info" => $game_info,
            "video_like" => $video_like,
            "formArticleComment" => $formArticleComment
        );

        // $this->_response($data);

         $res = array(
            "errNo" => 0,
            'errMsg' => 'success',
            "data" => $data
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
