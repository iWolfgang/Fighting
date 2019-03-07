<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Http\Redirect;
use App\GoodsBuyCarModel;
use App\GoodsModel;

class GoodsBuyCarController extends Controller
{
/**
 * 将要加入购物车--选sku 
 * Author Amber
 * Date 2018-10-16
 * Params [params]
 * @param string $value [description]
 */
    public function willJoin_Buycart(Request $request)
    {
        $goods_id = $request->input("goods_id");
        $GoodsModel = new GoodsModel();

        $ret = $GoodsModel->willJoin_Buycart($goods_id);
        // print_r($ret);die;
        $res = array(
            "errNo" => 0,
            "errMsg" => "success",
            "data" => $ret
        );

        $this->_response($res);
    }

/**
 * 加入购物车 
 * Author Amber
 * Date 2018-10-16
 * Params [params]
 * @param Request $request [description]
 */
	public function add_buycar(Request $request)
	{
        
        $user_id = $request->input("user_id");
        $goods_id = $request->input("goods_id");
        $buy_num = $request->input("buy_num");
		$buycar_type = $request->input("buyCar_type");
        $type = intval($buycar_type);
        if($type == 1){
            $type == 0;
        }
        // gettype($buycar_type);
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
        if ($type > 1) {
            $res = array(
                "errNo" => "0002",
                "errMsg" => "购物车类型只能是0或1"
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

        $res = $GoodsBuyCarModel->add_buycar($user_id,$goods_id,$buy_num,$type);


        if($res == FALSE){
            $ret = array(
                "errNo" => "0003",
                "errMsg" => "系统错误"
            );
            $this->_response($ret);
        }
        $ret = array(
            "errNo" => 0,
            "errMsg" => "success",
            "data" => "添加购物车成功"
        );

        $this->_response($ret);

	}

    public function homepagetwo_list(Request $request)
    {
        
        $cat_id = $request->input("cat_id");

        $GoodsModel = new GoodsCatModel();

        $res = $GoodsModel->only_two_homelist($cat_id);
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
 * 购物车展示
 * Author Amber
 * Date 2018-07-24
 * Params [params]
 * @param  Request $request [description]
 * @return [type]           [description]
 */
    public function show_buycar(Request $request)
    {
        // echo 1;die;
        $user_id = $request->input('user_id');
        $buyCar_type = $request->input('buyCar_type');

        $GoodsModel = new GoodsBuyCarModel();

        $res = $GoodsModel->show_buycar($user_id,$buyCar_type);
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
/**
 * 删除购物车 
 * Author Amber
 * Date 2019-02-01
 * Params [params]
 * @param  Request $request [description]
 * @return [type]           [description]
 */
    public function del_buycar(Request $request)
    {
        $user_id = $request->input('user_id');
        $productSku_id = $request->input('productSku_id');
        $GoodsModel = new GoodsBuyCarModel();
        $res = $GoodsModel->del_buycar($user_id,$productSku_id);
        if($res == FALSE){
            $res = array(
                "errNo" => "8004",
                "errMsg" => "删除失败"
            );
            $this->_response($res);
        }
        $res = array(
            "errNo" => 0,
            "errMsg" => "删除成功"
        );
        $this->_response($res);
    }
}