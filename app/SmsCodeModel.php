<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

use DB;
use Aliyun\Core\Config as AliyunConfig;
use Aliyun\Core\Profile\DefaultProfile;
use Aliyun\Core\DefaultAcsClient;
use Aliyun\Api\Sms\Request\V20170525\SendSmsRequest;
use Aliyun\Api\Sms\Request\V20170525\QuerySendDetailsRequest;
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
        // print_r($sendInfo);die;
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
        //$add = true;
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
        // if(time() - $create_time < 60){
        //     $res = array(
        //         "errNo" => "1001",
        //         "errMsg" => "验证码发送过于频繁"
        //     );
        //     return $res;
        // }

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
        //echo 111;die;
        // echo $code;die;
                    // 阿里云Access Key ID和Access Key Secret 从 https://ak-console.aliyun.com 获取
            $appKey = 'LTAIUEbd0H2A8dBa';
            $appSecret = 'eYVSVPY36otKJWJIGAsWSPD0TQUyyo';

            // 短信签名 详见：https://dysms.console.aliyun.com/dysms.htm?spm=5176.2020520001.1001.3.psXEEJ#/sign
            $signName  = '注册验证';

            // 短信模板Code https://dysms.console.aliyun.com/dysms.htm?spm=5176.2020520001.1001.3.psXEEJ#/template
            $template_code = 'SMS_33560277';

            // 短信中的替换变量json字符串.$product;
            
            $json_string_param = "{'code':$code,'product':'实锤'}";

            // 接收短信的手机号码
            $phone = $mobile;

            // 初始化阿里云config
            AliyunConfig::load();
            // 初始化用户Profile实例
            $profile = DefaultProfile::getProfile("cn-hangzhou", $appKey, $appSecret);
            DefaultProfile::addEndpoint("cn-hangzhou", "cn-hangzhou", "Dysmsapi", "dysmsapi.aliyuncs.com");
            $acsClient = new DefaultAcsClient($profile);
            // 初始化SendSmsRequest实例用于设置发送短信的参数
            $request = new SendSmsRequest();
            // 必填，设置短信接收号码
            $request->setPhoneNumbers($phone);
            // 必填，设置签名名称
            $request->setSignName($signName);
            // 必填，设置模板CODE
            $request->setTemplateCode($template_code);

            // 可选，设置模板参数
            if(!empty($json_string_param)) {
                $request->setTemplateParam($json_string_param);
            }

            // 可选，设置流水号
            // if($outId) {
            //     $request->setOutId($outId);
            // }

            // 发起请求
            $acsResponse =  $acsClient->getAcsResponse($request);
            
            // 默认返回stdClass，通过返回值的Code属性来判断发送成功与否
            if($acsResponse && strtolower($acsResponse->Code) == 'ok')
            {
                
                return true;
            }
           
            return false;
        // // $appkey=26988;
        // // $sign= 'f44b8678e5e926838ff8af54388a5adf';
        // // $url="http://api.k780.com/?app=sms.send&tempid=51358&param=code%3D"
        // //     .$code."&phone=".$mobile."&appkey=".$appkey."&sign=".$sign."&format=json";
        // // $result = file_get_contents($url);
        
        // // return empty($result) ? false : true;

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
        // var_dump($ret);
        if(empty($ret) || time() > $ret['expire_time'])
        {
            // echo 1;die;
            return false;
        }
        if($code != $ret['sms_code']){
            // echo $code;die;
            // echo $ret['sms_code'];
              // echo 2;die;
            return false;
        }
          // echo 3;die;
        $this-> removeSmsCodeInfoByMobile($mobile);
        return true;
    }
}
