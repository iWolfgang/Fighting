<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

use DB;
use Illuminate\Support\Facades\Redis;
//Call to undefined method Illuminate\Database\MySqlConnection::()
use Illuminate\Database\MySqlConnection\paginate;

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
     * 用户登录 
     * Author Liuran
     * Date 2018-04-07
     * Params [params]
     * @param  string  $user_mobile [手机号]
     * @param  integer $login_type  [登陆类型 1-短信登陆 2-密码登录]
     * @param  string  $sms_code    [短信验证码]
     * @param  string  $user_passwd [用户密码]
     */
    public function login($user_mobile = '', $login_type = 0, $sms_code = '', $user_passwd = '')
    {
        $userId = 0;

        $isRegist = $this->checkMobileIsRegist($user_mobile);

        if($isRegist == false){
            $res = array(
                "errNo" => "1003",
                "errMsg" => "该手机号码尚未注册"
            );

            return $res;
        }

        if($login_type == 1){
            $userId = $this->getUserIdBySmsCode($user_mobile, $sms_code);
        }

        if($login_type == 2){
            $userId = $this->getUserIdByUserPasswd($user_mobile, $user_passwd);
        }

        if($userId == FALSE){
            $res = array(
                "errNo" => "1004",
                "errMsg" => "用户登录失败"
            );

            return $res;
        }

       // $userId = 1;
        if(isset($userId['errNo'])){
            return $userId;
        }


        $userToken = $this->createUserToken($userId);

        $res = array(
            "token" => $userToken,
            "user_id" => $userId
        );
        return $res;
    }

    /**
     * 创建用户token 
     * Author Amber
     * Date 2018-04-11
     * Params [params]
     * @param  integer $user_id [用户id]
     */
    public function createUserToken($user_id = 0)
    {
        $time = time();
        $secret = md5($user_id . $time . rand(1111,9999));

        $token = sprintf("%s|%s|%s", $user_id, $time, $secret);

        $key = sprintf("MYAPI_USER_TOKEN_%s", $user_id);

        $set = Redis::set($key, $secret);

        return $token;
    }

    /**
     * 短信验证码登陆
     * Author Liuran
     * Date 2018-04-07
     * Params [params]
     * @param  string $user_mobile [用户手机号]
     * @param  string $sms_code    [短信验证码]
     */
    public function getUserIdBySmsCode($user_mobile = '', $sms_code = '')
    {
       $checkSmsCode = $this->checkSmsCode($user_mobile, $sms_code);
       // $checkSmsCode = true;
       if($checkSmsCode == FALSE){
            $res = array(
                "errNo" => "1002",
                "errMsg" => "短信验证码错误"
            );

            return $res;
        }
        $res = $this->getUserInfoByMobile($user_mobile);
print_r($res);die;
        return $res;
    }

    /**
     * 通过密码获取用户id 
     * Author Liuran
     * Date 2018-04-07
     * Params [params]
     * @param  string $user_mobile [手机号码]
     * @param  string $user_passwd [登陆密码]
     */
    public function getUserIdByUserPasswd($user_mobile = '', $user_passwd = '')
    {
        $userInfo = $this->getUserInfoByMobile($user_mobile);

        if($userInfo == false){
            $res = array(
                "errNo" => "1005",
                "errMsg" => "用户信息获取失败"
            );

            return $res;
        }
        if($userInfo['user_passwd'] != $this->createPasswd($user_passwd)){
            $res = array(
                "errNo" => "1006",
                "errMsg" => "密码有误，请重新输入"
            );
            return $res;
        }
        return $userInfo['id'];
    }


    /**
     * 通过手机号获取用户信息 
     * Author Liuran
     * Date 2018-04-07
     * Params [params]
     * @param  string $user_mobile [手机号码]
     */
    public function getUserInfoByMobile($user_mobile = '')
    {
        $userInfo = DB::table($this->_tabName)
            ->where('user_mobile', $user_mobile)
            ->first();

        return empty($userInfo) ? false : get_object_vars($userInfo);
    }

    /**
     * 通过手机号获取用户Id 
     * Author Liuran
     * Date 2018-04-07
     * Params [params]
     * @param  string $user_mobile [手机号码]
     */
    public function getUserIdByUserMobile($user_mobile = '')
    {
        $userId = DB::table($this->_tabName)
            ->where('user_mobile', $user_mobile)
            ->pluck('id')
            ->first();

        return empty($userId) ? false : $userId;
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
        $SmsCodeModel = new SmsCodeModel();
        
        return $SmsCodeModel->chenckCode($user_mobile, $sms_code);
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


    /**
     * 用户列表
     * Author JiaXu
     * Date 2018-04-07
     * Params [params]
     * @param  string $passwd [密码原串]
     */
    public function UserList( )
    {
     // $User =  DB::select('select id,user_name,user_mobile,updatetime from t_user_info');
      //  $User = DB::;
       // $aa = $User->paginate(5);
      //  return empty($aa) ? false : json_decode(json_encode($aa), true);
        $users = DB::table('t_user_info')->paginate(2);
        return view('user.index', ['users' => $users]);
    }
/**
 * 用户信息查询
 * Author Amber
 * Date 2018-06-19
 * Params [params]
 * @param  [type] $user_id [description]
 * @return [type]          [description]
 */
    public function userinfo($user_id)
    {
       $count = DB::table('t_user_info')
        ->select('t_user_info.id','t_user_info.user_mobile','t_user_infos.head portrait','t_user_infos.sex','t_user_infos.email','t_user_infos.id_attestation','t_user_infos.shipping address')
        ->where("user_id", $user_id)
        ->join('t_user_infos','user_id','t_user_info.id')
        ->first();

       return $count;
    }
}
