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
    	// $isset = DB::table($this->_tabName)
    	//    	->select('user_id','goods_id','buy_num')
     //        ->where("user_id", $user_id)
     //        ->get();

     //    $data = json_decode(json_encode($isset), true);
      // print_r($data);die;
        // $imgArr = array();
        // foreach ($data as $key => $value) {
        //   $imgArr[$value['goods_id']] = $value['goods_id'];
        // }
 $objects = DB::table('g_goods')  
        ->select('g_goods.id','g_buycar.buy_num','g_goods.goods_name','g_goods.goods_thumb','g_goods.ruling_price','g_goods.inventory')
        ->join('g_buycar','g_goods.id','=','g_buycar.goods_id')
        ->where('g_buycar.user_id',$user_id)
        // ->limit(9)
        ->get();
         $objects = json_decode(json_encode($objects), true);
         return $objects;
 // print_r($objects);die;


        //  $imgArr = array();
        // foreach ($objects as $key => $value) {
        //   $imgArr[$value['goods_id']] = $value['goods_id'];
        // }



 // $articleInfo = DB::select("SELECT t1.buy_num,t2.goods_name,t2.ruling_price,t2.goods_code,t2.inventory FROM g_buycar t1 LEFT JOIN g_goods t2 ON t1.goods_id = t2.id WHERE t1.user_id = 1");
 //        $articleInfos = json_decode(json_encode($articleInfo), true);

// print_r($articleInfos);die;




        // $comma_separated = implode(",", $imgArr);
        //  $data = DB::select ("select id,goods_name,ruling_price,goods_code,inventory from g_goods where id in ($comma_separated)");
        //  return empty($data) ? false : $data;

        
       // SQLSTATE[42000]: Syntax error or access violation: 1064 You have an error in your SQL syntax; check the manual that corresponds to your MariaDB server version for the right syntax to use near 'FROM 'g_buycar' as t1 LEFT JOIN 'g_goods' as 't2' ON 't1.goods_id' = 't2.id' WHE' at line 1 (SQL:
       //  SELECT FROM 'g_buycar' as t1 LEFT JOIN 'g_goods' as 't2' ON 't1.goods_id' = 't2.id' WHERE 't1.user_id' = 1)

    }

 }