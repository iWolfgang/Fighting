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
            ->select('id','no','total_amount','remark','paid_status','creatorder_at','expiration_at')
            ->where('user_id',$user_id)
            ->where('paid_status',"待支付")
            ->get();
        $objects = json_decode(json_encode($bool), true);//未支付的订单列表
        
        $CloseOrder = array();
        foreach ($objects as $key => $value) {//关闭支付超时的订单
            if(time() > $value['expiration_at']){
                //判断订单过期时间是否小于当前时间
                // $del_order =  DB::delete('delete from g_orders where id = '.$val['id'].'');
                $del_order = DB::table('g_orders')
                    ->where('id', $value['id'])
                    ->update(['paid_status' => '已关闭']);
                if($del_order){//修改skuid商品的状态 订单状态改为已关闭，并还原对应的库存
                    $small_order = DB::table('g_order_items')
                     ->select()
                     ->where('order_id',$value['id'])
                     ->get();
                     $small = json_decode(json_encode($small_order), true);
                     foreach ($small as $k => $v) {
                            $del_item =  DB::update('update  g_goods set inventory = inventory + '.$v['amout'].' where id = '.$v['goods_id'].'');
                            // $del_items =  DB::delete('delete from g_order_items where order_id = '.$value['id'].'');  
                     }
                }
            }
            else{//未支付订单的详细商品列表
                $pay_items = collect([]);
                foreach ($objects as $key => $value) {
                   $arr = DB::table('g_order_items')
                    ->where('order_id',$value['id'])
                    ->get(); 
                    // $arrs = json_decode(json_encode($arr), true);
                    // print_r($arrs);die;
                     $pay_items->push($arr);
                }
				$pay_items = $pay_items->flatten();
                $pay_items = json_decode(json_encode($pay_items), true);
                $list = array();
                foreach ((array)$pay_items as $k => $v) {
                         $goods = DB::table('g_productSkus')
                            ->select('g_product.id','g_product.goods_thumb','g_product.goods_name','g_productSkus.title')
                            ->join('g_product','g_productSkus.product_id','=','g_product.id')
                            ->where('g_productSkus.id',$v['goods_id'])
                            ->first();
                            $list[$k]['goods'] = (array)$goods;
                            $list[$k]['amout'] = $v['amout'];
                            $list[$k]['price'] = $v['price'];
                            $list[$k]['total_amount'] = $v['price']*$v['amout'];
               
                 }
                 // print_r($list);die;
            }

        }
       return $list;
    
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

        //上边查的是收货地址
        //、、下边我们要查的是相关的订单
        $order_item = DB::table('g_order_items')
                ->select('g_order_items.price','g_product.goods_thumb','g_order_items.goods_id','g_product.goods_name','g_order_items.amout')
                ->join('g_product','g_order_items.goods_id','=','g_product.id')
                ->where('order_id',$order_id)
                ->where('goods_id',$goods_id)
                ->first();          
        if(empty($order_item)){
            return False;
        }
        $order_items = get_object_vars($order_item);
        // print_r($order_item);die;
        $arr = array_merge($orders,$order_items);
        return $arr ? $arr : False; 
    }
}
