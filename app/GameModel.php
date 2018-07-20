<?php
namespace App;

use Illuminate\Database\Eloquent\Model;

use DB;
use Illuminate\Support\Facades\Redis;


class GameModel extends Model{

    public $_tabName = 't_game_main';

    //$user_id,$user_id,
    public function game_info($game_id)
    {
    	$game_info = $this->game_info_msg($game_id);
    	//print_r($game_info);die;
    	$game_name = $game_info['g_name'];
    	$game_type = $game_info['g_type'];
    	//$game_news = $this->game_news($game_id);
    	$game_correlation = $this->game_correlation($game_name,$game_type);

    	
    	$data = array(
            "game_info" => $game_info,
          //  "game_news" => $game_news,
            "game_correlation" => $game_correlation
        );
        return $data;
    }
/**
 * 游戏主信息 
 * Author Amber
 * Date 2018-06-19
 * Params [params]
 * @param  [type] $user_id [description]
 * @param  [type] $game_id [description]$user_id,
 */
    public function game_info_msg($game_id)
    {
    	$users = DB::table('t_game_main')->select()->where('id',$game_id)->first();

    	 return $users ? get_object_vars($users) : False;
    }
/**
 * 游戏资讯 
 * Author Amber
 * Date 2018-06-19
 * Params [params]
 * @param  string $value [description]
 * @return [type]        [description],'article_type','updatetime','article_source'
 */
  //   public function game_news($game_id)
  //   {

	 //   // $users = DB::table('t_game_main')->select('article_thumb','article_title')->where('id',$game_id)->first();

  //       $objects = DB::table('t_article')  
		// 	    ->select('article_thumb','article_title')
		// 	    ->where('fk_game_id',$game_id)
		// 	    ->join('t_article_main','t_article.id','=','t_article_main.m_id')
		// 	    ->get();
		// $data = json_decode(json_encode($objects), true);
  //       // return $objects ? get_object_vars($objects) : False;
		// return empty($data) ? false : $data;
  //   }

/**
 * 相关游戏
 * Author Amber
 * Date 2018-06-19
 * Params [params]
 * @param  [type] $game_name [description]
 * @param  [type] $game_type [description]
 */
    public function game_correlation($game_name,$game_type)
    {
    	$rate = DB::table($this->_tabName) 
    	->select('g_thumb','g_name')
    	->where('g_type', $game_type)
        ->where( 'g_name','like', '%' . $game_name . '%')
        ->get();
        $data = json_decode(json_encode($rate), true);
        // return $objects ? get_object_vars($objects) : False;
		return empty($data) ? false : $data;
    }

/**
 * 游戏列表页
 * Author Amber
 * Date 2018-06-20
 * Params [params]
 * @return [type] [description]
 */
    public function game_list()
    {
       $in_vogue = $this->in_vogue();//流行精品
       $new_Arrival = $this->new_Arrival();//最新上架
       $be_up_game = $this->be_up_game();//即将上架

       $data = array(
        'in_vogue' => $in_vogue,
        'new_Arrival' => $new_Arrival,
        'be_up_game' => $be_up_game
       );
       return empty($data) ? false : $data;
    }

    public function in_vogue()
    {
       $rate = DB::table($this->_tabName) 
        ->select('id','g_thumb','g_name')
        ->where('g_fashion',1)
        ->limit(6)
        ->get();
        $data = json_decode(json_encode($rate), true);
        return empty($data) ? false : $data;
    }

    public function new_Arrival()
    {
       $rate = DB::table($this->_tabName) 
        ->select('id','g_thumb','g_name')
        ->where('g_update','<', time())
        ->limit(6)
        ->get();
        $data = json_decode(json_encode($rate), true);
        return empty($data) ? false : $data;
    }

    public function be_up_game()
    {
       $rate = DB::table($this->_tabName) 
        ->select('id','g_thumb','g_name')
        ->where('g_update','>', time())
        ->limit(6)
        ->get();
        $data = json_decode(json_encode($rate), true);
        return empty($data) ? false : $data;
    }
 }