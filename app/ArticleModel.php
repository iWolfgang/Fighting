<?php
namespace App;

use Illuminate\Database\Eloquent\Model;

use DB;
use Illuminate\Support\Facades\Redis;


class ArticleModel extends Model{

    public $_tabName = 't_article';
    const LIKE_ZAN_COUNT = 'Like_zan_%d';//点赞功能
    /**
     * 用户点赞 功能
     * Author Amber
     * Date 2018-06-14
     * Params [params]
     * @param [type] $page    [文章id]
     * @param [type] $user_id [用户i
     *d]
     */
    public function Like_zan($page,$user_id)
    {
        $isset = $this->Like_zan_isset($page,$user_id);
        
        if($isset){

            $Like_zan_reduce = $this->Like_zan_reduce($page,$user_id);
            
        }else{

            $Like_zan_add = $this->Like_zan_add($page,$user_id);
        
        }

        $action = $isset ? "un_like" : "like";
        $count = $this->Like_zan_count($page,$user_id);
        
        $data = array(
            "action" => $action,
            "count" => $count
        );
        return $data;
    }
    /**
     * 判断用户是否点过赞 
     * Author Amber
     * Date 2018-06-14
     * Params [params]
     * @param [type] $page    [description]
     * @param [type] $user_id [description]
     */
    public function Like_zan_isset($page,$user_id)
    {
        $key = sprintf(self::LIKE_ZAN_COUNT,$page);
        $isset = Redis::SISMEMBER($key,$user_id);
        return $isset;
    }

    /**
     * 点赞 
     * Author Amber
     * Date 2018-06-14
     * Params [params]
     * @param string $value [description]
     */
    public function Like_zan_add($page,$user_id)
    {
        $key = sprintf(self::LIKE_ZAN_COUNT,$page);
        $Like_zan = Redis::SADD($key,$user_id);
        return $Like_zan;
    }

    /**
     * 取消点赞 
     * Author Amber
     * Date 2018-06-14
     * Params [params]
     * @param string $value [description]
     */
    public function Like_zan_reduce($page,$user_id)
    {
        $key = sprintf(self::LIKE_ZAN_COUNT,$page);
        $Like_zan = Redis::SREM($key,$user_id);
        return $Like_zan;
    }
    /**
     * 统计点赞数量 
     * Author Amber
     * Date 2018-06-14
     * Params [params]
     * @param string $value [description]
     */
    public function Like_zan_count($page)
    {
        $key = sprintf(self::LIKE_ZAN_COUNT,$page);
        $Like_zan = count(Redis::SMEMBERS($key));
        return $Like_zan;        
    }



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
 * 短文章详情页 + 游戏模块
 * Author Amber
 * Date 2018-06-12
 * Params [params]
 * @param string $value [description]
 */
    public function getD_ArtInfo($article_id)
    {
      $objects = DB::table('t_shorts_article')  
        ->select('t_shorts_article.id','title','content','updated_at','source','image_url','fk_game_id')
        ->join('t_shorts_img','t_shorts_article.id','=','t_shorts_img.shorts_article_id')
        ->where('t_shorts_article.id',$article_id)
        ->get();
       $data = json_decode(json_encode($objects), true);
      
        $imgArr = array();
        foreach ($data as $key => $value) {
          $imgArr[$value['id']][] = $value['image_url'];
          
        }
//  print_r($imgArr);die;
        $res = array();
        foreach ($data as $key => $value) {
          $res[$value['id']] = $value;

          $res[$value['id']]['image_url'] = $imgArr[$value['id']];
        }
        $fk_game_id = $data[0]['fk_game_id'];
        $game = $this->getGameInfoByGameId($fk_game_id);
        if($game == '游戏信息不存在'){
            $res['game'] = '游戏信息不存在';
            
        }
        $res['game'] = $game;
          return empty($res) ? '游戏信息不存在' : $res;
     
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
        $comment_info =  $this->formArticleComment($article_id);
       //print_r($articleInfo);die;
        $gameInfo = array();

        if($articleInfo == false){                                                                          
            $res = array(
                "errNo" => "3001",
                "errMsg" => "文章信息不存在"
            );
            return $res;
        }
        if($comment_info == false){
           $comment_info = "暂无评论";
        }

        // echo $articleInfo[0]['fk_game_id'];die;
        if(isset($articleInfo[0]['fk_game_id'])){
            $gameInfo = $this->getGameInfoByGameId($articleInfo[0]['fk_game_id']);//游戏信息
        }
        if($gameInfo == '游戏信息不存在'){
            $gameInfo = "游戏信息不存在";
            // $res = array(
            //     "errNo" => "3002",
            //     "errMsg" => "游戏信息不存在"
            // );
            // return $res;
        }
        $res = array();
        $res['article_info'] = $this->formatArticleInfo($articleInfo[0]);
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
        //return empty($articleInfo) ? '游戏信息不存在' : $articleInfos;         
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

        $articleInfo = DB::select('SELECT article_title,fk_game_id,article_thumb,article_content,article_reading,article_author,article_source,updated_at FROM t_article 
        where id  = :id and article_status = 1;', [':id'=>$article_id]);
        // $objects = DB::table('t_article')  
        // ->select('id','article_thumb','article_title','article_type','updatetime','article_source')
        // // ->join('t_article_main','t_article.id','=','t_article_main.m_id')
        // ->limit(9)
        // ->get();
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
    public function getGameInfoByGameId($g_id)
    {
        // echo $game_name;die;
        $students = DB::table('t_game_main')
        ->select( 'g_name','g_thumb','g_meta_information','g_type','g_update')
        ->where("id", $g_id)
        ->first();
        // print_r($students);die;
       return $students ? get_object_vars($students) : '游戏信息不存在';

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
        $res['article_reading'] = $article_info['article_reading'];
        $res['author'] = $article_info['article_author'];
        $res['content'] = $article_info['article_content'];
        $res['fk_game_id'] = $article_info['fk_game_id'];

        return $res;
    }
 
}