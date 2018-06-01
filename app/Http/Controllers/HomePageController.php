<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\HomePageModel;

class HomePageController extends Controller
{
    /**
     * 首页的轮播图 
     * Author Amber
     * Date 2018-05-08
     */
    public function slideshow(Request $request)
    {
        $slideshow_type = $request->input("slideshow_type");

        $HomePageModel = new HomePageModel();

        $ret = $HomePageModel->slideshow($slideshow_type);

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
    public function slideshow_add(Request $request){
// echo 1;die;
        $slideshow = $request->file("slideshow");
        
        $slideshow_url = $request->input("slideshow_url");
        $slideshow_type = $request->input("slideshow_type");
        $slideshow_title = $request->input("title");

        $HomePageModel = new HomePageModel();

        $ret = $HomePageModel->slideshow_add( $slideshow,  $slideshow_title, $slideshow_url, $slideshow_type);
        if($ret == FALSE){
            $res = array(
                "errNo" => "0003",
                "errMsg" => "系统错误"
            );
            $this->_response($res);
        }

         $res = array(
                "errNo" => "0",
                "errMsg" => "添加成功"
            );

        $this->_response($res);
    }
}
