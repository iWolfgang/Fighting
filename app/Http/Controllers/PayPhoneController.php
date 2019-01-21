<?php
namespace App\Http\Controllers;

use App\Services\AopClient;
use App\Services\AlipayTradeAppPayRequest;
use Illuminate\Http\Request;
use Illuminate\Http\Redirect;
use DB;
Class PayPhoneController extends Controller
{

	public function index(Request $request)
	{
		$aop = new AopClient();
		$aop->gatewayUrl = "https://openapi.alipay.com/gateway.do";
		$aop->appId = "2019011062842792";
		$aop->rsaPrivateKey = 'MIIEowIBAAKCAQEA0eaUU8GLz93lCunIq+1mECFpABRPFE32+4Gri2PLRGI9ndbrQLpsQhqZNfxnJ1/cvI2kLNDs3/ZUkB/yi4OjRtHoCeRnzVZYnnsszweCxckPFr4BauSgsTGygpVrVbo2dIzBsBW2EWBHVi/ohWijEDs7kMLpsIFKJ4ug1qq5e5DGNHrv+cW/k+Goor6pWPoj6R8sjhfPuZYrN+JzgFnstvEBGL7yR2MrgKj84vzbgVLaX+CqRGe87fI9JquPhuNGB+cWoxuyqK2YSMzmXDTP+Tym/kDZytRtSODmZli6Ksny+MCMOr1qpuMPQ6bc0tTkKes6oFdO4fryvkRWWU/x3wIDAQABAoIBABN8WGC+IwCVFOJCecKcM1FkCJ9dQ0obQsZub0JtbT1X8Whpv0UvCUXJuldsqxbYq2FFtOwEKTlRYOBQVu/ktI+qhOQGNCy3y1pLDQnbJKS/2Yq+8Nq/hrtsZaoBvQkkVFHVj1WNbm2GhpjVsbxQznJ/TTRPI+qi1gN9ztye1MFHsDMsf4E5gWOnuiBoLZjDXRvit05Ow2XUbjHzqhtudErLFc8ZuokUuZC/Iz1w8VHVnVPK5v3kj0jcPwnDhkK8kRk3sa8nXLGQKO8H/eKSMPtFuXtOmNFU6+aIt7F3S6YzSq7Tz4Fta9x18My/WlmWqmRoGKFUUW+giDcJ3JYy8uECgYEA+ptyA6IfBZxW493clEyUS1DulAKpHuflI6FYy09yS/Vn7kL7++vhzvIruq86f+tpNOT/QnArH4zpE+z3zP9dGPoeDphtlS9tEqb/2M7BXgpZx9qJZHcERDJBecPp3bFabMo7rKiEMorh/eMZAtSzpCFX2Fi62WWcmD93DSDNJBsCgYEA1mrjchOchJBSJDIFAacz8ou+ArSU00w6icKOiRd3YGixnGu+2qLmZ5po5MP+VBAmnCnp30vBh8aL8UMFDYAq9W+1YqQil6DE4I27ra0XD78j+duCGUPAHoH6jglQLov+jP5HAVl2xDuSGSy1Ri02y2741NbNqFFd2u8lU/10HY0CgYBzqWCSqrVUmpZDrrbKPxnGNQEXkK7LU82ehy37D5y5z/Z6sbGo0HI0V/K0w4DlXxn8TqA84pYUhq1gA+NOWqF2EKHkrJcO3oehry+vuaTnKTHMmmEE3CU88FDlyPTb26nXQfMOuevhg9XPnouBkfejDbyEXldGVK5UWh4xEe179wKBgD6CVuCQ+xZihK/srSz4M9rIBpL/Vkvrcz1qLOemobTHkNALUU6oIwedKmtXADQ9qSPpzDa+/SK6LV4ercBr1xpKgNTLCRKvWfYlG8vcJFcA4FodNmZrK/0443S5HlkTkxhDoSuxi0BWJZeVQxu8XrccGQrjvH0Pi48iHP3JbCqZAoGBAOUIvrezFowg8ftSHj68h7138fnw3lS+C37GmQwv96ifl+WLD6RuBZNwauImutBS8LSrU+R4X86VB9yI89PXRUnwm/ZDdKxjtlIcWRbKdqcp6EyjU7NstHNc3CkpA7mEgVQeKJ2am2byCq8WQukYxGU1c5HQwJmveZkAc4hutKEW';
		$aop->format = "json";
		$aop->charset = "UTF-8";
		$aop->signType = "RSA2";
		$aop->alipayrsaPublicKey = 'MIIBIjANBgkqhkiG9w0BAQEFAAOCAQ8AMIIBCgKCAQEAjHrWWgUm0nmDbxf/0zobPwRFWZaEpWPo1OqxAa5RhwgAfnvZn+AdFIUIXFEUjsV0PfoOrNSyFuM2zSusMtJWer1xX6rUDbhrRpnaxuibyCJUPWr4zZ4WChRGpgIYp3+7NJeRHkWM24cbkKvdPl4432kpmlh29A0armcKEke5PwzTaV+T+r2VduVGAIezHL0w0APdJFOgsqG+JnO+7rS08y86OOFTYZ2V3xJma5qg4E1UmtCmCQBqC9+MFKgrMzVeQqySw4I6WAFCfe7z5SoioeeFXCuFTekLC8XPmcTZH6wByWzF1nDBzlW2oEEUy5bGhj19d91LaCls5TOTEVJ7SwIDAQAB';
		$notify_url = urlencode("http://api.mithrilgaming.com:7777/PayPhone/notify");
// 订单标题
		$subject = '实锤APP';
// 订单详情
		$body = '这是主题'; 
		//c传过来我需要的参数，然后再处理
        $order_id = $request->input("order_id");//支付金额
        session_start();
        session(['order_id' => $order_id]);
        // session(['total_amount' => $total_amount]);
        $res = $this->selectorder($order_id);
        $total_amount= $res['total_amount'];
        $out_trade_no = $res['no'];
		//SDK已经封装掉了公共参数，这里只需要传入业务参数
		// $bizcontent = "{\"body\":\"我是测试数据\",\"subject\": \"App支付\",\"out_trade_no\": \"$out_trade_no\",\"timeout_express\": \"30m\",\"total_amount\": \"$total_amount\"}";
$bizcontent = "{\"body\":\"".$body."\","
                . "\"subject\": \"".$subject."\","
                . "\"out_trade_no\": \"".$out_trade_no."\","
                . "\"timeout_express\": \"30m\","
                . "\"total_amount\": \"".$total_amount."\","
                . "\"product_code\":\"QUICK_MSECURITY_PAY\""
                . "}";
		//实例化具体API对应的request类,类名称和接口名称对应,当前调用接口名称：alipay.trade.app.pay
		$requests = new AlipayTradeAppPayRequest();
		$requests->setNotifyUrl($notify_url);
		$requests->setBizContent($bizcontent);
		// $requests->setNotifyUrl("http://api.mithrilgaming.com:7777/Pay/notify");
		// $requests->setBizContent($bizcontent);
		//这里和普通的接口调用不同，使用的是sdkExecute
		$response = $aop->sdkExecute($requests);
		// echo gettype($response);die;
		//$arr = explode('&',$response);//转换成数组，帮前端去掉$arrr[0]的参数
		//unset($arr[0]);
		// // print_r($arr);die;
		//$AR = implode('&',$arr);
		// // echo gettype($AR);
		// // dump($AR);die;
		//$respons = $aop->sdkExecute($AR);
		// $respons = $aop->sdkExecute($response);
		//return $response;
		$res = array(
                "data" => $response
            );
		return $res;
		//htmlspecialchars是为了输出到页面时防止被浏览器将关键参数html转义，实际打印到日志以及http传输不会有这个问题
		//return htmlspecialchars($response);//就是orderString 可以直接给客户端请求，无需再做处理。
		// openssl_sign(): supplied key param cannot be coerced into a private key

	}
	public function selectorder($order_id='')
	{
		 $ret = DB::table('g_orders')
		 	->select('total_amount','no')
            ->where('id', $order_id)
            ->first();
          $res = get_object_vars($ret);
          // dump($ret);die;

            // print_r($res);die;
            return $res;
	}
	//回调地址
	public function notify(request $request)
	{
		// echo 1notify;
		// var_dump($request);die;
		    // $out_trade_no = $orders['out_trade_no'];// 订单号
      //       $trade_no = $orders['trade_no'];// 支付宝交易号
            $aop = new AopClient;
			$aop->alipayrsaPublicKey = 'MIIBIjANBgkqhkiG9w0BAQEFAAOCAQ8AMIIBCgKCAQEAjHrWWgUm0nmDbxf/0zobPwRFWZaEpWPo1OqxAa5RhwgAfnvZn+AdFIUIXFEUjsV0PfoOrNSyFuM2zSusMtJWer1xX6rUDbhrRpnaxuibyCJUPWr4zZ4WChRGpgIYp3+7NJeRHkWM24cbkKvdPl4432kpmlh29A0armcKEke5PwzTaV+T+r2VduVGAIezHL0w0APdJFOgsqG+JnO+7rS08y86OOFTYZ2V3xJma5qg4E1UmtCmCQBqC9+MFKgrMzVeQqySw4I6WAFCfe7z5SoioeeFXCuFTekLC8XPmcTZH6wByWzF1nDBzlW2oEEUy5bGhj19d91LaCls5TOTEVJ7SwIDAQAB';
			$flag = $aop->rsaCheckV1($_POST, NULL, "RSA2");
            // $this->updateDB($out_trade_no,$trade_no);
            dump($flag);die;
	}
	//
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
