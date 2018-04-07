<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

use DB;

class SmsCodeModel extends Model
{
    public $_tabName = 't_sms_code';

    /**
     * 发送短信验证码 
     * Author JiaXu
     * Date 2018-04-04
     * Params [params]
     * @param  string $mobile [手机号码]
     */
    public function sendCode($mobile = '')
    {
        $sendInfo = $this->getSmsCodeInfoByMobile($mobile);

        if($sendInfo === FALSE){
            $res = $this->createSmsCodeInfoByMobile($mobile);
        }else{
            $res = $this->reCreateSmsCodeInfoByMobile($mobile, $sendInfo['create_time']);
        }

        return $res;
        
    }

    /**
     * 通过手机号获取发送记录 
     * Author JiaXu
     * Date 2018-04-04
     * Params [params]
     * @param  string $mobile [发送的手机号码]
     */
    public function getSmsCodeInfoByMobile($mobile = '')
    {
        $SmsCodeInfo = DB::table($this->_tabName)
            ->where("sms_mobile", $mobile)
            ->first();

        return $SmsCodeInfo ? get_object_vars($SmsCodeInfo) : FALSE;
    }

    /**
     * 通过手机号码创建短信记录 
     * Author JiaXu
     * Date 2018-04-04
     * Params [params]
     * @param  string $mobile [发送的手机号码]
     */
    public function createSmsCodeInfoByMobile($mobile = '')
    {
        $data = array();
        $data['sms_mobile'] = $mobile;
        $data['sms_code'] = $this->createSmsCode();
        $data['expire_time'] = time() + 600; //有效期10分钟
        $data['create_time'] = time();

        $add = DB::table($this->_tabName)
            ->insert($data);

        if($add){
            $this->sendSmsCodeToAPI($data['sms_mobile'], $data['sms_code']);
        }
        return $add;
    }

    /**
     * 重复发送验证码逻辑 
     * Author JiaXu
     * Date 2018-04-04
     * Params [params]
     * @param  string  $mobile      [手机号码]
     * @param  integer $create_time [创建时间 时间戳]
     */
    public function reCreateSmsCodeInfoByMobile($mobile = '', $create_time = 0)
    {
        if(time() - $create_time < 60){
            $res = array(
                "errNo" => "1001",
                "errMsg" => "验证码发送过于频繁"
            );
            return $res;
        }

        $this->removeSmsCodeInfoByMobile($mobile);

        $res = $this->createSmsCodeInfoByMobile($mobile);

        return $res;
    }

    /**
     * 通过手机号码删除短信记录 
     * Author JiaXu
     * Date 2018-04-04
     * Params [params]
     * @param  string $mobile [手机号码]
     */
    public function removeSmsCodeInfoByMobile($mobile = '')
    {
        $del = DB::table($this->_tabName)
            ->where("sms_mobile", $mobile)
            ->delete();

        return $del;
    }

    /**
     * 创建短信验证码 
     * Author JiaXu
     * Date 2018-04-04
     */
    public function createSmsCode()
    {
        return rand(111111,999999);
    }

    /**
     * 请求发送短信API
     * Author JiaXu
     * Date 2018-04-04
     * Params [params]
     * @param  string $mobile [手机号码]
     * @param  string $code   [短信验证码]
     */
    public function sendSmsCodeToAPI($mobile = '', $code = '')
    {
        return true;
    }
    /**
     *验证码校验
     * Author Liuran
     * Date 2018-04-07
     * Params [params]
     * @param  string $mobile [手机号码]
     * @param  string $code   [短信验证码]
     */
    public function chenckCode($mobile = '', $code = ''){
        $ret = $this->getSmsCodeInfoByMobile($mobile);

        if(empty($ret) || time() > $ret['expire_time'])
        {
            return false;
        }
        if($code != $ret['sms_code']){
            return false;
        }//
        $this-> removeSmsCodeInfoByMobile($mobile);
        return true;
    }
}
