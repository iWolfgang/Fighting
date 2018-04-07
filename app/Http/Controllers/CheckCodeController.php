<?php
namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\SmsCodeModel;
class CheckCodeController extends Controller
{
    /**
     * 校验短信验证码
     * Author Liuran
     * Date 2018-04-07
     * Route::post('/CheckCode/checkCode', 'CheckCodeController@checkCode');
     */
    public function chenckCode($mobile,$code)
    {
        if (preg_match("/^1[34578]{1}\d{9}$/", $mobile) == FALSE) {
            $res = array(
                "errNo" => "0002",
                "errMsg" => "手机号码格式不正确"
            );
            $this->_response($res);
        }
        $SmsCode = new SmsCodeModel;
        $ret = $SmsCode->getSmsCodeInfoByMobile($mobile);
        if($ret === False){
           // $SmsCode-> createSmsCodeInfoByMobile($mobile);
            $res = array(
                "errNo"=>"0003",
                "errMsg"=>"验证码校验错误"
            );
        }
        //判断过期时间<time():验证码校验错误
        if($ret['expire_time'] <time())
        {
            $res = array(
                "errNo"=>"0003",
                "errMsg"=>"验证码校验错误"
            );
        }else{
            if($code ==$ret['sms_code']){
               $SmsCode-> removeSmsCodeInfoByMobile($mobile);
            }
            $res = array(
                "errNo"=>"0003",
                "errMsg"=>"验证码校验错误"//不一致
            );

        }

    }
}