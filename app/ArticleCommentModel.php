<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

use Illuminate\Support\Facades\Redis;

class ArticleCommentModel extends Model
{	
	const COMMENT_LIKE_REDIS_KEY = 'MYAPI_COMMENT_LIKE_%d'; //评论点赞的redis key

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

		return Redis::SCARD($key);
	}
}
