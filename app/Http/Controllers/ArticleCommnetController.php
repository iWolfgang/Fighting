<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\ArticleCommentModel;
use Illuminate\Support\Facades\DB;
class ArticleCommnetController extends Controller
{	
	/**
	 * 评论点赞 
	 * Author Amber
	 * Date 2018-04-13
	 */
	public function addCommentLike(Request $request)
	{
		$user_id = $this->user_id;
        
		$comment_id = intval($request->input("comment_id"));
		if(empty($comment_id)){
            $res = array(
                "errNo" => "0002",
                "errMsg" => "评论id格式不正确"
            );
            $this->_response($res);
        }
		$ArticleCommentModel = new ArticleCommentModel();

		$ret = $ArticleCommentModel->addCommentLike($user_id, $comment_id);

		if($ret == FALSE){
            $res = array(
                "errNo" => "0003",
                "errMsg" => "系统错误"
            );
            $this->_response($res);
        }elseif(isset($ret['errNo'])){
            $this->_response($ret);
        }

        $res = array(
            "errNo" => 0,
            "errMsg" => "评论点赞成功",
            "data" => $ret
        );

        $this->_response($res);
	}
    /**
     * 删除评论
     * Author Amber
     * Date 2018-11-27
     * Params [params]
     * @param string $value [description]
     */
    public function DeleteComment(Request $request)
    {
        $fk_comment_id = $request->input('comment_id');//评论id
        // echo $fk_comment_id;die;
        $ArticleComment = new ArticleCommentModel();
        $ret = $ArticleComment->deleteComment($fk_comment_id);
        if($ret == false){
            $res = array(
                'errNo' => "0003",
                'errMsg' => "删除失败"
            );
            $this->_response($res);
        }
        $res = array(
            'errNo' => 0,
            'errMsg' => "删除成功"
        );
        $this->_response($res);
    }

/**
 *添加评论 
 * Author Amber
 * Date 2018-04-13
 * Params [params]
 * @param string $value [description]
 * @param string $value [description]
 * @param string $value [description]
 * @param string $value [description]
 */
    public function addComment(Request $request)
    {
        
        $fk_type_name = $request->input('type_name');//评论类型名称
        $fk_article_id = $request->input('article_id');//文章id
        $fk_comment_pid = $request->input('fk_comment_pid');//评论id
        // echo $fk_comment_pid;die;
        $fk_user_id = $request->input('user_id');

        $comment_content = $request->input('comment_content');//评论信息
       

        //校验
        if(empty($fk_article_id)){
            $res = array(
                'errNo' => "0002",
                'errMsg' => "文章不存在"
            );
            $this->_response($res);
        }
        if(empty($comment_content) || mb_strlen($comment_content) > 500){
              $res = array(
                'errNo' => "0002",
                'errMsg' => "评论不能为空and超过100字"
            );
            $this->_response($res);
        }
        $ArticleComment = new ArticleCommentModel();
        $ret = $ArticleComment->addComment($fk_article_id,$fk_comment_pid,$fk_user_id,$comment_content,$fk_type_name);
        if($ret == false){
            $res = array(
                'errNo' => "0003",
                'errMsg' => "系统错误"
            );
            $this->_response($res);
        }else if(isset($ret['errNo'])){
            $this->_response($ret);
        }

        $res = array(
            'errNo' => 0,
            'errMsg' => "评论成功"
        );
        $this->_response($res);
    }

    /**
     * 文章评论列表
     * Author Liuran
     * Date 2018-04-10
     * @param string $id [文章id]
     */
    public function ArticleCommnet_list(Request $request)
    {
        // echo 1;die;
        
        $article_id = intval($request->input("article_id"));//文章id
        $article_type = $request->input("article_type");//文章类型

        if(empty($article_id)|| empty($article_type)){
            $res = array(
                "errNo" => "0002",
                "errMsg" => "缺少必要的参数"
            );
            $this->_response($res);
        } 
        $ArticleModel = new ArticleCommentModel();

        $ret = $ArticleModel->articleComment_list($article_id,$article_type);
        if($ret == FALSE){
            $res = array(
                "errNo" => "0003",
                "errMsg" => "暂无评论"
            );
            $this->_response($res);
        }
        $res = array(
            "errNo" => 0,
            "errMsg" => "success",
            "data" => $ret
        );

        $this->_response($res);

    }
    /**
     * 文章评论列表
     * Author Liuran
     * Date 2018-04-10
     * @param string $id [文章id]
     */
    public function ArticleCommnet_twoList(Request $request)
    {
        // echo 1;die;
        
        $article_id = intval($request->input("article_id"));//文章id
        $comment_id = $request->input("comment_id");//文章类型

        if(empty($article_id)|| empty($comment_id)){
            $res = array(
                "errNo" => "0002",
                "errMsg" => "缺少必要的参数"
            );
            $this->_response($res);
        } 
        $ArticleModel = new ArticleCommentModel();

        $ret = $ArticleModel->articleComment_twoList($article_id,$comment_id);
        if($ret == FALSE){
            $res = array(
                "errNo" => "0003",
                "errMsg" => "暂无评论"
            );
            $this->_response($res);
        }
        $res = array(
            "errNo" => 0,
            "errMsg" => "success",
            "data" => $ret
        );

        $this->_response($res);

    }

    /**
     * 删除评论
     * Author Amber
     * Date 2018-04-15
     * int [id]
     * @param Request $request [获取文章id]
     */
    public function ArticleDel(Request $request)
    {
        $id = $request['id'];  

        $ret = DB::table('t_article_comment')->where('id', '=', $id)->delete();

        if($ret == FALSE){
            $res = array(
                "errNo" => "0003",
                "errMsg" => "删除失败"
            );
            $this->_response($res);
        }

        $res = array(
            "errNo" => 0,
            "errMsg" => "删除成功",
        );

        $this->_response($res);
    }

    /**
     * 评论回复列表
     * Author Amber
     * Date 2018-04-16
     */
    public function Art_Com_reply(Request $request)
    {
        $comment_id = $request['comment_id'];
        $fk_article_id = $request['fk_article_id'];

        if(empty($comment_id) || empty($fk_article_id)){
            $res = array(
                "errNo" => "0002",
                "errMsg" => "缺少必要的参数"
            );
            $this->_response($res);
        }

        $Art_ComModel = new ArticleCommentModel();

        $ret = $Art_ComModel -> art_Com_reply( $comment_id, $fk_article_id );

        // print_r($ret);exit;
        if($ret == FALSE){
            $res = array(
                "errNo" => "0003",
                "errMsg" => "系统错误"
            );
            $this->_response($res);
        }
        $res = array(
            "errNo" => 0,
            "errMsg" => "success",
            "data" => $ret
        );

        $this->_response($res);
    }

    
}
