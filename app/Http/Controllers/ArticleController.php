<?php
namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\ArticleModel;
use App\funsModel;

class ArticleController extends Controller
{
    /**
     * 搜索关键字
     * Author Liuran
     * Date 2018-04-09
     * Route::post('/CheckCode/search', 'CheckCodeController@search');
     */
    public function search(Request $request){

        $keyword = $request->input("keyword");
       // var_dump($keyword);die;
        $sear = new funsModel();
        $arr = $sear->search($keyword);

        $res = array(
            "errNo" => 0,
            "errMsg" => "success",
            "data" => $arr
        );

        $this->_response($res);

    }

    /**
     * 文章数据
     * Author Liuran
     * Date 2018-04-10
     * @param string $id [文章id]
     */
//    public function Article_msg(Request $request)
//    {
//        $id = $request->input("id");
//        $sear = new ArticleModel();
//        $arr = $sear->article_sms($id);
//        // var_dump($arr);die;
//        $res = array(
//            "errNo" => 0,
//            "errMsg" => "success",
//            "data" => $arr
//        );
//
//        $this->_response($res);
//    }


    public function getArticleInfo(Request $request)
    {
        $article_id = intval($request->input("article_id"));

        if(empty($article_id)){
            $res = array(
                "errNo" => "0002",
                "errMsg" => "缺少必要的参数"
            );
            $this->_response($res);
        }

        $ArticleModel = new ArticleModel();

        $ret = $ArticleModel->getArticleInfo($article_id);

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
            "errMsg" => "success",
            "data" => $ret
        );

        $this->_response($res);
    }

}