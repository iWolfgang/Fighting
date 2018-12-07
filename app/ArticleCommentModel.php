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
	 * 删除评论 
	 * Author Amber
	 * Date 2018-11-27
	 * Params [params]
	 * @param  string $value [description]
	 * @return [type]        [description]
	 */
	 public function deleteComment($comment_id = 0)
	 {
	 	// echo $comment_id;die;
	 	$res = DB::table('t_article_comment')->where('comment_id', $comment_id)->delete();
	 	return $res;
	 }
	/**
	 * 评论点赞
	 *  /usr/local/redis/bin/redis-cli 
	 * Author Amber
	 * Date 2018-04-13
	 * Params [params]
	 * @param integer $user_id    [点赞的用户id]
	 * @param integer $comment_id [点赞的评论id]
	 */
	public function addCommentLike($user_id , $comment_id )
	{
		// echo 1;die;
		// echo $user_id;die;
		$hasLike = $this->checkHasCommentLike($comment_id, $user_id); //用户是否对该条评论有点赞记录
		// dump($hasLike);die;
		if($hasLike){
			$this->unCommentLike($comment_id, $user_id); //取消点赞
		}else{
			$this->commentLike($comment_id, $user_id); //点赞
		}


		$action = $hasLike ? false : true;

		$likeCnt = $this->getCommentLikeCnt($comment_id);

		$res = array(
			"action" => $action,
			"like_cnt" => $likeCnt,
			"comment_id" => $comment_id
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

		return Redis::SISMEMBER($key, $user_id);//sismember
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
	public function addComment($fk_article_id = '',$fk_comment_pid = '',$fk_comment_puid='',$fk_comment_pusername='',$fk_user_id = '',$fk_user_name = '',$comment_content = '',$fk_type_name)
	{
		$data = array();

		$data['fk_article_id'] = $fk_article_id;
		$data['fk_comment_pid'] =$fk_comment_pid ;
		$data['fk_comment_puid'] =$fk_comment_puid;
		$data['fk_comment_pusername'] =$fk_comment_pusername;
		$data['fk_user_id'] = $fk_user_id;
		$data['fk_user_name'] = $fk_user_name;
		$data['comment_content'] = $comment_content;
		$data['fk_article_type'] = $fk_type_name;
		$data['create_time'] = date('Y-m-d H:i:s');
 
		$add = DB::table($this->_tabName)
            ->insert($data);
        if($add){
        	$arr = $this->articleComment_list($fk_article_id,$fk_type_name);
        }
		if($add == false){
            $res = array(
                "errNo" => "1004",
                "errMsg" => "用户评论失败"
            );

            return $res;
        }else{
            $res = array(
                "errNo" => "0",
                "errMsg" => "评论成功",
                "data" => $arr
            );

            return $res;
        }
	}

    /**
     * 通过文章id列出所有评论 
     * Author Amber
     * Date 2018-04-14
     * Params [params]
     * @param string $article_id [文章id]
     */
    public function articleComment_list($article_id,$article_type)
    {
      	// echo $article_id."...........".$article_type;
        $Comment_list = $this->findComment_list($article_id,$article_type);//获取评论
        // print_r($Comment_list);die;
        return $Comment_list;
    }

     public function findComment_list($article_id,$article_type)
    {
        $Comment_list = DB::table($this->_tabName)
	        ->select('comment_id','comment_content','fk_comment_pid','fk_comment_puid','fk_comment_pusername','create_time','t_user_infos.user_id','t_user_infos.user_name','t_user_infos.head_portrait')

	        ->join('t_user_infos','t_article_comment.fk_user_id','=','t_user_infos.user_id')
	        ->where('fk_article_id', $article_id)
	        ->where('fk_comment_pid', 0)
	        ->where('fk_article_type', $article_type)
	        ->orderBy('create_time', 'desc')
	        ->get(); 
        $Comment = json_decode(json_encode($Comment_list), true);
        foreach ($Comment as $key => $value) {
        	$Comment[$key]['like_num'] = $this->getCommentLikeCnt($value['comment_id']);
        	$Comment[$key]['children'] = $this->articleComment_twoList($article_id,$value['comment_id']);
        }
        return $Comment;
    }
    /**
     * 通过文章id列出所有2级评论 
     * Author Amber
     * Date 2018-04-14
     * Params [params]
     * @param string $article_id [文章id]
     */
    public function articleComment_twoList($article_id,$comment_id)
    {
      
        $Comment_list = $this->findComment_twolist($article_id,$comment_id);//获取评论
        return $Comment_list;
    }
     public function findComment_twolist($article_id,$comment_id)
    {
        $Comment_list = DB::table($this->_tabName)
	        ->select('comment_id','comment_content','fk_comment_pid','fk_comment_puid','fk_comment_pusername','create_time','t_user_infos.user_id','t_user_infos.user_name','t_user_infos.head_portrait')
	        ->join('t_user_infos','t_article_comment.fk_user_id','=','t_user_infos.user_id')
	        ->where('fk_article_id', $article_id)
	        ->where('fk_comment_pid', $comment_id)
	        ->orderBy('create_time', 'desc')
	        ->get(); 
        $Comment = json_decode(json_encode($Comment_list), true);
        foreach ($Comment as $key => $value) {
        	$Comment[$key]['like_num'] = $this->getCommentLikeCnt($value['comment_id']);
        }
        return $Comment;
    }

     public function digui($Comment_list,$comment_pid = 0)
    {
    	
    	$list = array();
        foreach ($Comment_list as $row) {
        	
        	if($row['fk_comment_pid'] == $comment_pid){
        		$list[$row['fk_comment_pid']] = $row;
        		print_r($list);die;
        		$children = $this->digui($Comment_list,$row['comment_id']);
        		$children && $list[$row['fk_comment_pid']]['children'] = $children;
        	}
        }
       
        return $list;
    }

     public function digu($Comment_list,$comment_pid = 0)
    {
    	print_r($Comment_list);
    	echo $comment_pid;die;
    	$list = array();
        foreach ($Comment_list as $row) {
        	
        	if($row['fk_comment_pid'] == $comment_pid){
        		$list[$row['comment_id']] = $row;
        		print_r($list);
        		$children = $this->digui($Comment_list,$row['comment_id']);
        		print_r($children);die;
        	}
        	else{
        		echo 2;die;
        	}
        }
    }

/**mn      
 * 回复评论列表
 * Author Amber
 * Date 2018-04-16
 * Params [params]
 * @param  integer $comment_id    [评论id]
 * @param  integer $fk_article_id [文章id]
 * @return [type]                 [description]
 */
    public function art_Com_reply($comment_id = 0 , $fk_article_id = 0)
    {
    	  $ArticleComReply_list = DB::table($this->_tabName)
    	  ->select('comment_content')
    	  ->where('fk_article_id', $fk_article_id)
    	  ->where('fk_comment_id', $comment_id )
    	  ->get(); 
    
    	  return empty($ArticleComReply_list) ? false : $ArticleComReply_list;  
    }
      
}
