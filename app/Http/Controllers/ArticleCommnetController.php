<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\ArticleCommentModel;
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
 * Function 
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
        $fk_article_id = $request->input('fk_article_id');//文章id
        $fk_comment_id = $request->input('fk_comment_id');//评论id
        $fk_user_id =  $this->user_id;

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
                'errMsg' => "评论不能为空"
            );
            $this->_response($res);
        }

        $ArticleComment = new ArticleCommentModel();
        $ret = $ArticleComment->addComment($fk_article_id,$fk_comment_id,$fk_user_id,$comment_content);

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
}
