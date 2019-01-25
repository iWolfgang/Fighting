<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Http\Redirect;
use App\GameModel;
use App\HomePageModel;
use App\GoodsModel;

class GameController extends Controller
{

/**
 * //游戏详情页
 * Author Amber
 * Date 2018-06-28
 */
	public function game_info(Request $request)
	{
        $game_id =  $request->input('game_id');
      
        $GameModel = new GameModel();

        $ret = $GameModel->game_info($game_id);
        // $ret['id'] = intval($game_id);
        $GoodsModel = new GoodsModel();

        $ret['game_info'] = $GoodsModel->detail_page($game_id);
        $ret['game_correlation'] = [];
        if($ret == FALSE){
            $res = array(
                "errNo" => "0003",
                "errMsg" => "系统错误"
            );
            $this->_response($res);
        }elseif(isset($ret['errNo'])){
            $this->_response($ret);
        }

        $data = array(
            "data" => $ret
        );

        $this->_response($data);

	}


    public function game_list(Request $request)
    {
        $HomePageModel = new GameModel();
        $ret = $HomePageModel->game_list();//banner条
      
        if($ret == FALSE){
            $res = array(
                "errNo" => "0003",
                "errMsg" => "系统错误"
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

    public function in_vogue($value='')
    {
        $GameModel = new GameModel();
        $ret = $GameModel->in_vogue();
        if($ret == FALSE){
            $res = array(
                "errNo" => "0003",
                "errMsg" => "暂无精品"
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

    public function new_Arrival($value='')
    {
        $GameModel = new GameModel();
        $ret = $GameModel->new_Arrival();        
         if($ret == FALSE){
            $res = array(
                "errNo" => "0003",
                "errMsg" => "暂无新品"
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

    public function discounts($value='')
    {
        $GameModel = new GameModel();
        $ret = $GameModel->discounts();        
        if($ret == FALSE){
            $res = array(
                "errNo" => "0003",
                "errMsg" => "暂无优惠商品"
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

        public function sell_hot($value='')
    {
        $GameModel = new GameModel();
        $ret = $GameModel->sell_hot();        
        if($ret == FALSE){
            $res = array(
                "errNo" => "0003",
                "errMsg" => "暂无热销商品"
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
}