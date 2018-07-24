<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Http\Redirect;
use App\GoodsModel;
// use App\HomePageModel;

class GoodsController extends Controller
{


	public function goods_list(Request $request)
	{
		$classify_id = $request->input("classify_id");

        $GoodsModel = new GoodsModel();

        $res = $GoodsModel->GoodsList($classify_id);

        if($res == FALSE){
            $res = array(
                "errNo" => "0003",
                "errMsg" => "系统错误"
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

    public function detail_page(Request $request)
    {
        $goods_id = $request->input("goods_id");

        $GoodsModel = new GoodsModel();

        $res = $GoodsModel->detail_page($goods_id);
        // print_r($res);die;
        if($res == FALSE){
            $res = array(
                "errNo" => "0003",
                "errMsg" => "返回数据类型找刘然要"
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