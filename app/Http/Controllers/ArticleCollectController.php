<?php
namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\ArticleModel;
use App\ArticleCollectModel;
use App\WPModel;
use Illuminate\Support\Facades\DB;
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
        // var_dump($ret);die;
        if($ret == FALSE){
            $res = array(
                "errNo" => "3041",
                "errMsg" => "您还没收藏过,快去收藏吧"
            );
            $this->_response($res);
        }else{
             $res = array(
                "errNo" => "0",
                "errMsg" => "success",
                "data" => $ret
            );
            $this->_response($res);
        }
    }
    /*
     * 数据库demo*
     */
    public function demo_db(){
       // $ret = DB::table('send_code')->select();
         // $ArticleModel = new WPModel();
        $res = DB::connection('mysql_center');
        $res1 = $res->select("SELECT * FROM t_user_info");


        // $ret = $ArticleModel->sel();
        var_dump($res1);die;
        //         $ArticleModel = new ArticleCollectModel();

        // $ret = $ArticleModel->Show_collect_reply(1);
        // var_dump($ret);die;

        $this->_response($res);
    }
}
