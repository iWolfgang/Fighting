<?php
namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\ArticleModel;
use App\funsModel;

class ArticleController extends Controller
{

    public function Like_zan(Request $request)

    {
        // var_dump($request->input("type"));die;
        $user_id =  $request->input('user_id');//用户id

        $page_id = $request->input("page_id");//文章id
        $type = $request->input("type");//文章类型

        $sear = new ArticleModel();
        $ret = $sear->Like_zan($user_id,$page_id,$type);
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
            "errMsg" => "点赞成功",
            "data" => $ret
        );

        
        $this->_response($res);
    }
public function PageViewsd(Request $request)
{
    
    $user_ips = $request->getClientIp();


    // echo $user_ips;
    // $clientIps = array();
    // $ip = $this->server->get('REMOTE_ADDR');
    // $ip = $_SERVER['REMOTE_ADDR'];
    // if (!$this->isFromTrustedProxy()) {
    //     return array($ip);
    // }
    // if (self::$trustedHeaders[self::HEADER_FORWARDED] && $this->headers->has(self::$trustedHeaders[self::HEADER_FORWARDED])) {
    //     $forwardedHeader = $this->headers->get(self::$trustedHeaders[self::HEADER_FORWARDED]);
    //     preg_match_all('{(for)=("?\[?)([a-z0-9\.:_\-/]*)}', $forwardedHeader, $matches);
    //     $clientIps = $matches[3];
    // } elseif (self::$trustedHeaders[self::HEADER_CLIENT_IP] && $this->headers->has(self::$trustedHeaders[self::HEADER_CLIENT_IP])) {
    //     $clientIps = array_map('trim', explode(',', $this->headers->get(self::$trustedHeaders[self::HEADER_CLIENT_IP])));
    // }
    // $clientIps[] = $ip; // Complete the IP chain with the IP the request actually came from
    // $ip = $clientIps[0]; // Fallback to this when the client IP falls into the range of trusted proxies
    // foreach ($clientIps as $key => $clientIp) {
    //     // Remove port (unfortunately, it does happen)
    //     if (preg_match('{((?:\d+\.){3}\d+)\:\d+}', $clientIp, $match)) {
    //         $clientIps[$key] = $clientIp = $match[1];
    //     }
    //     if (IpUtils::checkIp($clientIp, self::$trustedProxies)) {
    //         unset($clientIps[$key]);
    //     }
    // }
    // // Now the IP chain contains only untrusted proxies and the client IP
    // return $clientIps ? array_reverse($clientIps) : array($ip);
}
    /**
     * 浏览量和浏览记录可以一起存
     * Author Amber
     * Date 2018-11-08
     * Params [params]
     * @param Request $Request [description]
     */
    public function PageViews(Request $request)
    {  
        $user_ip = $request->getClientIp(); 
        $page_id = $request->input("page_id");//文章id
        $type = $request->input("type");//文章类型
        $sear = new ArticleModel();
        $ret = $sear->PageViews($page_id,$user_ip,$type);
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
            "data" => $ret
        );        
        $this->_response($res);
    }
    /**
     * 搜索关键字
     * Author Liuran
     * Date 2018-04-09gettype($code);
     * Route::post('/CheckCode/search', 'CheckCodeController@search');
     */
    public function search(Request $request){
        $keyword = $request->input("keyword");
        $user_id = $request->input("user_id");
        if(empty($keyword)){
            
            $res = array(
                "errNo" => "0003",
                "errMsg" => "缺少关键字"
            );
            $this->_response($res);
        } 
        $sear = new funsModel();
        $arr = $sear->search($keyword,$user_id);


        if($arr == FALSE){
            $res = array(
                "errNo" => "0002",
                "errMsg" => "未找到相关游戏"
            );
            $this->_response($res);
        }

        $res = array(
            "errNo" => 0,
            "errMsg" => "success",
            "data" => $arr
        );
          $this->_response($res);
    }

/**
 * 历史搜索 
 * Author Amber
 * Date 2018-06-21
 * Params [params]
 * @param string $value [description]
 */
    public function history_Search(Request $request)
    {
        $user_id = intval($request->input("user_id"));
        $sear = new funsModel();
        $arr = $sear->history_Search($user_id);
        if($arr == FALSE){
            $res = array(
                "errNo" => "0002",
                "errMsg" => "历史记录为空,快搜索吧~"
            );
            $this->_response($res);
        }

        $res = array(
            "errNo" => 0,
            "errMsg" => "success",
            "data" => $arr
        );
          $this->_response($res);


    }

    /**
     * 增加阅读量 
     * Author Amber
     * Date 2018-06-12
     * Params [params]
     * @param Request $request [description]
     */
    public function addArticleRead(Request $request)
    {
        $article_id = intval($request->input("article_id"));
        $ArticleModel = new ArticleModel();

        $ret = $ArticleModel->addArticleRead($article_id);
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
            "errMsg" => "success"
        );

        $this->_response($res);

    }
    /*
    获取短文章详情
     */
    public function getD_ArtInfo(Request $request)
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

        $ret = $ArticleModel->getD_ArtInfo($article_id);

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
    /*
    获取文章详情
     */
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