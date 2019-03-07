<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\LogisticesModel;
use DB;

class LogisticsController extends Controller
{

/**
 * 查看物流 
 * Author Amber
 * Date 2018-12-13
 * Params [params]
 * @param string $value [description]
 */
  public function selectLog(Request $request)
  {
  	$OrderCode = $request->input("OrderCode");
  	$ShipperCode = $request->input("ShipperCode");
  	$LogisticCode = $request->input("LogisticCode");
    $LogisticsModel = new LogisticesModel();
    $data = $LogisticsModel->selectLog($OrderCode,$ShipperCode,$LogisticCode);
  }

  public function ReceiptList(Request $request)
  {
  	$user_id = $request->input("user_id");
  	$LogisticesModel = new LogisticesModel();
    $ret = $LogisticesModel->ReceiptList($user_id);
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
  	  $user_id = $request->input('user_id');
      $order_id = $request->input('order_id');
      $goods_id = $request->input('goods_id');
      $LogisticesModel = new LogisticesModel();
      $ret = $LogisticesModel->Receiptitem($user_id,$order_id,$goods_id);
      if($ret){
        // echo 1;die;
         $res = array(
                "errNo" => "success",
                "data" => $ret,
            );
            $this->_response($res);
         }else{
          // echo 2;die;
           $res = array(
                "errNo" => "7008",
                "errMsg" => "您还没有相关的订单"
            );
            $this->_response($res);
            
        
       }
  }
/**
 * 快递鸟面单 
 * Author Amber
 * Date 2019-03-01
 * Params [params]
 * @param  string $value [description]
 * @return [type]        [description]
 */
  public function faceLog($value='')
  {

  }

}