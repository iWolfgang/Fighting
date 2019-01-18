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
        'app_id' => '2019011062842792',
        'notify_url' => 'http://api.mithrilgaming.com:7777/Pay/notify',//notify_url 代表服务器端回调地址，return_url 代表前端回调地址
        'return_url' => '',//http://yansongda.cn/return.php
        'ali_public_key' => 'MIIBIjANBgkqhkiG9w0BAQEFAAOCAQ8AMIIBCgKCAQEAjHrWWgUm0nmDbxf/0zobPwRFWZaEpWPo1OqxAa5RhwgAfnvZn+AdFIUIXFEUjsV0PfoOrNSyFuM2zSusMtJWer1xX6rUDbhrRpnaxuibyCJUPWr4zZ4WChRGpgIYp3+7NJeRHkWM24cbkKvdPl4432kpmlh29A0armcKEke5PwzTaV+T+r2VduVGAIezHL0w0APdJFOgsqG+JnO+7rS08y86OOFTYZ2V3xJma5qg4E1UmtCmCQBqC9+MFKgrMzVeQqySw4I6WAFCfe7z5SoioeeFXCuFTekLC8XPmcTZH6wByWzF1nDBzlW2oEEUy5bGhj19d91LaCls5TOTEVJ7SwIDAQAB',
        // 加密方式： **RSA2**  
        'private_key' => 'MIIEowIBAAKCAQEA0eaUU8GLz93lCunIq+1mECFpABRPFE32+4Gri2PLRGI9ndbrQLpsQhqZNfxnJ1/cvI2kLNDs3/ZUkB/yi4OjRtHoCeRnzVZYnnsszweCxckPFr4BauSgsTGygpVrVbo2dIzBsBW2EWBHVi/ohWijEDs7kMLpsIFKJ4ug1qq5e5DGNHrv+cW/k+Goor6pWPoj6R8sjhfPuZYrN+JzgFnstvEBGL7yR2MrgKj84vzbgVLaX+CqRGe87fI9JquPhuNGB+cWoxuyqK2YSMzmXDTP+Tym/kDZytRtSODmZli6Ksny+MCMOr1qpuMPQ6bc0tTkKes6oFdO4fryvkRWWU/x3wIDAQABAoIBABN8WGC+IwCVFOJCecKcM1FkCJ9dQ0obQsZub0JtbT1X8Whpv0UvCUXJuldsqxbYq2FFtOwEKTlRYOBQVu/ktI+qhOQGNCy3y1pLDQnbJKS/2Yq+8Nq/hrtsZaoBvQkkVFHVj1WNbm2GhpjVsbxQznJ/TTRPI+qi1gN9ztye1MFHsDMsf4E5gWOnuiBoLZjDXRvit05Ow2XUbjHzqhtudErLFc8ZuokUuZC/Iz1w8VHVnVPK5v3kj0jcPwnDhkK8kRk3sa8nXLGQKO8H/eKSMPtFuXtOmNFU6+aIt7F3S6YzSq7Tz4Fta9x18My/WlmWqmRoGKFUUW+giDcJ3JYy8uECgYEA+ptyA6IfBZxW493clEyUS1DulAKpHuflI6FYy09yS/Vn7kL7++vhzvIruq86f+tpNOT/QnArH4zpE+z3zP9dGPoeDphtlS9tEqb/2M7BXgpZx9qJZHcERDJBecPp3bFabMo7rKiEMorh/eMZAtSzpCFX2Fi62WWcmD93DSDNJBsCgYEA1mrjchOchJBSJDIFAacz8ou+ArSU00w6icKOiRd3YGixnGu+2qLmZ5po5MP+VBAmnCnp30vBh8aL8UMFDYAq9W+1YqQil6DE4I27ra0XD78j+duCGUPAHoH6jglQLov+jP5HAVl2xDuSGSy1Ri02y2741NbNqFFd2u8lU/10HY0CgYBzqWCSqrVUmpZDrrbKPxnGNQEXkK7LU82ehy37D5y5z/Z6sbGo0HI0V/K0w4DlXxn8TqA84pYUhq1gA+NOWqF2EKHkrJcO3oehry+vuaTnKTHMmmEE3CU88FDlyPTb26nXQfMOuevhg9XPnouBkfejDbyEXldGVK5UWh4xEe179wKBgD6CVuCQ+xZihK/srSz4M9rIBpL/Vkvrcz1qLOemobTHkNALUU6oIwedKmtXADQ9qSPpzDa+/SK6LV4ercBr1xpKgNTLCRKvWfYlG8vcJFcA4FodNmZrK/0443S5HlkTkxhDoSuxi0BWJZeVQxu8XrccGQrjvH0Pi48iHP3JbCqZAoGBAOUIvrezFowg8ftSHj68h7138fnw3lS+C37GmQwv96ifl+WLD6RuBZNwauImutBS8LSrU+R4X86VB9yI89PXRUnwm/ZDdKxjtlIcWRbKdqcp6EyjU7NstHNc3CkpA7mEgVQeKJ2am2byCq8WQukYxGU1c5HQwJmveZkAc4hutKEW',
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
        // 'mode' => '', // optional,设置此参数，将进入沙箱模式
    ];

    public function index(Request $request)
    {
        // dd($request);die;
        $total_amount = $request->input("total_amount");//支付金额
        $order_id = $request->input("order_id");//支付金额
        session_start();
        session(['order_id' => $order_id]);
        session(['total_amount' => $total_amount]);
        // $total_amount = 20;//支付金额
       // $order_id = 1;//

        $order = [
            'out_trade_no' => time(),
            'total_amount' => $total_amount,
            'subject' => 'test subject - 测试',
        ];
          return Pay::alipay($this->config)->app($order);
        // $alipay = Pay::alipay($this->config)->Web($order);

        // return $alipay;
    }

    public function creat_sign($value='')
    {
            session_start();
            $order_id =session('order_id');
            $total_amount =session('total_amount');
            
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