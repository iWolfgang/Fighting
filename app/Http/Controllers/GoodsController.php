<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Http\Redirect;
use App\GoodsModel;
use App\TapgetModel;
use App\GoodsCatModel;
class GoodsController extends Controller
{
    public function goods_full(Request $request)
    {
        $Tapmodel = new TapgetModel();
        $GoodsModel = new GoodsCatModel();
        $HomePageModel = new GoodsModel();

        $data['slideshow'] = $HomePageModel->slideshow();
        $data['homepage_list'] = $GoodsModel->only_this_homelist();
        $data['subject_goods'] = $Tapmodel->TapAndGoods();//里边是标签的基本信息
        if(empty($data)){
             $res = array(
            "errNo" => 0,
            'errMsg' => '没有拿到数据'
           );

          $this->_response($res);
        }
        $res = array(
            "errNo" => 0,
            'errMsg' => 'success',
            "data" => $data
        );
        $this->_response($res);
    }
/**
 * 专题商品列表 
 * Author Amber
 * Date 2018-10-10
 * Params [params]
 * @param  Request $request [description]
 * @return [type]           [description]
 */
    public function subject_goods(Request $request)
    {
        $Tapmodel = new TapgetModel();
        $taps = $Tapmodel->TapAndGoods();//里边是标签的基本信息
        // print_r($taps);die;
        $res = array(
            "errNo" => 0,
            'errMsg' => 'success',
            "data" => $taps
        );
        $this->_response($res);

    }

    public function subject_goodsitem(Request $request)
    {
        $tap_id = $request->input("tap_id");
        $Tapmodel = new TapgetModel();
        $taps = $Tapmodel->subject_goodsitem($tap_id);
        $res = array(
            "errNo" => 0,
            'errMsg' => 'success',
            "data" => $taps
        );
        $this->_response($res);
    }

 /**
 * 首页的轮播图 
 * Author Amber
 * Date 2018-05-08
 */
    public function slideshow(Request $request)
    {
        $HomePageModel = new GoodsModel();
        $ret = $HomePageModel->slideshow();
        if($ret == FALSE){
            $res = array(
                "errNo" => "0003",
                "errMsg" => "轮播图类型不符"
            );
            $this->_response($res);
        }
        $res = array(
            "errNo" => 0,
            'errMsg' => 'success',
            "data" => $ret
        );
        $this->_response($res);
    }

/**
 * 商品列表页 
 * Author Amber
 * Date 2018-10-10
 * Params [params]
 * @param  Request $request [description]
 * @return [type]           [description]
 */
	public function goods_list(Request $request)
	{
		$classify_id = $request->input("classify_id");

        $GoodsModel = new GoodsModel();

        $res = $GoodsModel->GoodsList($classify_id);

        if($res == FALSE){
            $res = array(
                "errNo" => "0003",
                "errMsg" => "此分类下暂无商品",
                "data" => null
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
/**
 * 电商列表----全部列表
 * Author Amber
 * Date 2018-12-25
 * Params [params]
 * @param  Request $request [description]
 * @return [type]           [description]
 */
    public function all_goodslist(Request $request)
    {
        $goods_catid = $request->input("goods_catid");
        $GoodsModel = new GoodsModel();
        $res = $GoodsModel->all_goodslist($goods_catid);
        if($res == FALSE){
            $res = array(
                "errNo" => 0,
                "errMsg" => "success",
                "errMsg" => "暂无商品"
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