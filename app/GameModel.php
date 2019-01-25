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
        // echo $game_id;die;
        // $game_info = $this->game_info_msg($game_id);
        // $likeid = $game_info['likeid'];
        // $ids = explode(',',$likeid);
        // $game_correlation = $this->game_correlation($ids);
        $longa_correlation = $this->longa_correlation($game_id);
        $shorta_correlation = $this->shorta_correlation($game_id);//相关的短资讯
        $appraisala_correlation = $this->appraisala_correlation($game_id);//相关的测评
        $video_correlation = $this->video_correlation($game_id);//相关的视频


//liuran        
        $data = array(
            // "game_info" => $game_info,
            // "game_correlation" => $game_correlation,
            "longa_correlation" => $longa_correlation,
            "shorta_correlation" => $shorta_correlation,
            "appraisala_correlation" => $appraisala_correlation,
            "video_correlation" => $video_correlation
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
        // echo $game_id;die;
        $users = DB::table('t_game_main')->select('id','g_cover','imageurl','g_name','g_price','g_content','likeid')->where('id',$game_id)->first();

         return $users ? get_object_vars($users) : False;
    }


/**
 * 相关游戏
 * Author Amber
 * Date 2018-06-19
 * Params [params]
 * @param  [type] $game_name [description]' . $game_name.'
 * @param  [type] $game_type [description]
 */
    public function game_correlation($ids)
    { 
        // echo $ids;die;
        $arr =array();
        foreach ($ids as $k => $v) {
            $arr[] = DB::table($this->_tabName) 
                ->select('id','g_thumb','g_name','g_content')
                // ->where('id','!=',1)
                ->where( 'id',$v)
                ->first();
        }
    	// 
        $data = json_decode(json_encode($arr), true);
        // print_r($data);die;
		return empty($data) ? false : $data;
    }
/**
 * 相关长咨询 
 * Author Amber
 * Date 2018-08-21
 * Params [params]
 * @param string $value [description]
 */
    public function longa_correlation($game_id)
    {
        // echo $game_id;
        $objects = DB::table('t_article')  
                ->select('id','article_thumb','article_title','article_type','created_at')
                ->where('its_type',1)
                ->where('fk_game_id', 'like', '%'.$game_id.'%')
                ->get();
                // print_r($objects);die;
        return empty($objects) ? false : $objects;
    }

/**
 * 相关测评资讯 
 * Author Amber
 * Date 2018-08-21
 * Params [params]
 * @param string $value [description]
 */
    public function appraisala_correlation($game_id)
    {
        // echo $game_id;
        $objects = DB::table('t_article')  
                ->select('id','article_thumb','article_title','article_type','created_at')
                ->where('its_type',2)
                ->where('fk_game_id', 'like', '%'.$game_id.'%')
                ->get();
        return empty($objects) ? false : $objects;
    }


/**
 * 相关视频资讯 根据游戏id划分
 * Author Amber
 * Date 2018-08-21
 * Params [params]
 * @param string $value [description]
 */
    public function video_correlation($game_id)
    {
        // echo $game_id;die;
         $objects = DB::table('t_video')  
            ->select('id','source_img','source','video_text','video_url','created_at')
            ->where('fk_game_id', 'like', '%'.$game_id.'%')
            ->get();
        return empty($objects) ? false : $objects;
    }
/**
 * 相关视频资讯 根据标签划分
 * Author Amber
 * Date 2018-08-21
 * Params [params]
 * @param string $value [description]
 */
    public function videotap_correlation($ids)
    {
        // var_dump($ids);die;
        $a = explode( ',',$ids);
        
        // print_r($a);                           
        $arr =array();$result =array();
        foreach ($a as $k => $v) {
            $arr[] = DB::table('t_video')  
            ->select('id','tapid','video_cover','video_text','video_type','created_at')
            ->where('tapid', 'like', '%'.$v.'%')
            ->limit(4)
            ->get();
        }
       $data = json_decode(json_encode($arr), true);
     // print_r($arr);
       foreach($data as $value){  
            foreach($value as $v){  
                $result[]=$v;
            }  
        }
       $res = array_unique($result, SORT_REGULAR);
       // print_r($res);
       $re=array_splice($res,1);
       // print_r($re);die;
       return empty($re) ? false : $re;
    }

/**
 * 相关短资讯 
 * Author Amber
 * Date 2018-08-21
 * Params [params]
 * @param string $value [description]
 */
    public function shorta_correlation($game_id)
    {
       $objects = DB::table('t_shorts_article')  
        ->select('t_shorts_article.id','source_img','source','title','content','imageurl','t_shorts_article.created_at')
        ->join('t_shorts_img','t_shorts_article.id','=','t_shorts_img.shorts_article_id')
        ->where('fk_game_id', 'like', '%'.$game_id.'%')
        ->get();
       $data = json_decode(json_encode($objects), true);
      
        $imgArr = array();
        foreach ($data as $key => $value) {
          $imgArr[$value['id']][] = $value['imageurl'];
          
        }
        $res = array();
        foreach ($data as $key => $value) {
          $res[$value['id']] = $value;

          $res[$value['id']]['imageurl'] = $imgArr[$value['id']];
        }
         return empty($res) ? false : $res;
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
       $discounts = $this->discounts();//即将上架
       $sell_hot = $this->sell_hot();//热销

       $data = array(
        'in_vogue' => $in_vogue,
        'new_Arrival' => $new_Arrival,
        'discounts' => $discounts,
        'sell_hot' => $sell_hot,
       );
       return empty($data) ? false : $data;
    }
//精品
    public function in_vogue()
    {
       $rate = DB::table('g_product') 
        ->select('id','goods_banner')
        ->where('game_goods',0)
        ->where('is_sift',1)
        ->get();
        // $rate = DB::table('g_product') 
        // ->select('id','g_slideshow')
        // ->where('is_sift',1)
        // ->get();

        $data = json_decode(json_encode($rate), true);
        // dd($data);die;
        return empty($data) ? false : $data;
    }
//上新品
    public function new_Arrival()
    {
       $rate = DB::table('g_product') 
        ->select('id','goods_thumb')
        ->where('game_goods',0)
        ->where('id','!=','1')
        ->orderBy('created_at', 'desc')
        ->get();
        $data = json_decode(json_encode($rate), true);
         // print_r($data);die;*-
        return empty($data) ? false : $data;
    }

//优惠
    public function discounts()
    {
        // echo 1;die;
       $rate = DB::table('g_product') 
        ->select('id','goods_horizontal')
        ->where('game_coupon',1)
        ->where('game_goods',0)
        ->get();
        $data = json_decode(json_encode($rate), true);
        return empty($data) ? false : $data;
    }

//热销
    public function sell_hot()
    {
       $rate = DB::table('g_product') 
        ->select('id','goods_thumb')
        ->where('game_goods',0)
        ->where('id','!=','1')
        ->orderBy('sold_count', 'desc')
        ->get();
        $data = json_decode(json_encode($rate), true);
        return empty($data) ? false : $data;
    }
 }
