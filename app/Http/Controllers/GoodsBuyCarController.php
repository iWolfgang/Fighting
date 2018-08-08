<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Http\Redirect;
use App\GoodsBuyCarModel;
use App\GoodsModel;
// use App\HomePageModel;

class GoodsBuyCarController extends Controller
{


	public function add_buycar(Request $request)
	{
        
        $user_id = $request->input("user_id");
        $goods_id = $request->input("goods_id");
		$buy_num = $request->input("buy_num");
        if (empty($user_id) || is_numeric($user_id) == FALSE) {
            $res = array(
                "errNo" => "0002",
                "errMsg" => "用户id格式不正确"
            );
            $this->_response($res);
        }
        if (empty($goods_id) || is_numeric($goods_id) == FALSE) {
            $res = array(
                "errNo" => "0002",
                "errMsg" => "商品id格式不正确"
            );
            $this->_response($res);
        }
        if (empty($buy_num) || is_numeric($buy_num) == FALSE) {
            $res = array(
                "errNo" => "0002",
                "errMsg" => "购买量格式不正确"
            );
            $this->_response($res);
        }
        $GoodsModel = new GoodsModel();

        $ret = $GoodsModel->check_sku($goods_id,$buy_num);
        if($ret == FALSE){
            $res = array(
                "errNo" => "6003",
                "errMsg" => "被卖光啦"
            );
            $this->_response($res);
        }

        $GoodsBuyCarModel = new GoodsBuyCarModel();

        $res = $GoodsBuyCarModel->add_buycar($user_id,$goods_id,$buy_num);

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
            "data" => "添加购物车成功"
        );

        $this->_response($res);

	}

        public function homepagetwo_list(Request $request)
    {
        
        $cat_id = $request->input("cat_id");

        $GoodsModel = new GoodsCatModel();

        $res = $GoodsModel->only_two_homelist($cat_id);
// print_r($res);die;
        if($res == FALSE){
            $res = array(
                "errNo" => "6003",
                "errMsg" => "此分类暂无商品"
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
 * 购物车展示·
 * Author Amber
 * Date 2018-07-24
 * Params [params]
 * @param  Request $request [description]
 * @return [type]           [description]
 */
    public function show_buycar(Request $request)
    {
        $user_id = $request->input('user_id');

        $GoodsModel = new GoodsBuyCarModel();

        $res = $GoodsModel->show_buycar($user_id);
// print_r($res);die;
        if($res == FALSE){
            $res = array(
                "errNo" => "0",
                "errMsg" => "购物车空空的"
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