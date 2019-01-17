<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Http\Redirect;
use Yansongda\Pay\Pay;
use Yansongda\Pay\Log;
use DB;
class PayController extends Controller
{

  protected $config = [
        'app_id' => '2016091700532476',
        'notify_url' => 'http://api.mithrilgaming.com:7777/Pay/notify',//notify_url 代表服务器端回调地址，return_url 代表前端回调地址
        'return_url' => 'http://api.mithrilgaming.com:7777/Pay/notify',//http://yansongda.cn/return.php
        'ali_public_key' => 'MIIBIjANBgkqhkiG9w0BAQEFAAOCAQ8AMIIBCgKCAQEAoLddXIoQegGcJidCazdCkAP+uGXj4jnuRakg6rhyBchdzyWDd4nbStuzEF2XlyrZsECm8ew2BQh2DJrS0xQ291a4FwJzeqW3kQVNsbfy7gJ005n856jGQwBoXgqGZpGGrK+6b7XIIMYlqWNAMmReCfs8YwgO8KSRO+7QfRxVZQ9jIVlSiecHGoQ7JUjAgTyf64IjxPUdDM13pVG8ChnIxDTG2V/zal/fWHJ+IBkq1z8Ld027VxMf6rqwqmgQfUpuS/fU2uMZu7d87PgQiYpWZX53KPompaxOn+ziWmInnTpeHSZ9uRQO3ckSbZR3b7dZEKhkopcxohiPkL3vzvSVTwIDAQAB',
        // 加密方式： **RSA2**  
        'private_key' => 'MIIEpAIBAAKCAQEAxoKOeLL15+5Q3rvk3o3TSBh7Jm7Vc9qe3+MUCZLJXA2fxY40Slj1CRjJpCpw7Ima8yOzk5Jromkl0FsL1Sis+IpzItn8p920LD2nA2GOj30hhGPX8RZdpYHu1TiIFAoSPxppf/8Bl+cbhuluvHWdkFg9KuHH59kgCGTjTt+H+nnbDg09FalnIuVYfIHbRF3XghrbjG2gXJwvnbv5G/vn/cn8XNQd/kh0cFegYRnuM4Ytv1l12+oU//n4TUZiPEwRhQE1rKTfFWssmo1+pjKPHDzbcHKOZ5P5XXJmVQUXGHmFEZ1zwcTtcUxCvxubaAq9QjBCMD7yAHLP2FPOnAE1CwIDAQABAoIBAQCLr/Fbg+tAstWbdrKd3UUw9XErYVC4/r1PpLZD6lPhgADO/rtTGfgL17KE4AyolkhAEf5auO0e00j1rivMapwVJS/R9X0yDvOrMhMlcO/ljtMYGz4oe2mYfq82wVoYZ+HhH8/fbu2LG+il7furiERA/6bDVy/ZuVMuNPgzV16TZQ/31jDqELGEQuZNicEEVM58ycVw21LeXKup5EhAT4F6/LS7n8db+gHI+VutY3n9yajrWKagDsKEAC2tnuvEMOr5PdS9o5qRTKpauhExJ5rA9q1sEr+Uy2XS6qQkzgwBZCY7pRCj+UlBjbQaty241909PZpdLtSBQzrjiF35snE5AoGBAPQeuTDaMPiwUNoPWRdsbev6kSkJ7wa+lXEjNnfOMnmP0SQdGxWCG5mzuTvhU3RhZCC/zuugoK0bch4dVFzskQwQN2Uv5Np8jLEuCvcitEW+Ji2TR6mD5kepL4uYfesglHx5Zg2ODE2VQJGyH+RKmc+f0L1R6hnpGGMuOJrtPntVAoGBANArni8gBb4VeYdu87GE075hCiG9OfkuYp1V3i7fT2tc1bPIK+bw7e8e2w/9mHufm92ems92AQ4gkUejsCHy7dXMlH2bVxtKYufOj+hvQVBXlI8sXTd7LHM2tC4Fy8BGTCn5eFFXPjodRt+u4/zK0iTpWa/fwF10skfmn3cPwK7fAoGAefzEU+okYJFSxbS3s2HGiA487YH+RKOF7/RFqpaKWH6KZv54Y0YR3ruVi5usZVKpg0f18X7h6770RqInXwwD60BLPjAxrxBgCcXVSuu3o8ZCM7IONGIp95NOo/Y+rfko2g6b9ZgPA9HaYzPcherVc5AL8h712Z7GRgF53clB2MUCgYEAgkd59PYugdrAtwC6JJQDTHdAtwLM4GSmNN9DcMQy2Pc9iwkwaJTJ48fEw0orftrR2i3jpwir+mJpaZNIrMZzEEW7fKyWJC82nOpKZ+YQ0y4sfDoAkLNWizhmJRNdg5S7H93P7eASwp4F+AFlD5UDVTRw+7+ljRd2Z8cub339UbMCgYBDNvPQ26jnEQfVHaFyPRPfsHsgUfWZJjv22/8a++9F5rpzrJ692aHLL6CaxbQTAAclcPQQ+EJNn3VktLtK+KgSvCCLX37pZNl+rbVPRMagzyKJayKa9XBvf3p1JR2ZCJUn7GBVYKKIQd1TRuayaeZy5RtExlCfdGgbMURikBknrw==',
        'log' => [ // optional
            'file' => './logs/alipay.log',
            'level' => 'debug', // 建议生产环境等级调整为 info，开发环境为 debug
            'type' => 'single', // optional, 可选 daily.
            'max_file' => 30, // optional, 当 type 为 daily 时有效，默认 30 天
        ],
        'http' => [ // optional
            'timeout' => 5.0,
            'connect_timeout' => 5.0,
            // 更多配置项请参考 [Guzzle](https://guzzle-cn.readthedocs.io/zh_CN/latest/request-options.html)
        ],
        'mode' => 'dev', // optional,设置此参数，将进入沙箱模式
    ];

