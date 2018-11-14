<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Http\Redirect;
use App\CreatOrderModel;
use DB;

class CreatOrderController extends Controller
{

/**
 * Function 
 * Author Amber
 * Date 2018-11-13
 * Params [params]
 * @param string $value [description]
 */
  public function PlaceOrder(Request $request)
  {
    // echo 1;die;
        $user_id = $request->input("user_id");
        $sku_id = $request->input("sku_id");
        $address = $request->input("address");//收货地址
        $remark = $request->input("remark");//留言
        $total_amount_one = $request->input("total_amount");//购买总金额
        // $order = array();
        // $user_id = $order['user_id'] = $request->input("user_id");
        // $order['sku_id'] = $request->input("sku_id");//商品id
        // $order['address'] = $request->input("address");//收货地址
        // $order['remark'] = $request->input("remark");//留言
        // $order['total_amount_one'] = $request->input("total_amount");//购买总金额
        // dump($order);
        //查询skuid对应的商品
        $CreatOrderModel = new CreatOrderModel();

        $ret = $CreatOrderModel->SelectSkuItem($sku_id);
        // $item = $this->SelectSkuItem($sku_id);
  }

  // public function SelectSkuItem($sku_id)
  // {
      
  // }



}