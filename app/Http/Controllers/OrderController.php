<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Http\Redirect;
use App\OrderModel;
use App\GoodsBuyCarModel;
use App\GoodsModel;
use DB;

class OrderController extends Controller
{

/**
 * 创建订单
 * Author Amber
 * Date 2018-07-27
 * Params [params]
 * @param  Request $request [description]
 * @return [type]           [description]
 */
   public function creat_orders(Request $request)
   {    
        $order = array();
	      $user_id = $order['user_id'] = $request->input("user_id");
        $order['address'] = $request->input("address");//收货地址
        $order['remark'] = $request->input("remark");//留言
        $orders['total_amount_one'] = $request->input("total_amount");//购买总金额
        $order['total_amount'] = 0;//购买总金额
        $order['creatorder_at'] = time();//购买总金额
        $order['expiration_at'] = time()+24*3600;//购买总金额
        $order['paid_status'] = "待支付";//购买总金额
	    	$order['no'] = $this->creat_ordnum();//订单流水号
        // print_r($order);die;
        $isset = $this->check_address($order['user_id']);//检查地址是否存在
        if($isset == False){
             $res = array(
                "errNo" => "7001",
                "errMsg" => "用户地址不存在"
            );
            $this->_response($res);
        }
        //模拟items数组
        $items = array(

          0 => array(
                        "goods_id" => '1',
                        "buy_num" => '5',
                        "price" => '200'
                      ),
          1 => array(
                        "goods_id" => '2',
                        "buy_num" => '7',
                        "price" => '250'
                      ),
          2 => array(
                        "goods_id" => '3',
                        "buy_num" => '7',
                        "price" => '252'
                      ),
        );

        //循环商量items
        $item = array();
        foreach ($items as $key => $value) {
            $item[$key]['price']  = $value['price'];
            $item[$key]['goods_id'] = $value['goods_id'];
            $item[$key]['amout']  = $value['buy_num'];
            $order['total_amount'] +=  $item[$key]['amout'] * $item[$key]['price'];

        }
        /**
         * 判断前后端接收的价格是否一致
         */
        if($orders['total_amount_one'] != $order['total_amount']){
            $res = array(
                "errNo" => "7002",
                "errMsg" => "请以正规方式购买"
            );
            $this->_response($res);
        }
        //将下单商品从购物车中删除
        
        $skuIds = collect($item)->pluck('goods_id');
        $GoodsBuyCarModel = new GoodsBuyCarModel();
        $del = $GoodsBuyCarModel->delcar($user_id,$skuIds);
        if($del == False){
             $res = array(
                "errNo" => "7003",
                "errMsg" => "系统有误，购买失败，请重新购买"
            );
            $this->_response($res);
        }

        //减少库存
        
        $GoodsModel = new GoodsModel();
        $cut_sku = $GoodsModel->cut_sku($item);
        if($cut_sku == False){
             $res = array(
                "errNo" => "7004",
                "errMsg" => "系统有误，购买失败，请重新购买"
            );
            $this->_response($res);
        } 
        
        $order_id = $this->orderstore($order);
        //循环商量items
        $itemnew = array();
        foreach ($items as $key => $value) {
            $itemnew[$key]['goods_id'] = $value['goods_id'];
            $itemnew[$key]['price']  = $value['price'];
            $itemnew[$key]['amout']  = $value['buy_num'];
            $itemnew[$key]['order_id']  = $order_id;

        }
        $OrdersModel = new OrderModel();
        $res = $OrdersModel->store_items($itemnew);
        if($res){
         $res = array(
                "errNo" => "success",
                "errMsg" => "订单提交成功"
            );
            $this->_response($res);
         }else{
          if($res){
           $res = array(
                "errNo" => "7005",
                "errMsg" => "订单提交失败"
            );
            $this->_response($res);
         }
       }
    }     
/**
 * 检查地址是否存在 
 * Author Amber
 * Date 2018-08-01
 * Params [params]
 * @param  [type] $user_id [description]
 * @return [type]          [description]
 */
   public function check_address($user_id)
   {//这块因为 前端传过来的地址类型 还没确定，等传过来以后  在这方法中要添加 一个where 条件 看用户id和地址是否同时存在表中
       $exit = DB::table('g_user_address')->where('user_id', $user_id)->exists();
      return $exit;
   }
/**
 * 创建order表数据
 * Author Amber
 * Date 2018-08-01
 * Params [params]
 * @param  [type] $order [description]
 * @return [type]        [description]
 */
   public function orderstore($order)
   {
        $OrdersModel = new OrderModel();
        $order_id = $OrdersModel->store($order);
        return $order_id;
   }

/**
 * 订单流水号
 * Author Amber
 * Date 2018-08-01
 * Params [params]
 * @return [type] [description]
 */
   public function creat_ordnum()
   {
      // 订单流水号前缀
        $prefix = date('YmdHis');
        for ($i = 0; $i < 10; $i++) {
            // 随机生成 6 位的数字
            $no = $prefix.str_pad(random_int(0, 999999), 6, '0', STR_PAD_LEFT);
            return $no;
        }
   }

/**
 * 未支付订单列表
 * Author Amber
 * Date 2018-08-01
 * Params [params]
 * @param  Request $request [description]
 * @return [type]           [description]
 */
   public function wait_paylist(Request $request)
   {
      $user_id = $request->input('user_id');
      // $user_id = $request->input('user_id');
      $orderModel = new orderModel();
      $res = $orderModel->wait_paylist($user_id);
   }
}