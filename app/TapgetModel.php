<?php
namespace App;

use Illuminate\Database\Eloquent\Model;

use DB;
// use App\GetPYModel;
// use Illuminate\Support\Facades\Redis;

class tapgetModel extends Model{

    public $_tabName = 'g_tapget';

/**
 * 标签关联商品 
 * Author Amber
 * Date 2018-10-10
 * Params [params]
 * @param string 
*/
    public function TapAndGoods()
    {
       $tap = DB::table('g_tapget')
              ->select('g_tapget.id','tap_name')
              ->where('g_tapget.tap_status',1)
              ->where('g_tapget.tap_type','goods')
              ->get();
          
        $goods_tagid = json_decode(json_encode($tap), true);
        
        $taps = array();
        foreach ($goods_tagid as $key => $value) {
          $taps[$value['id']] = $value['tap_name'];

        }
        
        $goods = DB::table('g_product')
               ->select('id','goods_name','goods_img','tapid')
               ->where('g_product.on_sale',1)
               ->where("game_goods", 0)
               ->get();
        $goods = json_decode(json_encode($goods), true);
        $goodsinfo = array();
        foreach ($goods as $key => $value) {
            $goodsinfo[$key]['id'] = $value['id'];
            $goodsinfo[$key]['goods_name'] = $value['goods_name'];
            $goodsinfo[$key]['goods_img'] = $value['goods_img'];
            $goodsinfo[$key]['tapid'] =  json_decode($value['tapid']);
            foreach($goodsinfo[$key]['tapid'] as $i){
                $goodsinfo[$key]['tags'][] = $taps[$i];
            }
        }
        // print_r($goodsinfo);die;
        return $goodsinfo;







       





        // $goodsinfo = array();
      
        // foreach ($goods as $goods_tagid) {
        //     foreach ($goods as $key => $value) {

        //       print_r($goods_tagid['id']);die;
        //       print_r($value['tapid']);die;
        //      if(in_array($goods_tagid['id'], $value['tapid'])){
        //         $goodsinfo[$key]['id'] = $value['id'];
        //         $goodsinfo[$key]['goods_name'] = $value['goods_name'];
        //         $goodsinfo[$key]['goods_img'] = $value['goods_img'];
        //       }else{

        //       }
        //   }
        // }
        
        // print_r($goodsinfo);die; 
      
  }

  public function aaa($value='')
  {
            $tap = DB::table($this->_tabName)
              ->select('id','tap_name')
              ->where('tap_status',1)
              ->where('tap_type','goods')
              ->get();
          
        $goods_tagid = json_decode(json_encode($tap), true);
        // print_r($goods_tagid);die;
        $ids = array_column($goods_tagid, 'id');//所有tapid
        $goods = DB::table('g_product')
               ->select('id','goods_name','goods_img','tapid')
               ->where('g_product.on_sale',1)
               ->get();
        $all_goods = json_decode(json_encode($goods), true);//所有商品
        //print_r($all_goods);die;
        $allgoods = array();//所有商品中的tapid(原是json)转为字符串(转字符串是为了用in方法)后的所有商品数组
        foreach ($all_goods as $key => $value) {
              
              $allgoods[$key]['id'] =  $value['id'];
              $allgoods[$key]['goods_name'] =  $value['goods_name'];
              // $allgoods[$key]['description'] =  $value['description'];
              $allgoods[$key]['goods_img'] =  $value['goods_img'];
              // $allgoods[$key]['goods_desc'] =  $value['goods_desc'];
              //$allgoods[$key]['tapid'] =  json_decode($value['tapid']);
              $allgoods[$key]['tapid'] =  implode(',',json_decode($value['tapid']));
        }   

        // foreach ($allgoods as $goodsinfo) {//筛不同
              foreach ($allgoods as $key => $value) {
                  if(in_array($value['tapid'], $ids)){
                      $goodsinfo[$key]['id'] =  $value['id'];
                      $goodsinfo[$key]['goods_name'] =  $value['goods_name'];
                      $goodsinfo[$key]['goods_img'] =  $value['goods_img'];             
                      $goodsinfo[$key]['tapid'] =  $value['tapid'];             
                  }else{
                      
                  }                
              }
              print_r($goodsinfo);die;
   
        // }
         $res = array(); //想要的结果
        foreach ($goodku as $k => $v) {
          $res[$v['product_id']][] = $v;
        }
        print_r($goodsinfo);die;




        // $goodsinfo 是标签关联的所有商品表所有商品 接下来要去找sku
        $goods_ids = array_column($goodsinfo, 'id');//所有商品id
        // print_r($goods_ids);
        $goodsSku = DB::table('g_productSkus')
               ->select('id','title','pricenow','product_id')
               ->get();
        $all_goodsSku = json_decode(json_encode($goodsSku), true);//所有SKU商品
        // print_r($all_goodsSku);
        $goodku = array();
        foreach ($all_goodsSku as $k => $v) {//筛不同
              foreach ($all_goodsSku as $key => $value) {
                  if(in_array($value['product_id'], $goods_ids)){
                      $goodku[$key]['id'] =  $value['id'];
                      $goodku[$key]['title'] =  $value['title'];
                      $goodku[$key]['pricenow'] =  $value['pricenow'];             
                      $goodku[$key]['product_id'] =  $value['product_id']; 
                    }   
                }
        $res = array(); //想要的结果
        foreach ($goodku as $k => $v) {
          $res[$v['product_id']][] = $v;
        }
         // print_r($res);die;
        }
  }






}


   

    // public function goods($taps)
    // {

    //       // $taps = $this->TapAndGoods();
    //       // print_r($taps);die;
    //       $goods = array();
    //       foreach ($taps as $key => $value) {
    //       	$goods[] = DB::table('g_product')
    //         ->select('id','goods_name','tapid')
    //         ->where('tapid',$value['id'])
    //         // ->where('tap_type','goods')
    //         ->get();
    //       }
    //       print_r($goods);die;
    //       ->select('g_product.id','g_product.goods_name','g_product.goods_desc','g_product.goods_img','g_product.price','g_product.sold_count','g_product.goods_postage','g_productSkus.id','g_productSkus.title','g_productSkus.priceone','g_productSkus.pricenow','g_productSkus.title','g_productSkus.title','g_productSkus.title','g_productSkus.stock')
               // ->join('g_productSkus','g_product.id','=','g_productSkus.product_id')
    // }













