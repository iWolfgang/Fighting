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
}