    public function index(Request $request)
    {
        // dd($request);die;
        $total_amount = $request->input("total_amount");//支付金额
        $order_id = $request->input("order_id");//支付金额
        session_start();
        session(['order_id' => $order_id]);
        // $total_amount = 20;//支付金额
       // $order_id = 1;//

        $order = [
            'out_trade_no' => time(),
            'total_amount' => $total_amount,
            'subject' => 'test subject - 测试',
        ];

        $alipay = Pay::alipay($this->config)->Web($order);

        return $alipay;
    }

    public function return()
    {
        $data = Pay::alipay($this->config)->verify(); // 是的，验签就这么简单！

        // 订单号：$data->out_trade_no
        // 支付宝交易号：$data->trade_no
        // 订单总金额：$data->total_amount
    }
    public function notify()
    {
        $alipay = Pay::alipay($this->config);
    
        try{
            $data = $alipay->verify(); // 是的，验签就这么简单！
            // dd($data);die;
            $orders = json_decode(json_encode($data), true);
            $out_trade_no = $orders['out_trade_no'];// 订单号
            $trade_no = $orders['trade_no'];// 支付宝交易号
            
            $this->updateDB($out_trade_no,$trade_no);
            // 请自行对 trade_status 进行判断及其它逻辑进行判断，在支付宝的业务通知中，只有交易通知状态为 TRADE_SUCCESS 或 TRADE_FINISHED 时，支付宝才会认定为买家付款成功。
            // 1、商户需要验证该通知数据中的out_trade_no是否为商户系统中创建的订单号；
            // 2、判断total_amount是否确实为该订单的实际金额（即商户订单创建时的金额）；
            // 3、校验通知中的seller_id（或者seller_email) 是否为out_trade_no这笔单据的对应的操作方（有的时候，一个商户可能有多个seller_id/seller_email）；
            // 4、验证app_id是否为该商户本身。
            // 5、其它业务逻辑情况
            //
            // Log::debug('Alipay notify', $data);//->all()
        } catch (Exception $e) {
            // $e->getMessage();
        }
        return $alipay->success();// laravel 框架中请直接 `return $alipay->success()`
    }
    
    public function Rollback()
    {
        //前端请求的api,返回来总金额，订单号，流水号，跟我们在notify()方法中拿到的数据进行匹配，正确的话 进行下一步，不正确的话返回下单失败，进行退款
    }
    public function updateDB($out_trade_no,$trade_no)
    {
        
        // $order_id = 2;//支付金额
        session_start();
        // $_SESSION[$order_id] = $order_id;
        
        $order_id =session('order_id');
        // echo $order_id;die;
        $paid_at = date('Y-m-d H:i:s');
        $ret = DB::table('g_orders')
            ->where('id', $order_id)
            ->update(['payment_no' => $out_trade_no,'payment_liu' => $trade_no,'payment_method' => '支付宝','paid_at' => $paid_at,'paid_status' => '已支付','ship_status' => '待发货']);

        if($ret == FALSE){
            $res = array(
                "errNo" => "0003",
                "errMsg" => "支付失败"
            );
            $this->_response($res);
        }
        $res = array(
            "errNo" => 0,
            "errMsg" => "支付成功"
        );
        $this->_response($res);
    }


}