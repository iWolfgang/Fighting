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
/**
 * 商品详情页
 * Author Amber
 * Date 2018-07-24
 * Params [params]
 * @param  Request $request [description]
 * @return [type]           [description]
 */
    public function detail_page(Request $request)
    {
        $goods_id = $request->input("goods_id");

        $GoodsModel = new GoodsModel();

        $res = $GoodsModel->detail_page($goods_id);
        // print_r($res);die;
        if($res == FALSE){
            $res = array(
                "errNo" => "0003",
                "errMsg" => "系统有误"
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