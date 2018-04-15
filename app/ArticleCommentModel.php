<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

use Illuminate\Support\Facades\Redis;

use DB;

class ArticleCommentModel extends Model
{	
	const COMMENT_LIKE_REDIS_KEY = 'MYAPI_COMMENT_LIKE_%d'; //评论点赞的redis key

	 public $_tabName = 't_article_comment';

	/**
	 * 评论点赞
	 *  /usr/local/redis/bin/redis-cli 
	 * Author Amber
	 * Date 2018-04-13
	 * Params [params]
	 * @param integer $user_id    [点赞的用户id]
	 * @param integer $comment_id [点赞的评论id]
	 */
	public function addCommentLike($user_id = 0, $comment_id = 0)
	{
		$hasLike = $this->checkHasCommentLike($comment_id, $user_id); //用户是否对该条评论有点赞记录

		if($hasLike){
			$this->unCommentLike($comment_id, $user_id); //取消点赞
		}else{
			$this->commentLike($comment_id, $user_id); //点赞
		}


		$action = $hasLike ? "un_like" : "like";

		$likeCnt = $this->getCommentLikeCnt($comment_id);

		$res = array(
			"action" => $action,
			"like_cnt" => $likeCnt
		);

		return $res;
	}

	/**
	 * 检测用户是否对该条评论有点赞记录 
	 * Author Amber
	 * Date 2018-04-13
	 * Params [params]
	 * @param  integer $comment_id [评论id]
	 * @param  integer $user_id    [用户id]
	 */
	public function checkHasCommentLike($comment_id = 0, $user_id = 0)
	{
		$key = sprintf(self::COMMENT_LIKE_REDIS_KEY, $comment_id);

		return Redis::SISMEMBER($key, $user_id);
	}

	/**
	 * 点赞评论 
	 * Author Amber
	 * Date 2018-04-13
	 * Params [params]
	 * @param  integer $comment_id [评论id]
	 * @param  integer $user_id    [用户id]
	 */
	public function commentLike($comment_id = 0, $user_id = 0)
	{
		$key = sprintf(self::COMMENT_LIKE_REDIS_KEY, $comment_id);

		return Redis::SADD($key, $user_id);
	}

	/**
	 * 取消点赞评论 
	 * Author Amber
	 * Date 2018-04-13
	 * Params [params]
	 * @param  integer $comment_id [评论id]
	 * @param  integer $user_id    [用户id]
	 */
	public function unCommentLike($comment_id = 0, $user_id = 0)
	{
		$key = sprintf(self::COMMENT_LIKE_REDIS_KEY, $comment_id);

		return Redis::SREM($key, $user_id);
	}

	/**
	 * 获取评论点赞数 
	 * Author Amber
	 * Date 2018-04-13
	 * Params [params]
	 * @param  integer $comment_id [评论id]
	 */
	public function getCommentLikeCnt($comment_id = 0)
	{
		$key = sprintf(self::COMMENT_LIKE_REDIS_KEY, $comment_id);

		return intval(Redis::SCARD($key));
	}

/**
 * 添加评论 
 * Author Amber
 * Date 2018-04-13
 * Params 
 * @param string $fk_article_id   [文章id]
 * @param string $fk_comment_id   [回复id]
 * @param string $fk_user_id      [用户id]
 * @param string $comment_content [内容]
 */
	public function addComment($fk_article_id = '',$fk_comment_id = '',$fk_user_id = '',$comment_content = '')
	{
		$data = array();

		$data['fk_article_id'] = $fk_article_id;
		$data['fk_comment_id'] = $fk_comment_id;
		$data['fk_user_id'] = $fk_user_id;
		$data['comment_content'] = $comment_content;
		$data['create_time'] = time();
		$add = DB::table($this->_tabName)
            ->insert($data);

		if($add == false){
            $res = array(
                "errNo" => "1004",
                "errMsg" => "用户注册失败"
            );

            return $res;
        }
        return $add;
	}

    /**
     * 通过文章id列出所有评论 
     * Author Amber
     * Date 2018-04-14
     * Params [params]
     * @param string $article_id [文章id]
     */
    public function articleComment_list($article_id)
    {
      
        $article_1_list = $this->articleComment_list1($article_id);
        if(empty($article_1_list)){
            $res = array(
                "errNo" => "0002",
                "errMsg" => "缺少必要的参数"
            );
            $this->_response($res);
        }
        // print_r($article_1_list);die;
        $article_list = $this->articleComment_list2($article_1_list);
        if(empty($article_list)){
            $res = array(
                "errNo" => "0002",
                "errMsg" => "缺少必要的参数"
            );
            $this->_response($res);
        }
		if(empty($article_list)){
            $res = array(
                "errNo" => 0,
            	"errMsg" => "success",
            	"data" => $article_list
            );
            $this->_response($res);
        }
        print_r($article_list);die;
    }

     public function articleComment_list1($article_id)
    {
      // $article_list = DB::table($this->_tabName)->select('fk_article_id','fk_comment_id','comment_content','comment_level','comment_status')->where('fk_article_id', $article_id)->get();
        $article_list = DB::table($this->_tabName)->select('fk_comment_id','comment_content','comment_level')->where('fk_article_id', $article_id)->where('comment_status', 1)->get(); 
       
        return $article_list;
    }
    

     public function articleComment_list2($article_1_list)
    {
        // $article_list = DB::table($this->_tabName)->where('fk_article_id', $article_id)->get();
        
        $arr = array(

        	"head_img" => "baidu.jpg",
        	"nickname" => "然然然",
        );

        $data = array();
        $data = array(
        	""
        );

        foreach($article_list as $K => $v){

        	$data[$k] = $v;
        	$data['fk_comment_id'] = $v['fk_comment_id'];
        	$data['fk_article_id'] = $v['fk_article_id'];
        	$data['fk_article_id'] = $v['fk_article_id'];

        }
        return $article_list;
    }
      
}
