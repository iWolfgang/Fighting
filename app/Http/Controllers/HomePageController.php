<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Http\Redirect;
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
    /**
     * 轮播图 添加
     * Author Amber
     * Date 2018-05-08
     */
    public function slideshow_add(Request $request){

        $slideshow = $request->file("slideshow");
        
        $slideshow_url = $request->input("slideshow_url");
        $slideshow_type = $request->input("slideshow_type");
        $slideshow_title = $request->input("title");

        $HomePageModel = new HomePageModel();

        $ret = $HomePageModel->slideshow_add( $slideshow,  $slideshow_title, $slideshow_url, $slideshow_type);
        if($ret == FALSE){
             echo "<script>alert('添加失败');window.location.href = 'http://dev.api.miyin.com//Rbac/banner'</script>";
        }else{
            echo "<script>alert('添加成功');window.location.href = 'http://dev.api.miyin.com//Rbac/banner'</script>";
           

        }

     
    }
}
