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
 * 短文章详情页 
 * Author Amber
 * Date 2018-06-12
 * Params [params]
 * @param string $value [description]
 */
    public function getD_ArtInfo($value='')
    {
        
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
        $articleInfo = $this->getArticleInfoById($article_id);//文章信息
       // $readCntInfo = $this->getArticleReadCntInfoById($article_id);//阅读数量
        $comment_info =  $this->formArticleComment($article_id);
        //print_r($comment_info);die;
        $gameInfo = array();

        if($articleInfo == false){
            $res = array(
                "errNo" => "3001",
                "errMsg" => "文章信息不存在"
            );
            return $res;
        }

        if(isset($articleInfo[0]['fk_game_name'])){
            $gameInfo = $this->getGameInfoByGameId($articleInfo[0]['fk_game_name']);//游戏信息
        }


        $res = array();
        $res['article_info'] = $this->formatArticleInfo($articleInfo[0]);
        //$res['read_info'] = $readCntInfo;
        $res['game_info'] = $gameInfo;
        $res['comment_info'] = $comment_info;

        $this->incrArticleReadCnt($article_id);

        return $res;
    }
    /**
     * 获取评论信息
     * Author Amber
     * Date 2018-06-12
     * Params [params]
     * @param  string $value [description]
     * @return [type]        [description]
     */
    public function formArticleComment($article_id)
    {
            $article = DB::table('t_article_comment')
            ->select('fk_user_id','comment_content','create_time')
            ->where("fk_article_id", $article_id)
            ->get();
        
        $articleInfos = json_decode(json_encode($article), true);
        // print_r($articleInfos);die;
        //return empty($articleInfo) ? false : $articleInfos;         
       return $articleInfos;
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

        $articleInfo = DB::select('SELECT article_title,fk_game_name,article_content,article_img,article_reading,article_author,article_source,updatetime FROM t_article a JOIN t_article_main b ON a.id = b.m_id where a.id  = :id and a.article_status = 1;', [':id'=>$article_id]);
        $articleInfos = json_decode(json_encode($articleInfo), true);
        // print_r($articleInfos);die;
        return empty($articleInfo) ? false : $articleInfos;
    }

    /**
     * 通过游戏id获取游戏信息 
     * Author Liuran
     * Date 2018-04-10
     * Params [params]
     * @param  integer $game_id [游戏id]
     */
    public function getGameInfoByGameId($game_name)
    {
      $students = DB::select('select * from t_game_info where game_name = ?',[$game_name]); 
      $articleInfos = json_decode(json_encode($students), true);

      return $articleInfos;
    }
/**
 * 增加阅读量
 * Author Amber
 * Date 2018-06-12
 * Params [params]
 * @param integer $article_id [description]
 */
    public function addArticleRead($article_id = 0)
    {
      $students = DB::select('select article_reading from t_article where id = ?',[$article_id]); 
     $articleInfos = json_decode(json_encode($students), true);
      $nums = $articleInfos[0]['article_reading'];
      $new = $nums+1;

      $num = DB::update('update t_article set article_reading = ? where id = ?',[$new,$article_id]);
      if($num == false){
        return false;
      }else{
        return $num;
      }
      
    }

    /**
     * 通过文章id获取阅读数信息 
     * Author Liuran
     * Date 2018-04-10
     * Params [params]
     * @param  integer $article_id [文章id]
     */
    // public function getArticleReadCntInfoById($article_id = 0)
    // {
    //     $res = array(
    //         "read_count" => $this->getArticleReadCnt($article_id),
    //         "like_count" => "1"
    //     );

    //     return $res;
    // }

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
        $res['thumb'] = $article_info['article_img'];
        $res['article_reading'] = $article_info['article_reading'];
        $res['author'] = $article_info['article_author'];
        $res['content'] = $article_info['article_content'];
        $res['fk_game_name'] = $article_info['fk_game_name'];

        return $res;
    }
 
}