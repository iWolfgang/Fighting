<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

use DB;

class UserModel extends Model
{
    public $_tabName = 't_user_info';

    /**
     * 用户注册 
     * Author JiaXu
     * Date 2018-04-07
     * Params [params]
     * @param  string  $user_mobile   [手机号码]
     * @param  string  $user_passwd   [登录密码]
     * @param  integer $sms_code      [短信验证码]
     * @param  string  $device_id     [注册设备id]
     * @param  string  $user_platform [注册平台]
     */
    public function regist($user_mobile = '', $user_passwd = '', $sms_code = 0, $device_id = '', $user_platform = '')
    {
        $checkSmsCode = $this->checkSmsCode($user_mobile, $sms_code);
        if($checkSmsCode == FALSE){
            $res = array(
                "errNo" => "1002",
                "errMsg" => "短信验证码错误"
            );

            return $res;
        }

        $isRegist = $this->checkMobileIsRegist($user_mobile);

        if($isRegist){
            $res = array(
                "errNo" => "1003",
                "errMsg" => "该手机号码已被注册"
            );

            return $res;
        }

        $add = $this->addUserInfoByMobile($user_mobile, $user_passwd, $device_id, $user_platform);

        if($add == false){
            $res = array(
                "errNo" => "1004",
                "errMsg" => "用户注册失败"
            );

            return $res;
        }
        return $add;
    }

    /**
     * 校验短信验证码 
     * Author JiaXu
     * Date 2018-04-07
     * Params [params]
     * @param  string  $user_mobile [手机号码]
     * @param  integer $sms_code    [短信验证码]
     */
    public function checkSmsCode($user_mobile = '', $sms_code = 0)
    {
        return true;
    }

    /**
     * 检测手机号码是否注册 
     * Author JiaXu
     * Date 2018-04-07
     * Params [params]
     * @param  string $mobile [手机号码]
     */
    public function checkMobileIsRegist($mobile = '')
    {
        $count = DB::table($this->_tabName)
            ->where("user_mobile", $mobile)
            ->count();

        return $count > 0 ? true : false;
    }

    /**
     * 添加手机登录用户 
     * Author JiaXu
     * Date 2018-04-07
     * Params [params]
     * @param string $user_mobile   [手机号码]
     * @param string $user_passwd   [登录密码]
     * @param string $device_id     [注册设备id]
     * @param string $user_platform [用户注册平台]
     */
    public function addUserInfoByMobile($user_mobile = '', $user_passwd = '', $device_id = '', $user_platform = '   ')
    {
        $data = array();
        $data['user_mobile'] = $user_mobile;
        $data['user_passwd'] = $this->createPasswd($user_passwd);
        $data['user_type'] = 1; //用户类型 1-手机号码 2-微信 3-QQ
        $data['device_id'] = $device_id;
        $data['user_platform'] = $user_platform;
        $data['create_time'] = time();

        $add = DB::table($this->_tabName)
            ->insert($data);

        return $add;
    }

    /**
     * 创建密码 
     * Author JiaXu
     * Date 2018-04-07
     * Params [params]
     * @param  string $passwd [密码原串]
     */
    public function createPasswd($passwd = '')
    {
        $signStr = "r1zhaox1anglushengz1yan";

        return md5($passwd . $signStr);
    }
}
