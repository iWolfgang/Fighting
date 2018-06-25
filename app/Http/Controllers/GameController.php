<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Http\Redirect;
use App\GameModel;
use App\HomePageModel;

class GameController extends Controller
{


	public function game_info(Request $request)
	{
		$user_id = $request->input("user_id");
		$game_id = $request->input("game_id");

        $HomePageModel = new HomePageModel();

        $res = $HomePageModel->long_articlelist($game_id);

        $GameModel = new GameModel();

        $ret = $GameModel->game_info($user_id,$game_id);

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
            "data" => $ret,
            "game_news" => $res
        );

        $res = array(
            "errNo" => 0,
            "errMsg" => "success",
            "data" => $data
        );

        $this->_response($res);

	}


    public function game_banner(Request $request)
    {
       // echo time();die;

        $HomePageModel = new HomePageModel();
        $ret = $HomePageModel->slideshow();//banner条
      
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

    public function in_vogue($value='')
    {
        $GameModel = new GameModel();
        $ret = $GameModel->in_vogue();
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

    public function new_Arrival($value='')
    {
        $GameModel = new GameModel();
        $ret = $GameModel->new_Arrival();        
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

    public function be_up_game($value='')
    {
        $GameModel = new GameModel();
        $ret = $GameModel->be_up_game();        
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
}