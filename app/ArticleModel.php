<?php
namespace App;

use Illuminate\Database\Eloquent\Model;

use DB;
use Illuminate\Support\Facades\Redis;


class ArticleModel extends Model{

    public $_tabName = 't_article';
    const LIKE_ZAN_COUNT = 'Like_zan_%d_%s';//点赞功能
    const LIKE_ZAN_COUNT = 'Look_num_%d_%s';//浏览量功能
    /**
     * 用户点赞 功能
     * Author Amber
     * Date 2018-06-14
     * Params [params]
     * @param [type] $page    [文章id]
     * @param [type] $user_id [用户i
     *d]
     */
    public function Like_zan($user_id,$page,$type)
    {
        
        $isset = $this->Like_zan_isset($page,$user_id,$type);
        if($isset){
            $Like_zan_reduce = $this->Like_zan_reduce($page,$user_id,$type);
            
        }else{

            $Like_zan_add = $this->Like_zan_add($page,$user_id,$type);
        
        }

        $action = $isset ? "un_like" : "like";
        $count = $this->Like_zan_count($page,$user_id,$type);
        
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
    public function Like_zan_isset($page,$user_id,$type)
    {
        $key = sprintf(self::LIKE_ZAN_COUNT,$page,$type);
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
    public function Like_zan_add($page,$user_id,$type)
    {
       $key = sprintf(self::LIKE_ZAN_COUNT,$page,$type);
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
    public function Like_zan_reduce($page,$user_id,$type)
    {
         $key = sprintf(self::LIKE_ZAN_COUNT,$page,$type);
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
    public function Like_zan_count($page,$user_id,$type)
    {
        $key = sprintf(self::LIKE_ZAN_COUNT,$page,$type);
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
        ->select('t_shorts_article.id','title','content','t_shorts_article.created_at','source','source_img','videourl','imageurl','fk_game_id')
        ->join('t_shorts_img','t_shorts_article.id','=','t_shorts_img.shorts_article_id')
        ->where('t_shorts_article.id',$article_id)
        ->first();
       $data = get_object_vars($objects);
       print_r($data);die;
       $str = json_decode($data['imageurl']);
       $data['imageurl'] =  $str;
        $fk_game_id = $data['fk_game_id'];
        $game_info = $this->getGameInfoByGameId($fk_game_id);
        $pinglun_type = 'shorta';
        $comment_info = $this->formArticleComment($article_id,$pinglun_type);
         if($comment_info == false){
           $comment_info = "暂无评论";
        }
        if($game_info == False){
            $res['game_info'] = '未关联游戏';
            
        }

        $res['shortdata'] = $data;
        $res['comment_info'] = $comment_info;
        $res['game_info'] = $game_info;
        return empty($res) ? '未关联游戏' : $res;
     
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
        $pinglun_type = 'longa';
        $comment_info =  $this->formArticleComment($article_id,$pinglun_type);//评论信息
       //
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
        // if($articleInfo['fk_game_id'] == 1){
        //     echo 1;die;
        // }
        if(isset($articleInfo['fk_game_id'])){
            $gameInfo = $this->getGameInfoByGameId($articleInfo['fk_game_id']);//游戏信息
        }
         // print_r($gameInfo);die;
        if($gameInfo == False){
            $gameInfo = "游戏信息不存在";
        }
        $res = array();
        $res['article_info'] = $articleInfo;
        $res['game_info'] = $gameInfo;
        $res['comment_info'] = $comment_info;

        $this->incrArticleReadCnt($article_id);

        return $res;
    }
    /**
     * 获取长评论信息
     * Author Amber
     * Date 2018-06-12
     * Params [params]
     * @param  string $value [description]
     * @return [type]        [description]
     */
    public function formArticleComment($article_id,$pinglun_type)
    {
            $Comment = DB::table('t_article_comment')
            ->select('comment_id','fk_comment_pid','fk_user_id','t_user_infos.head_portrait','t_user_infos.user_name','comment_content','create_time')
            ->join('t_user_infos','t_article_comment.fk_user_id','=','t_user_infos.user_id')
            ->where('fk_article_type',$pinglun_type)
            ->where("fk_article_id", $article_id)
            ->where("fk_comment_pid", 0)
            ->get();
        
        $CommentInfos = json_decode(json_encode($Comment), true);
        // $CommentInfos是一级评论
        $arr = array();
        foreach ($CommentInfos as $key => $value) {
           $obj = DB::table('t_article_comment')
            ->select('comment_id','fk_comment_pid','fk_user_id','t_user_infos.user_name','comment_content')
            ->join('t_user_infos','t_article_comment.fk_user_id','=','t_user_infos.user_id')
            ->where("fk_comment_pid", $value['comment_id'])
            ->get();
           $CommentInfos[$key]['comment_next']  = json_decode(json_encode($obj), true);
        }
        // print_r($CommentInfos);die;
       return $CommentInfos;
    }

     /**
     * 获取短评论信息
     * Author Amber
     * Date 2018-06-12
     * Params [params]
     * @param  string $value [description]
     * @return [type]        [description]
     */
    public function ShortArticleComment($article_id,$pinglun_type)
    {
            $Comment = DB::table('t_article_comment')
            ->select('comment_id','fk_comment_pid','fk_user_id','t_user_infos.head_portrait','t_user_infos.user_name','comment_content','create_time')
            ->join('t_user_infos','t_article_comment.fk_user_id','=','t_user_infos.user_id')
            ->where('fk_article_type',$pinglun_type)
            ->where("fk_article_id", $article_id)
            ->where("fk_comment_pid", 0)
            ->get();
        
        $CommentInfos = json_decode(json_encode($Comment), true);
        // $CommentInfos是一级评论
        $arr = array();
        foreach ($CommentInfos as $key => $value) {
           $obj = DB::table('t_article_comment')
            ->select('comment_id','fk_comment_pid','fk_user_id','t_user_infos.user_name','comment_content')
            ->join('t_user_infos','t_article_comment.fk_user_id','=','t_user_infos.user_id')
            ->where("fk_comment_pid", $value['comment_id'])
            ->get();
           $CommentInfos[$key]['comment_next']  = json_decode(json_encode($obj), true);
        }
       return $CommentInfos;
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
    public function getArticleInfoById($article_id)
    {

        $objects = DB::table('t_article')  
                ->select('id','article_title','article_content','fk_game_id','created_at')
                ->orderBy('created_at', 'desc')
                ->where('id',$article_id)
                ->first();
                $obj = get_object_vars($objects);
        // $objects = json_decode(json_encode($objects), true);
        return empty($obj) ? false : $obj;
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
        $ids = explode(',',$g_id);
        $arr =array();
        foreach ($ids as $k => $v) {
            $arr[] = DB::table('t_game_main') 
                ->select('id','g_thumb','g_name','g_content')
                ->where( 'id',$v)
                ->first();
        }
        $data = json_decode(json_encode($arr), true);
        return empty($data) ? false : $data;

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
//========================================================上边是点赞，下边是浏览=============================================
    /**
     * 浏览量统计 
     * Author Amber
     * Date 2018-11-08
     * Params [params]
     * @param string $value [description]
     */
    public function PageViews($user_ip,$page_id,$type)
    {
        //判断这个IP是否浏览过，第一次浏览+1，第二次就不要加一了
        $gongneng = 2;
        $isset = $this->Like_zan_isset($page,$user_id,$type,$gongneng);
        if($isset){
            $Like_zan_reduce = $this->Like_zan_reduce($page,$user_id,$type);
            
        }else{

            $Like_zan_add = $this->Like_zan_add($page,$user_id,$type);
        
        }
    }

}