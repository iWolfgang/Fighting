<?php
namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\ArticleModel;
use App\ArticleCollectModel;

class ArticleCollectController extends Controller
{
	/**
	 * 文章收藏 
	 * Author Amber
	 * Date 2018-04-17
	 * Params [params]
	 * @param Request $request [description]
	 */
	public function Art_col(Request $request)
	{
		$article_id = intval($request->input("article_id"));
		// $user_id = intval($request->input("token"));
		$user_id = intval($request->input("user_id"));
        if(empty($article_id)){
            $res = array(
                "errNo" => "0002",
                "errMsg" => "缺少必要的参数"
            );
            $this->_response($res);
        }

        $ArticleModel = new ArticleCollectModel();

        $ret = $ArticleModel->Add_collect($article_id,$user_id);
        if($ret == FALSE){
            $res = array(
                "errNo" => "0003",
                "errMsg" => "系统错误"
            );
            $this->_response($res);
        }elseif(isset($ret['errNo'])){
            $this->_response($ret);
        }

	}

    /**
     * 文章收藏列表 
     * Author Amber
     * Date 2018-04-19
     * Params [params]
     * @param  Request $Request [description]
     * @return [type]           [description]
     */
    public function Art_col_reply(Request $Request)
    {
        $user_id = $Request->input("user_id");

        if(empty($user_id)){
            $res = array(
                "errNo" => "0002",
                "errMsg" => "缺少必要的参数"
            );
            $this->_response($res);
        }

        $ArticleModel = new ArticleCollectModel();

        $ret = $ArticleModel->Show_collect_reply($user_id);
        if($ret == FALSE){
            $res = array(
                "errNo" => "0003",
                "errMsg" => "系统错误"
            );
            $this->_response($res);
        }elseif(isset($ret['errNo'])){
            $this->_response($ret);
        }
    }
}
