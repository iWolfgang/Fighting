<?php
namespace App;

use Illuminate\Database\Eloquent\Model;

use DB;
use Illuminate\Support\Facades\Redis;

class ArticleModel extends Model{

    public $_tabName = 't_article';


    /**
     * 获取文章详情 
     * Author Liuran
     * Date 2018-04-10
     * @param  [type] $id [接受的文章id]
     */
    public function article_sms($id)
    {
    	$article = DB::table($this->_tabName)
            ->where("id", $id)
            ->first();
         // print_r($article);die;
         
        return $article ? get_object_vars($article) : False;
    	
    }

    /**
     * 获取文章详情 
     * Author Liuran
     * Date 2018-04-10
     * Params [params]
     * @param  integer $article_id [文章id]
     */
    public function getArticleInfo($article_id = 0)
    {
        $articleInfo = $this->getArticleInfoById($article_id);
        $readCntInfo = $this->getArticleReadCntInfoById($article_id);

        $gameInfo = array();

        if($articleInfo == false){
            $res = array(
                "errNo" => "3001",
                "errMsg" => "文章信息不存在"
            );
            return $res;
        }

        if($articleInfo['fk_game_id'] > 0){
            $gameInfo = $this->getGameInfoByGameId($articleInfo['fk_game_id']);
        }

        $res = array();
        $res['article_info'] = $this->formatArticleInfo($articleInfo);
        $res['read_info'] = $readCntInfo;
        $res['game_info'] = $gameInfo;
        $res['comment_info'] = array();

        $this->incrArticleReadCnt($article_id);

        return $res;
    }

    /**
     * 增加文章阅读数 
     * Author Amber
     * Date 2018-04-12
     * Params [params]
     * @param  integer $article_id [文章id]
     */
    public function incrArticleReadCnt($article_id = 0)
    {
        $key = sprintf("MYAPI_ARTICLE_READ_CNT_%s", $article_id);

        return Redis::incr($key);
    }

    /**
     * 获取文章阅读数 
     * Author Amber
     * Date 2018-04-12
     * Params [params]
     * @param  integer $article_id [文章id]
     */
    public function getArticleReadCnt($article_id = 0)
    {
        $key = sprintf("MYAPI_ARTICLE_READ_CNT_%s", $article_id);

        return intval(Redis::get($key));
    }

    /**
     * 通过文章id获取文章详情 
     * Author Liuran
     * Date 2018-04-10
     * Params [params]
     * @param  integer $article_id [文章id]
     */
    public function getArticleInfoById($article_id = 0)
    {
        $articleInfo = DB::table($this->_tabName)
            ->where("id" , "=", $article_id)
            ->where("article_status", "=", 1)
            ->first();

        return empty($articleInfo) ? false : get_object_vars($articleInfo);
    }

    /**
     * 通过游戏id获取游戏信息 
     * Author Liuran
     * Date 2018-04-10
     * Params [params]
     * @param  integer $game_id [游戏id]
     */
    public function getGameInfoByGameId($game_id = 0)
    {
        $res = array(
            "id" => 1,
            "game_name" => "绝地求生大逃杀",
            "game_thumb" => "http://game.jpg",
            "game_type" => "冒险/解谜/生存",
            "game_platform" => "PS4独占",
            "game_begin_date" => "2018年4月20日"
        );

        return $res;
    }

    /**
     * 通过文章id获取阅读数信息 
     * Author Liuran
     * Date 2018-04-10
     * Params [params]
     * @param  integer $article_id [文章id]
     */
    public function getArticleReadCntInfoById($article_id = 0)
    {
        $res = array(
            "read_count" => $this->getArticleReadCnt($article_id),
            "like_count" => "1"
        );

        return $res;
    }

    /**
     * 格式化代码 
     * Author Liuran
     * Date 2018-04-10
     * Params [params]
     * @param  array  $article_info [文章信息]
     */
    public function formatArticleInfo($article_info = array())
    {
        $res = array();
        $res['title'] = $article_info['article_title'];
        $res['thumb'] = $article_info['article_thumb'];
        $res['author'] = $article_info['article_author'];
        $res['content'] = $article_info['article_content'];

        return $res;
    }
       
}