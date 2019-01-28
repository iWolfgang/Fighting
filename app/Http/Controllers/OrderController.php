<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Http\Redirect;
use App\OrderModel;
use App\GoodsBuyCarModel;
use App\GoodsModel;
use App\Jobs\CloseOrder;
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
      // dump($request);die;
        $order = array();
        $items = array();
        $user_id = $order['user_id'] = $request->input("user_id");//用户id
	      // $items = $request->input("items");//前端传过来的购买参数
        $items = json_decode( $request->input("items"),JSON_FORCE_OBJECT);//前端传过来的购买参数
        $order['address'] = $request->input("address");//收货地址
        $order['remark'] = $request->input("remark");//留言
        $orders['total_amount_one'] = $request->input("total_amount");//前端传过来的购买总金额
        // $goods_postage = $request->input("goods_postage");//前端传过来的购买总金额
        //=============================以上是需要前端传过来的===============================
        
        $order['total_amount'] = 0;//后端判断的总金额
        $order['creatorder_at'] = time();//下单时间
        $order['expiration_at'] = time()+1800;//订单关闭倒计时
        $order['paid_status'] = "待支付";//订单状态
        $order['refund_status'] = "未退款";//退款状态
        $order['ship_status'] = "待支付";//物流状态
        $order['no'] = $this->creat_ordnum();//订单流水号
	    	$is_car =  $request->input("is_car");//是否调用了购物车
        $isset = $this->check_address($order['user_id']);//检查地址是否存在
        if($isset == ''){
             $res = array(
                "errNo" => "7001",
                "errMsg" => "用户地址不存在"
            );
            $this->_response($res);
        }
     /**
     * 判断前后端接收的价格是否一致
     */
    
        $item = array();
        foreach ($items as $key => $value) {
            $item[$key]['price']  = $value['pricenow'];
            $item[$key]['goods_id'] = $value['id'];
            $item[$key]['amout']  = $value['buy_num'];
            $item[$key]['goods_postage']  = $value['goods_postage'];
            $order['total_amount'] +=  $item[$key]['amout'] * $item[$key]['price']+$item[$key]['goods_postage'];

        }
        // $order['total_amount'] = $goods_amount + $goods_postage;
        if($orders['total_amount_one'] != $order['total_amount']){
            $res = array(
                "errNo" => "7002",
                "errMsg" => "请以正规方式购买"
            );
            $this->_response($res);
        }
        //将下单商品从购物车中删除
        if($is_car){
          $skuIds = collect($items)->pluck('goods_id');
          $GoodsBuyCarModel = new GoodsBuyCarModel();
          $del = $GoodsBuyCarModel->delcar($user_id,$skuIds);
          if($del == False){
               $res = array(
                  "errNo" => "7003",
                  "errMsg" => "系统有误，购买失败，请重新购买"
              );
              $this->_response($res);
          }
        }
       //  //减少库存
        $GoodsModel = new GoodsModel();
        $cut_sku = $GoodsModel->cut_sku($items);
        if($cut_sku == False){
             $res = array(
                "errNo" => "7004",
                "errMsg" => "库存不足，购买失败"
            );
            $this->_response($res);
        } 
     
        $order_id = $this->orderstore($order);
        $itemnew = array();
        // $itemss =  json_decode($items,true);
        foreach ($items as $key => $value) {
            $itemnew[$key]['goods_id'] = $value['id'];
            $itemnew[$key]['price']  = $value['pricenow'];
            $itemnew[$key]['amout']  = $value['buy_num'];
            $itemnew[$key]['order_id']  = $order_id;

        }
        $OrdersModel = new OrderModel();
        $res = $OrdersModel->store_items($itemnew);
        $arr = array('order_id'=>$order_id, 'total_amount'=>$order['total_amount']);
        // dd($arr);die;
        if($res){
         $res = array(
                "errNo" => "success",
                "errMsg" => "订单提交成功,进行支付",
                "data"=>$arr
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
      // 订单流水号前缀 随机生成 6 位的数字
        $prefix = date('YmdHis');
        for ($i = 0; $i < 10; $i++) {
            $no = $prefix.str_pad(random_int(0, 999999), 6, '0', STR_PAD_LEFT);
            return $no;
        }
   }
/**
 * 全部订单 
 * Author Amber
 * Date 2019-01-24
 * Params [params]
 * @param string $value [description]
 */

    public function all_orderlist(Request $request)
    {
      // echo 1;die;
      $user_id = $request->input('user_id');
      $orderModel = new orderModel();
      // $data['wait_paylist'] = $orderModel->wait_paylist($user_id);
      // $data['wait_sendlist'] = $orderModel->wait_sendlist($user_id);
      // $data['ReceiptList'] = $orderModel->ReceiptList($user_id);
      // $data['Overlist'] = $orderModel->Overlist($user_id);
      $ret = $orderModel->all_orderlist($user_id);
      if($ret){
         $res = array(
                "errNo" => "success",
                "data" => $ret
            );
            $this->_response($res);
         }else{
          
           $res = array(
                "errNo" => "success",
                "errMsg" => "您还没有相关的订单",
                "data" => $ret
            );
            $this->_response($res);
         }
    }
  /**
   * 取消订单 
   * Author Amber
   * Date 2019-01-24
   * Params [params]
   * @param string $value [description]
   */
    public function cancel_order(Request $request)
    {
      $order_id = $request->input('order_id');
      $orderModel = new orderModel();
      $ret = $orderModel->cancel_order($order_id);
      if($ret){
         $res = array(
                "errNo" => "success",
                "data" => $ret,
            );
          $this->_response($res);
         }else{
           $res = array(
                "errNo" => "8003",
                "errMsg" => "取消订单异常"
            );
          $this->_response($res);
       }
    }
    /**
     * 订单详情页 
     * Author Amber
     * Date 2019-01-28
     * Params [params]
     * @param  Request $request [description]
     * @return [type]           [description]
     */
  public function goods_orderitem(Request $request)
   {
      $user_id = $request->input('user_id');
      $order_id = $request->input('order_id');
      $orderModel = new orderModel();
      $ret = $orderModel->goods_orderitem($user_id,$order_id);
      if($ret){
         $res = array(
                "errNo" => "success",
                "data" => $ret,
            );
            $this->_response($res);
      }else{
          
           $res = array(
                "errNo" => "success",
                "errMsg" => "您还没有相关的订单"
            );
            $this->_response($res);
        
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
      $orderModel = new orderModel();
      $ret = $orderModel->wait_paylist($user_id);
       if($ret){
         $res = array(
                "errNo" => "success",
                "data" => $ret,
            );
            $this->_response($res);
         }else{
          
           $res = array(
                "errNo" => "success",
                "errMsg" => "您还没有相关的订单",
                "data" => $ret
            );
            $this->_response($res);
        
       }
   }
/**
 * 待付款商品详情页
 * Author Amber
 * Date 2018-08-09
 * Params [params]
 * @param  string $value [description]
 * @return [type]        [description]
 */
   public function wait_pay(Request $request)
   {
      $user_id = $request->input('user_id');
      $order_id = $request->input('order_id');
      $goods_id = $request->input('goods_id');
      $orderModel = new orderModel();
      $ret = $orderModel->wait_pay($user_id,$order_id,$goods_id);
      if($ret){
         $res = array(
                "errNo" => "success",
                "data" => $ret
            );
            $this->_response($res);
         }else{
           $res = array(
                "errNo" => "7008",
                "errMsg" => "请求超时"
            );
            $this->_response($res);
        
       }
   }
 /**
  * 待发货列表 
  * Author Amber
  * Date 2018-12-07
  * Params [params]
  * @param string $value [description]
  */
   public function wait_sendlist(Request $request)
   {
      $user_id = $request->input('user_id');
      $orderModel = new orderModel();
      $ret = $orderModel->wait_sendlist($user_id);
      if($ret){
         $res = array(
                "errNo" => "success",
                "data" => $ret,
            );
            $this->_response($res);
      }else{
          
           $res = array(
                "errNo" => "success",
                "errMsg" => "您还没有相关的订单",
                "data" => $ret
            );
            $this->_response($res);
        
       }
   }
   /**
    * 待收货列表页 
    * Author Amber
    * Date 2019-01-24
    * Params [params]
    * @param Request $request [description]
    */
   public function ReceiptList(Request $request)
   {
      $user_id = $request->input('user_id');
      $orderModel = new orderModel();
      $ret = $orderModel->ReceiptList($user_id);
      if($ret){
         $res = array(
                "errNo" => "success",
                "data" => $ret,
            );
            $this->_response($res);
      }else{
          
           $res = array(
                "errNo" => "success",
                "errMsg" => "您还没有相关的订单",
                "data" => $ret
            );
            $this->_response($res);
        
       }
   }
/**
 * 确认收货 
 * Author Amber
 * Date 2019-01-24
 * Params [params]
 * @param string $value [description]
 */
   public function Confirm_Order(Request $request)
   {
      $user_id = $request->input('user_id');
      $order_id = $request->input('order_id');
      $orderModel = new orderModel();
      $ret = $orderModel->Confirm_Order($user_id,$order_id);
      if($ret){
         $res = array(
                "errNo" => "success",
                "data" => "确认收货成功",
            );
            $this->_response($res);
      }else{
          
           $res = array(
                "errNo" => "8001",
                "errMsg" => "确认收货失败，请重新确认"
            );
            $this->_response($res);
        
       }
   }
    /**
    * 已经完成的订单列表 
    * Author Amber
    * Date 2019-01-24
    * Params [params]
    * @param Request $request [description]
    */
      public function Overlist(Request $request)
   {
      $user_id = $request->input('user_id');
      $orderModel = new orderModel();
      $ret = $orderModel->Overlist($user_id);
      if($ret){
         $res = array(
                "errNo" => "success",
                "data" => $ret,
            );
            $this->_response($res);
      }else{
          
           $res = array(
                "errNo" => "success",
                "errMsg" => "您还没有相关的订单"
            );
            $this->_response($res);
        
       }
   }
  
   public function Overitem(Request $request)
   {
      $order_id = $request->input('order_id');
      $orderModel = new orderModel();
      $ret = $orderModel->Overitem($order_id);
      if($ret){
         $res = array(
                "errNo" => "success",
                "data" => $ret,
            );
            $this->_response($res);
      }else{
          
           $res = array(
                "errNo" => "success",
                "errMsg" => "您还没有相关的订单"
            );
            $this->_response($res);
        
       }
   }

   public function wait_senditem(Request $request)
   {
      $order_id = $request->input('order_id');
      $orderModel = new orderModel();
      $ret = $orderModel->wait_senditem($order_id);
      if($ret){
         $res = array(
                "errNo" => "success",
                "data" => $ret,
            );
            $this->_response($res);
      }else{
          
           $res = array(
                "errNo" => "success",
                "errMsg" => "您还没有相关的订单"
            );
            $this->_response($res);
        
       }
   }
  public function Receiptitem(Request $request)
   {
      $order_id = $request->input('order_id');
      $orderModel = new orderModel();
      $ret = $orderModel->Receiptitem($order_id);
      if($ret){
         $res = array(
                "errNo" => "success",
                "data" => $ret,
            );
            $this->_response($res);
      }else{
          
           $res = array(
                "errNo" => "success",
                "errMsg" => "您还没有相关的订单"
            );
            $this->_response($res);
        
       }
   }

   public function goods_orderitem(Request $request)
   {
      $user_id = $request->input('user_id');
      $order_id = $request->input('order_id');
      $orderModel = new orderModel();
      $ret = $orderModel->goods_orderitem($user_id,$order_id);
      if($ret){
         $res = array(
                "errNo" => "success",
                "data" => $ret,
            );
            $this->_response($res);
      }else{
          
           $res = array(
                "errNo" => "success",
                "errMsg" => "您还没有相关的订单"
            );
            $this->_response($res);
        
       }
   }

}