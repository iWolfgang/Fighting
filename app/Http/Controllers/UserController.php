<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\UserModel;
use App\GetPYModel;

class UserController extends Controller
{   
    /**
     * 用户注册 
     * Author JiaXu
     * Date 2018-04-07
     */
    public function regist(Request $request)
    {
        $user_mobile = $request->input("user_mobile");
       // $user_passwd = $request->input("user_passwd");

        $sms_code = $request->input("sms_code");

        // $device_id = $request->input("device_id");
        // $user_platform = $request->input("user_platform");

        if(empty($user_mobile) || preg_match("/^1[34578]{1}\d{9}$/",$user_mobile) == FALSE){
            $res = array(
                "errNo" => "0002",
                "errMsg" => "手机号码格式不正确"
            );
            $this->_response($res);
        }

        // if(empty($user_passwd) || strlen($user_passwd) != 32){
        //     $res = array(
        //         "errNo" => "0002",
        //         "errMsg" => "密码格式不正确"
        //     );
        //     $this->_response($res);
        // }

        if (empty($sms_code) || is_numeric($sms_code) == FALSE) {
            $res = array(
                "errNo" => "0002",
                "errMsg" => "短信验证码格式不正确"
            );
            $this->_response($res);
        }

        $UserModel = new UserModel();
        $ret = $UserModel->regist($user_mobile,$sms_code);

        if($ret == FALSE){
            $res = array(
                "errNo" => "0003",
                "errMsg" => "系统错误"
            );
            $this->_response($res);
        }elseif(isset($ret['errNo'])){
            $this->_response($ret);
        }

        $res = array(
            "errNo" => 0,
            "errMsg" => $ret

        );

        $this->_response($res);

    }

    /**
     * 用户登录
     * Author Liuran
     * Date 2018-04-07
     */
    public function login(Request $request)
    {
        $user_mobile = $request->input('user_mobile');

        $login_type = $request->input('login_type');

        $sms_code = $request->input('sms_code');
        $user_passwd = $request->input('user_passwd');    

        if(empty($user_mobile) || preg_match("/^1[34578]{1}\d{9}$/",$user_mobile) == FALSE){
            // echo 1;die;
            $res = array(
                "errNo" => "0002",
                "errMsg" => "手机号码格式不正确"
            );
            $this->_response($res);
        }

        if($login_type == 2 && (empty($user_passwd) || strlen($user_passwd) != 32)){
            // echo 2;die;
            $res = array(
                "errNo" => "0003",
                "errMsg" => "密码格式不正确"
            );
            $this->_response($res);
        }

        if ($login_type == 1 && (empty($sms_code) || is_numeric($sms_code) == FALSE)){
            // echo 3;die;
            $res = array(
                "errNo" => "0004",
                "errMsg" => "短信验证码格式不正确"
            );
            $this->_response($res);
        }

        if(empty($login_type)){
            $res = array(
                "errNo" => "0002",
                "errMsg" => "登录类型错误"
            );
            $this->_response($res);
        }

        $UserModel = new UserModel();

        $ret = $UserModel->login($user_mobile, $login_type, $sms_code, $user_passwd);
        // dump($ret);die;

        if($ret == FALSE){
            $res = array(
                "errNo" => "0003",
                "errMsg" => "系统错误"
            );
            $this->_response($res);
        }elseif(isset($ret['errNo'])){
            $this->_response($ret);
        }

        $res = array(
            "errNo" => 0,
            "errMsg" => "登陆成功",
            "data" => $ret
        );

        $this->_response($res);
    }


    public function testPY(Request $request)
    {
        $key = $request->input("key");

        $GetPYModel = new GetPYModel();

        $res = $GetPYModel->encode($key, "all");

        var_dump($key,$res);exit;

        $this->_response($res);
    }

/**
 * 个人中心
 * Author Amber
 * Date 2018-06-19
 * Params [params]
 * @param string $value [description]
 */
    public function userinfo(Request $request)
    {
        $key = $request->input("user_id");

        $GetPYModel = new UserModel();

        $ret = $GetPYModel->userinfo($key);

        if($ret == FALSE){
            $res = array(
                "errNo" => "0003",
                "errMsg" => "系统错误"
            );
            $this->_response($res);
        }elseif(isset($ret['errNo'])){
            $this->_response($ret);
        }

        $res = array(
            "errNo" => 0,
            "errMsg" => "登陆成功",
            "data" => $ret
        );
        $this->_response($res);
    }
/**
 * 添加add用户信息 
 * Author Amber
 * Date 2018-06-26
 * Params [params]
 * @param  Request $request [description]
 * @return [type]           [description]
 */
    public function userinfo_add(Request $request)
    {

        $head_img = $request->file("head_img");
        $user_id = $request->input("user_id"); 
        $user_name = $request->input("user_name"); 
        $sex = $request->input("sex"); 
        $email = $request->input("email"); 
        //=================收货地址=======================
        // $data['province'] = $request->input("province");
        // $data['city'] = $request->input("city");
        // $data['district'] = $request->input("district");
        // $data['address'] = $request->input("address");
        // $data['contact_name'] = $request->input("contact_name");
        // $data['contact_phone'] = $request->input("contact_phone");
        // $data['district'] = $request->input("district");
        // echo $email;die;
        $GetPYModel = new UserModel();

        $bool = $GetPYModel->userinfo_add($head_img,$user_name,$user_id,$sex,$email);

        // $bool = $this->_response($res);
        if($bool == FALSE){
            $res = array(
                "errNo" => "0003",
                "errMsg" => "系统错误"
            );
            $this->_response($res);
        }
        $res = array(
            "errNo" => 0,
            "errMsg" => "修改成功"
        );
        $this->_response($res);

    }
}
