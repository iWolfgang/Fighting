<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Http\Redirect;
use App\GoodsCatModel;
// use App\HomePageModel;

class GoodsCatController extends Controller
{


	public function homepage_list(Request $request)
	{

        $GoodsModel = new GoodsCatModel();

        $res = $GoodsModel->only_this_homelist();
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

    public function PC_DIY(Request $request)
    {
        
    }
}