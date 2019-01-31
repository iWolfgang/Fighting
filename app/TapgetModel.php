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
              ->select('id','tap_name','tap_img','tap_desc','tap_logo')
              ->where('tap_status',1)
              ->where('tap_type','goods')
              ->get();
          
        $goods_tagid = json_decode(json_encode($tap), true);
        // dd($goods_tagid);die;
        $goods = DB::table('g_product')
               ->select('id','goods_name','price','goods_thumb','tapid')
               ->where('g_product.on_sale',1)
               ->where("game_goods", 1)
               ->get();
        $goods = json_decode(json_encode($goods), true);
      
        $goodsinfo = array();
        foreach ($goods as $key => $value) {
            $goodsinfo[$key]['id'] = $value['id'];
            $goodsinfo[$key]['goods_name'] = $value['goods_name'];
            $goodsinfo[$key]['price'] = $value['price'];
            $goodsinfo[$key]['goods_thumb'] = $value['goods_thumb'];
            $goodsinfo[$key]['tapid'] =  json_decode($value['tapid']);

        }
       // print_r($goods_tagid);
       // print_r($goodsinfo);die;
        $arr = array();
        foreach ($goods_tagid as $key => $value) {
          foreach ($goodsinfo as $k => $v) {
           
            foreach ($v['tapid'] as $ke => $va) {
              // echo $va ;die;
                if( $value['id'] ==$va){
                  // echo "开始".$arr[$key]['tap_id']."======".$value['id']."<br>";[$key]
                  $arr[$key]['tap_name'] = $value['tap_name'];
                  $arr[$key]['tap_img'] = $value['tap_img'];
                  $arr[$key]['tap_id'] =  $value['id'];
                  $arr[$key]['tap_desc'] = $value['tap_desc'];     
                  $arr[$key]['tap_logo'] = $value['tap_logo'];    
                  $arr[$k]['tap_id'] =  $value['id'];
                  $arr[$k]['goods_id'] = $v['id'];
                  $arr[$k]['goods_thumb'] = $v['goods_thumb'];
                  $arr[$k]['price'] = $v['price'];
                    // dd($arr);die;
                }
            }
          }         
        }
              // print_r($arr);
        $liu = array();
        foreach ($arr as $key => $value) {
          if(!empty($value['tap_name'])){
             $liu[] = $value;
          }
        }
        // print_r($liu);
        $wei = array();
        foreach ($arr as $key => $value) {
          if(!isset($value['tap_name'])){
             $wei[] = $value;
          }
        }
        // print_r($wei);

        // $ran = array();
        // foreach ($liu as $key => $value) {
        //   $ran[$key]['tap_name'] = $value['tap_name'];
        //   $ran[$key]['tap_img'] = $value['tap_img'];
        //   $ran[$key]['tap_id'] = $value['tap_id'];     
        //   $ran[$key]['tap_desc'] = $value['tap_desc'];     
        //   $ran[$key]['tap_logo'] = $value['tap_logo'];     
        // }
       foreach ($liu as $key => $value) {
          foreach ($goodsinfo as $k => $v) {
            // dd($v['tapid']);die;
            foreach ($v as $ke => $va) {
              // echo $va['tap_id'];die;
              if($value['tap_id'] == $va[0]){
                $liu[$key]['goods_info'][] = $v;
            }
            }
            
          }
        }
        // print_r($liu);die;
        return $liu;
      
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
               ->select('id','goods_name','goods_thumb','tapid')
               ->where('g_product.on_sale',1)
               ->get();
        $all_goods = json_decode(json_encode($goods), true);//所有商品
        //print_r($all_goods);die;
        $allgoods = array();//所有商品中的tapid(原是json)转为字符串(转字符串是为了用in方法)后的所有商品数组
        foreach ($all_goods as $key => $value) {
              
              $allgoods[$key]['id'] =  $value['id'];
              $allgoods[$key]['goods_name'] =  $value['goods_name'];
              // $allgoods[$key]['description'] =  $value['description'];
              $allgoods[$key]['goods_thumb'] =  $value['goods_thumb'];
              // $allgoods[$key]['goods_desc'] =  $value['goods_desc'];
              //$allgoods[$key]['tapid'] =  json_decode($value['tapid']);
              $allgoods[$key]['tapid'] =  implode(',',json_decode($value['tapid']));
        }   

        // foreach ($allgoods as $goodsinfo) {//筛不同
              foreach ($allgoods as $key => $value) {
                  if(in_array($value['tapid'], $ids)){
                      $goodsinfo[$key]['id'] =  $value['id'];
                      $goodsinfo[$key]['goods_name'] =  $value['goods_name'];
                      $goodsinfo[$key]['goods_thumb'] =  $value['goods_thumb'];             
                      $goodsinfo[$key]['tapid'] =  $value['tapid'];             
                  }else{
                      
                  }                
              }
              // print_r($goodsinfo);die;
   
        // }
         $res = array(); //想要的结果
        foreach ($goodku as $k => $v) {
          $res[$v['product_id']][] = $v;
        }
        // print_r($goodsinfo);die;




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


  public function subject_goodsitem($tap_id='')
  {
           $tap = DB::table('g_tapget')
              ->select('id','tap_name','tap_img','tap_desc','tap_logo')
              // ->where('tap_status',1)
              // ->where('tap_type','goods')
              ->where('id',$tap_id)
              ->first();
          
        $goods_tapid = json_decode(json_encode($tap), true);
        // dd($goods_tagid);die;
        $goods = DB::table('g_product')
               ->select('id','goods_name','price','goods_thumb','tapid')
               ->where('g_product.on_sale',1)
               ->where("game_goods", 1)
               ->get();
        $goods = json_decode(json_encode($goods), true);
        $goodsinfo = array();
        foreach ($goods as $key => $value) {
            $goodsinfo[$key]['id'] = $value['id'];
            $goodsinfo[$key]['goods_name'] = $value['goods_name'];
            $goodsinfo[$key]['price'] = $value['price'];
            $goodsinfo[$key]['goods_thumb'] = $value['goods_thumb'];
            $goodsinfo[$key]['tapid'] =  json_decode($value['tapid']);

        }
        $arr = array();
        // foreach ($goods_tagid as $key => $goods_tapid) {
        // dd($goods_tapid);die;
          foreach ($goodsinfo as $k => $v) {

            foreach ($v['tapid'] as $ke => $va) {

                if($goods_tapid['id'] == $va){
                  $arr['tap_name'] = $goods_tapid['tap_name'];
                  $arr['tap_img'] = $goods_tapid['tap_img'];
                  $arr['tap_id'] =  $goods_tapid['id'];
                  $arr['tap_desc'] = $goods_tapid['tap_desc'];     
                  $arr['tap_logo'] = $goods_tapid['tap_logo']; 
                  $arr[$k]['tap_id'] =  $goods_tapid['id'];
                  $arr[$k]['goods_id'] = $v['id'];
                  $arr[$k]['goods_name'] = $v['goods_name'];
                  $arr[$k]['price'] = $v['price'];
                  $arr[$k]['goods_thumb'] = $v['goods_thumb'];
                }
            }       
        }
        $liu = array();
        $liu['tap_name'] = $arr['tap_name'];
        $liu['tap_img'] = $arr['tap_img'];
        $liu['tap_id'] = $arr['tap_id'];
        $liu['tap_logo'] = $arr['tap_logo'];
        $liu['tap_desc'] = $arr['tap_desc'];

        $liu['goods_info'] = array();
         unset($arr['tap_name']);
         unset($arr['tap_img']);
         unset($arr['tap_id']);
         unset($arr['tap_logo']);
         unset($arr['tap_desc']);
        foreach ($arr as $key => $value) {
          $liu['goods_info'][] = $value;
        }

   
        return $liu;
  }



}













