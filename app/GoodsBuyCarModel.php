<?php
namespace App;

use Illuminate\Database\Eloquent\Model;

use DB;
// use Illuminate\Support\Facades\Redis;


class GoodsBuyCarModel extends Model{

    public $_tabName = 'g_buycar';

   /**
    * 添加购物车
    * Author Amber
    * Date 2018-07-24
    * Params [params]
    * @param [type] $user_id  [description]
    * @param [type] $goods_id [description]
    * @param [type] $buy_num  [description]
    */
    public function add_buycar($user_id,$goods_id,$buy_num)
    {
    	$isset = $this->check($user_id,$goods_id);
    	if($isset){
    		$bool = DB::table('g_buycar')->insert(
 				['user_id'=>$user_id,'goods_id'=>$goods_id,'buy_num'=>$buy_num]
 			);
    	    return $bool;
    	}else{
    		 return False;
    	}

    }
/**
 * 查看购物车此商品是否存在  如果存在 就删除
 * Author Amber
 * Date 2018-07-24
 * Params [params]
 * @param  [type] $user_id  [description]
 * @param  [type] $goods_id [description]
 * @param  [type] $buy_num  [description]
 * @return [type]           [description]
 */
    public function check($user_id,$goods_id)
    {
    	   $isset = DB::table($this->_tabName)
    	   	->select('id')
            ->where("goods_id", $goods_id)
            ->where("user_id", $user_id)
            ->first();
         // print_r($isset);die;
         // echo 1;die;
        $iss =  $isset ? get_object_vars($isset) : False;
        // print_r($iss);die;
        $ids = $iss['id'];
        // echo $ids;
        if($ids > 0){
        
            $num = DB::table('g_buycar')->where('id', '=', $ids)->delete();

        	// echo $num;die;
        	 return $num ? true : False;
	     }else{
	     	// echo 2;die;
	     	return true;
	     }
    }

    public function show_buycar($user_id='')
    {
        $objects = DB::table('g_goods')  
             ->select('g_goods.id','g_buycar.buy_num','g_goods.goods_name','g_goods.goods_thumb','g_goods.ruling_price','g_goods.inventory')
             ->join('g_buycar','g_goods.id','=','g_buycar.goods_id')
             ->where('g_buycar.user_id',$user_id)
             ->get();
         $objects = json_decode(json_encode($objects), true);
         return $objects;
    }
/**
 * 删除购物车已经下单的商品 
 * Author Amber
 * Date 2018-07-31
 * Params [params]
 * @param  string $user_id [description]
 * @param  string $skuIds  [description]
 * @return [type]          [description]
 */
    public function delcar($user_id = '' , $skuIds = '')
    {
        $objects = json_decode(json_encode($skuIds), true);
        $objectss = implode($objects,',');
        $del_goods =  DB::delete('delete from g_buycar where user_id = '.$user_id.' and goods_id in ('.$objectss.')');
        return $del_goods;
    }

 }