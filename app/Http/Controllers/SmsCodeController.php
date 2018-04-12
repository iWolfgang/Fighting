<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\SmsCodeModel;

class SmsCodeController extends Controller
{
    /**
     * 发送短信验证码 
     * Author JiaXu
     * Date 2018-04-04
     * Route::post('/SmsCode/sendCode', 'SmsCodeController@sendCode');
     */
    public function sendCode(Request $request)
    {
        $mobile = $request->input("mobile");

        if(preg_match("/^1[34578]{1}\d{9}$/",$mobile) == FALSE){
            $res = array(
                "errNo" => "0002",
                "errMsg" => "手机号码格式不正确"
            );
            $this->_response($res);
        }
        
        $SmsCodeModel = new SmsCodeModel();

        $ret = $SmsCodeModel->sendCode($mobile);

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
            "errMsg" => "短信发送成功"
        );

        $this->_response($res);

    }
    public function checkCode(Request $request){
        $mobile = $request->input("mobile");
        $code = $request->input("code");
        $SmsCode = new SmsCodeModel();
        $res = $SmsCode->chenckCode($mobile,$code);
        // var_dump($res);

    }
}
