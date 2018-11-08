<?php

namespace App\Http\Controllers;

use Yansongda\Pay\Pay;
use Yansongda\Pay\Log;

class WePayController extends Controller
{
    protected $config;
    public function __construct(){
        $this->config =  [
        'appid' => 'wx6f1fa22ecd6e7638', // APP APPIDwx6f1fa22ecd6e7638
        //wx6flfa22ecd6e7638
        'mch_id' => '1517826431',//第一步获取到的商户号 1501006831
        'key' => 'XImSWSjmZ5NH7ddhPgXle9o4DfGdbzMy',
        'notify_url' => 'http://api.mithrilgaming.com:8888/Pay/rollback',
        'cert_client' => resource_path('wechat_pay/apiclient_cert.pem'), // optional，退款等情况时用到
        'cert_key' => resource_path('wechat_pay/apiclient_key.pem'),// optional，退款等情况时用到
        'log' => [ // optional
            'file' => './logs/wechat.log',
          //  'level' => 'debug', // 建议生产环境等级调整为 info，开发环境为 debug
          //  'type' => 'single', // optional, 可选 daily.
          //  'max_file' => 30, // optional, 当 type 为 daily 时有效，默认 30 天
                 ]
        ];
    }

    public function index($)
    {
        // echo 1;die;
        $order = [
            'out_trade_no' => time(),
            'total_fee' => '1', // **单位：分**
            'body' => 'test body - 测试',
            'openid' => 'onkVf1FjWS5SBIixxxxxxx',
        ];

        $pay = Pay::wechat($this->config)->app($order);

        // $pay->appId
        // $pay->timeStamp
        // $pay->nonceStr
        // $pay->package
        // $pay->signType
    }

    public function rollback()
    {
         $pay = Pay::wechat($this->config);

        try{
            $data = $pay->verify(); // 是的，验签就这么简单！->all()

            Log::debug('Wechat notify', $data);
        } catch (Exception $e) {
            // $e->getMessage();
        }
        
        return $pay->success();// laravel 框架中请直接 `return $pay->success()`
    }

}
