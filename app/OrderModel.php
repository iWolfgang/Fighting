<?php
namespace App;

use Illuminate\Database\Eloquent\Model;

use DB;
use Illuminate\Support\Facades\Redis;
use App\Jobs\CloseOrder;

class OrderModel extends Model{

    public $_tabName = 'g_orders';

/**
 * 创建一个订单
 * Author Amber
 * Date 2018-08-01
 * Params [params]
 * @param  string $value [description]
 * @return [type]        [description]
 */
    public function store($order)
    {
    	$bool = DB::table('g_orders')
            ->insertGetId($order);

        return $bool;
    }


    public function store_items($items)
    {
    	foreach ($items as $key => $value) {
    	   $order = DB::table('g_order_items')
            ->insert($value);
    	}
// dump($order);die;
         // $this->dispatch(new CloseOrder($order, config('app.order_ttl')));
      

    	return $order;
    }

    public function wait_paylist($user_id='')
    {

    	$bool = DB::table('g_orders')
            // ->select('g_orders.id','g_orders.no','g_orders.address','g_orders.total_amount','g_orders.remark','g_orders.paid_status','g_orders.creatorder_at','g_orders.expiration_at')
        ->select('id','no','total_amount','remark','paid_status','creatorder_at','expiration_at')
            ->where('user_id',$user_id)
            ->where('paid_status',"待支付")
            ->get();
        $objects = json_decode(json_encode($bool), true);
        $list = array();
        if(!empty($objects)){
            // echo 1;
            foreach ($objects as $key => $val) {

            if(time() > $val['expiration_at']){
                //判断订单过期时间是否小于当前时间
                $del_order =  DB::delete('delete from g_orders where id = '.$val['id'].'');
                if($del_order){//删除skuid商品
                    $small_order = DB::table('g_order_items')
                     ->select()
                     ->where('order_id',$val['id'])
                     ->get();
                     $small = json_decode(json_encode($small_order), true);
                     foreach ($small as $key => $value) {
                            $del_item =  DB::update('update  g_goods set inventory = inventory + '.$value['amout'].' where id = '.$value['goods_id'].'');
                            $del_items =  DB::delete('delete from g_order_items where order_id = '.$val['id'].'');  
                     }
                }
            }
          else{
                foreach ($objects as $key => $value) {
                    
                    $small_order = DB::table('g_order_items')
                     ->select()
                     ->where('order_id',$value['id'])
                     ->get();
                     $small = json_decode(json_encode($small_order), true);
                     print_r($small);
                    foreach ($small as $k => $v) {

                         $goods = DB::table('g_productSkus')
                            ->select('g_product.id','g_product.goods_thumb','g_product.goods_name','g_productSkus.title')
                            ->join('g_product','g_productSkus.product_id','=','g_product.id')
                            ->where('g_productSkus.i',$v['goods_id'])
                            ->first();
                            $list[$k]['goods'] = (array)$goods;
                            $list[$k]['amout'] = $v['amout'];
                            $list[$k]['price'] = $v['price'];
                            $list[$k]['total_amount'] = $v['price']*$v['amout'];
               
                 }
                  
             }
              
                    return $list ? $list : False; 
            }
        }

    }else{
            return False;
        }  
    }
/**
 * 待支付详情页
 * Author Amber
 * Date 2018-08-09
 * Params [params]
 * @param  string $value [description]
 * @return [type]        [description]
 */
    public function wait_pay($user_id = '',$order_id = '',$goods_id = '')
    {
        $order = DB::table($this->_tabName)
            ->select('id','no','address','total_amount','remark','expiration_at','creatorder_at')
            ->where('id', $order_id)
            ->where('user_id',$user_id)
            ->first();
        if(empty($order)){
            return False;
        }
        $orders = get_object_vars($order);
        
        $order_item = DB::table('g_order_items')
            ->select('amout','g_order_items.price','g_goods.goods_thumb','g_order_items.goods_id','g_goods.goods_name')
            ->join('g_goods','g_order_items.goods_id','=','g_goods.id')
            ->where('order_id', $order_id)
            ->where('goods_id',$goods_id)
            ->first();
        if(empty($order_item)){
            return False;
        }
        $order_items = get_object_vars($order_item);
        
        $arr = array_merge($orders,$order_items);
        return $arr ? $arr : False; 
    }
}