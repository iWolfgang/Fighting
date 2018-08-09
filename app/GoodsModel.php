<?php
namespace App;

use Illuminate\Database\Eloquent\Model;

use DB;
use Illuminate\Support\Facades\Redis;


class GoodsModel extends Model{

    public $_tabName = 'g_goods';
    // const LIKE_ZAN_COUNT = 'Like_zan_%d';//点赞功能
    /**
     * 商品列表页 功能
     * Author Amber
     * Date 2018-07-23
     * Params [params]
     * @param [type] $page    [文章id]
     * @param [type] $user_id [用户i
     *d]
     */
    public function GoodsList($classify_id)
    {
        $isset = $this->only_this_goodslist($classify_id);
        return $isset;
    }
    public function only_this_goodslist($classify_id='')
    {
        $article = DB::table($this->_tabName)
            ->select('id','goods_name','goods_thumb')
            ->where("goods_cat", $classify_id)
            ->get();
         // print_r($article);die;
        $data = json_decode(json_encode($article), true);

        return $data ? $data : False;        
    }

    public function detail_page($goods_id='')
    {
        $data = DB::table($this->_tabName)
            ->select()
            ->where("id", $goods_id)
            ->first();
            // print_r($data);die;
        return $data ? get_object_vars($data): False;     
    }

    /**
     * 检查库存
     */
    public function check_sku($goods_id,$buy_num)
    {
        $data = $this->detail_page($goods_id);
        // print_r($data);die;
        $sku = $data['inventory'];
        if($buy_num > $sku){
            return False;
        }
        else{
            return true;
        }
    }
/**
 * 下单后减库存
 * Author Amber
 * Date 2018-08-01
 * Params [params]
 * @param  [type] $user_id [description]
 * @param  [type] $skuIds  [description]
 * @return [type]          [description]
 */
    public function cut_sku($item)
    {
        // $this -> check_sku()
       foreach ($item as $k => $v) {
            // select inventory from g_goods where id = '.$v['goods_id'].'
          $sku =   DB::select('select inventory from g_goods where id = '.$v['goods_id'].'');
          $objects = json_decode(json_encode($sku), true);
          if($objects[0]['inventory'] < $v['amout']){
            return False;
          }
        } 
        foreach ($item as $key => $value) {
           $cut_sku =  DB::update('update g_goods set inventory = inventory- '.$value['amout'].' where id = '.$value['goods_id'].'');
        }
        return $cut_sku;
    }

/**
 * 取消订单后添加库存
 * Author Amber
 * Date 2018-08-01
 * Params [params]
 * @param  string $value [description]
 * @return [type]        [description]
 */
    public function plus_sku($value='')
    {
       foreach ($item as $k => $v) {
            // select inventory from g_goods where id = '.$v['goods_id'].'
          $sku =   DB::select('select inventory from g_goods where id = '.$v['goods_id'].'');
          $objects = json_decode(json_encode($sku), true);
          if($objects[0]['inventory'] < 0){
            return False;
          }
        } 
        foreach ($item as $key => $value) {
           $plus_sku =  DB::update('update g_goods set inventory = inventory+ '.$value['amout'].' where id = '.$value['goods_id'].'');
        }
        return $plus_sku;
    }
}