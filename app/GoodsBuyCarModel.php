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

       
            if($isset['buy_num'] > 0){
                    $num = $isset['buy_num'];
                     $bool = DB::table('g_buycar')
                    ->where('user_id',$user_id)
                    ->where('productSku_id',$goods_id)
                    ->update(
                      ['buy_num'=>  $buy_num]
                    );
               return $bool;
            }else{
                // $num = 0;
                 $bool = DB::table('g_buycar')
                    ->insert(['user_id'=>$user_id,'productSku_id'=>$goods_id,'buy_num'=>$buy_num]);
                  return $bool;    
            }
          
           
     

    }
    public function cut_buycar($user_id,$goods_id,$buy_num)
    {
    	$isset = $this->check($user_id,$goods_id);

    	if($isset){
    		$bool = DB::table('g_buycar')
                ->where('user_id',$user_id)
                ->where('productSku_id',$goods_id)
                    ->update(
 				['buy_num'=> $isset['buy_num'] - $buy_num]
 			);
    	    return $bool;
    	}else{
    		 return False;
    	}

    }

/**
 * 查看购物车此商品是否存在  如果存在 就累计！！！
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
    	   	->select('buy_num')
            ->where("productSku_id", $goods_id)
            ->where("user_id", $user_id)
            ->first();
        $iss =  $isset ? get_object_vars($isset) : False;
        return $iss;
      //   $ids = $iss['id'];
      //   if($ids > 0){
      //   $num = DB::table($this->_tabName)->where('id', '=', $ids)->delete();

      //   	return $num ? true : False;
	     // }else{
	     	
	     // 	return true;
	     // }
    }
/**
 * 展示购物车
 * Author Amber
 * Date 2018-10-23
 * 涉及到三个表  商品表 商品sku表 购物车表
 * @param  string $user_id [description]
 * @return [type]          [description]
 */
    public function show_buycar($user_id='')
    {
        
        $objects = DB::table('g_productSkus')
                ->join('g_buycar','g_productSkus.id','=','g_buycar.productSku_id')
                ->join('g_product','g_productSkus.product_id','=','g_product.id')
                ->select('g_buycar.productSku_id as id','g_product.goods_name','g_product.goods_postage','g_productSkus.sku_thumb','g_productSkus.title','g_productSkus.pricenow','g_buycar.buy_num','g_productSkus.stock','g_buycar.user_id')
                ->where('g_buycar.user_id',$user_id)
                ->get();
         $objects = json_decode(json_encode($objects), true);
         return $objects;
         // print_r($objects);die;
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

    public function del_buycar($user_id,$productSku_id)
    {
      
        // $objectss = implode($productSku_id,',');
        $res =  DB::table('g_buycar')
                        ->where('user_id',$user_id)
                        ->where('productSku_id',$productSku_id)
                        ->delete();
             // echo $res;die;             
        return $res;
    }

 }