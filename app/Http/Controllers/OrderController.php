<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Http\};
use App\OrderModel;
// use App\HomePageModel;

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
	   	$data['province'] = $request->input("province");
	   	$data['city'] = $request->input("city");
	   	$data['district'] = $request->input("district");
	   	$data['address'] = $request->input("address");
	   	$data['contact_name'] = $request->input("contact_name");
	   	$data['contact_phone'] = $request->input("contact_phone");
	   	$data['district'] = $request->input("district");
	   	$data['district'] = $request->input("district");

        $GoodsModel = new GoodsModel();

        $res = $GoodsModel->GoodsList($classify_id);

        if($res == FALSE){
            $res = array(
                "errNo" => "0003",
                "errMsg" => "此分类下暂无商品"
            );
            $this->_response($res);
        }
        $res = array(
            "errNo" => 0,
            "errMsg" => "success",
            "data" => $res
        );

        $this->_response($res);
   }
}