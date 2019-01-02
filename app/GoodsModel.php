<?php
namespace App;

use Illuminate\Database\Eloquent\Model;

use DB;
use Illuminate\Support\Facades\Redis;


class GoodsModel extends Model{

    public $_tabName = 'g_product';
/**
 * 展示轮播图 
 * Author Amber
 * Date 2018-05-08
 * Params [params]
 **/
    public function slideshow()
    {
      $data = DB::table('t_slideshow')
            ->where('slideshow_type','article')
            ->orderBy('created_at', 'desc')
            ->get(['slideshow','slideshow_url','type']);
          $data = json_decode(json_encode($data), true);

          return $data;
    }
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
        $goodslist = DB::table($this->_tabName)
            ->select('id','goods_name','goods_thumb','price')
            ->where("goods_cat", $classify_id)
            ->where("game_goods", 0)
            ->get();
        $data = json_decode(json_encode($goodslist), true);
        return $data ? $data : False;        
    }
/**
 * 查询商品的详细信息
 * Author Amber
 * Date 2018-10-23
 * Params [params]
 * @param  string $goods_id [description]
 * @return [type]           [description]
 */
    public function detail_page($goods_id='')
    {
        //根据商品
        $data = DB::table('g_product')
            ->select('goods_name','goods_thumb','goods_img','goods_cat','goods_desc','sold_count','price','goods_postage','created_at')
            ->where("id", $goods_id)
            ->first();
        $datas = get_object_vars($data);
        $dataa = DB::table('g_productSkus')
           ->select('title','sku_thumb','pricenow','stock','product_id')
           ->where("product_id", $goods_id)
           ->get();   
        $dataite = json_decode(json_encode($dataa), true);   
           foreach ($dataite as $key => $value) {
               $datas['sku'][] = $value;
            }  
        return $datas;
    }
/**
 * 查询SKu商品  
 * Author Amber
 * Date 2018-10-16
 * Params [params]
 * @param  string $value [description]
 * @return [type]        [description]
 */
    public function willJoin_Buycart($goods_id)
    {
        return $this->selectGoodsSku($goods_id); 
    }
/**
 * 根据商品id查SKU 
 * Author Amber
 * Date 2018-10-16
 * Params [params]
 * @param  [type] $goods_id [description]
 * @return [type]           [description]
 */
    public function selectGoodsSku($goods_id)
    {
        $data = DB::table('g_productSkus')
            ->select()
            ->where("product_id", $goods_id)
            ->get();
        $data = json_decode(json_encode($data), true);
        $dev = array();
        foreach ($data as $key => $value) {
            if($data[$value['product_id']] = $value['product_id']){
                $dev['productSku'][$key]['title'] = $value['title'];
                $dev['productSku'][$key]['description'] = $value['description'];
                $dev['productSku'][$key]['pricenow'] = $value['pricenow'];
                $dev['productSku'][$key]['stock'] = $value['stock'];
            }
        }
       return  $dev;
    }
/**
 * 检查库存
 */
    public function check_sku($goods_id,$buy_num)
    {
        $data = $this->detail_page($goods_id);
        $sku = $data['stock'];
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
       foreach ($item as $k => $v) {
          $sku =   DB::select('select stock from g_productSkus where id = '.$v['goods_id'].'');
          $objects = json_decode(json_encode($sku), true);
          if($objects[0]['stock'] < $v['amout']){
            return False;
          }
        } 
        foreach ($item as $key => $value) {
          $cut_sku =  DB::update('update g_productSkus set stock = stock- '.$value['amout'].' where id = '.$value['goods_id'].'');
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
          $sku =   DB::select('select stock from g_productSkus where id = '.$v['goods_id'].'');
          $objects = json_decode(json_encode($sku), true);
          if($objects[0]['stock'] < 0){
            return False;
          }
        } 
        foreach ($item as $key => $value) {
           $plus_sku =  DB::update('update g_productSkus set stock = stock+ '.$value['amout'].' where id = '.$value['goods_id'].'');
        }
        return $plus_sku;
    }
/**
 * 电商列表----全部列表
 * Author Amber
 * Date 2018-12-25
 * Params [params]
 * @param  string $goods_catid [description]
 * @return [type]              [description]
 */
    public function all_goodslist($goods_catid='')
    {
      //先查标签表 查出一级标签对应的二级标签
      //根据二级标签拿到对应的商品,'goods_thumb'
        $cat_two = DB::table('g_classify')
            ->select('id','cat_name','cat_imageUrl','cat_imageUrlCor')
            ->where("pid", $goods_catid)
            ->get();
         
        $data = json_decode(json_encode($cat_two), true);
        $all = array();
        foreach ($data as $key => $value) {
            $goodslist  = DB::table($this->_tabName)
            ->select('id','goods_name','goods_thumb','price')
            ->where("goods_cat", $value['id'])
            ->where("game_goods", 0)
            ->get();
            $all[]= $goodslist;
        }
          
         $newarray = json_decode(json_encode($all), true);
         dd($newarray);die;

        $array=array();
        foreach ($newarray as $key => $value) {
            // print_r($value);die;
            foreach ($value as $k => $v) {
                $array[] = $v;
            }
        }
           $newarrays = json_decode(json_encode($array), true);
        return $newarrays ? $newarrays : False;  
    }
     

}