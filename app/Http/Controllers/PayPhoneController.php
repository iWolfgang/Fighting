<?php
namespace App\Http\Controllers;

use Illuminate\Support\Facades\Redis;
use Illuminate\Http\Request;
use Illuminate\Http\Redirect;
use DB;
use app\Packages\alipay;
// require ('AopSdk.php');
Class PayPhoneController extends Controller
{
    const ORDER_ID_KEY = 'ORDER_ID_%d'; //评论点赞的redis key
	public function index(Request $request)
	{
		$aop = new \AopClient();
		$aop->gatewayUrl = "https://openapi.alipay.com/gateway.do";
		$aop->appId = "2019011062842792";
		$aop->rsaPrivateKey = 'MIIEowIBAAKCAQEA0eaUU8GLz93lCunIq+1mECFpABRPFE32+4Gri2PLRGI9ndbrQLpsQhqZNfxnJ1/cvI2kLNDs3/ZUkB/yi4OjRtHoCeRnzVZYnnsszweCxckPFr4BauSgsTGygpVrVbo2dIzBsBW2EWBHVi/ohWijEDs7kMLpsIFKJ4ug1qq5e5DGNHrv+cW/k+Goor6pWPoj6R8sjhfPuZYrN+JzgFnstvEBGL7yR2MrgKj84vzbgVLaX+CqRGe87fI9JquPhuNGB+cWoxuyqK2YSMzmXDTP+Tym/kDZytRtSODmZli6Ksny+MCMOr1qpuMPQ6bc0tTkKes6oFdO4fryvkRWWU/x3wIDAQABAoIBABN8WGC+IwCVFOJCecKcM1FkCJ9dQ0obQsZub0JtbT1X8Whpv0UvCUXJuldsqxbYq2FFtOwEKTlRYOBQVu/ktI+qhOQGNCy3y1pLDQnbJKS/2Yq+8Nq/hrtsZaoBvQkkVFHVj1WNbm2GhpjVsbxQznJ/TTRPI+qi1gN9ztye1MFHsDMsf4E5gWOnuiBoLZjDXRvit05Ow2XUbjHzqhtudErLFc8ZuokUuZC/Iz1w8VHVnVPK5v3kj0jcPwnDhkK8kRk3sa8nXLGQKO8H/eKSMPtFuXtOmNFU6+aIt7F3S6YzSq7Tz4Fta9x18My/WlmWqmRoGKFUUW+giDcJ3JYy8uECgYEA+ptyA6IfBZxW493clEyUS1DulAKpHuflI6FYy09yS/Vn7kL7++vhzvIruq86f+tpNOT/QnArH4zpE+z3zP9dGPoeDphtlS9tEqb/2M7BXgpZx9qJZHcERDJBecPp3bFabMo7rKiEMorh/eMZAtSzpCFX2Fi62WWcmD93DSDNJBsCgYEA1mrjchOchJBSJDIFAacz8ou+ArSU00w6icKOiRd3YGixnGu+2qLmZ5po5MP+VBAmnCnp30vBh8aL8UMFDYAq9W+1YqQil6DE4I27ra0XD78j+duCGUPAHoH6jglQLov+jP5HAVl2xDuSGSy1Ri02y2741NbNqFFd2u8lU/10HY0CgYBzqWCSqrVUmpZDrrbKPxnGNQEXkK7LU82ehy37D5y5z/Z6sbGo0HI0V/K0w4DlXxn8TqA84pYUhq1gA+NOWqF2EKHkrJcO3oehry+vuaTnKTHMmmEE3CU88FDlyPTb26nXQfMOuevhg9XPnouBkfejDbyEXldGVK5UWh4xEe179wKBgD6CVuCQ+xZihK/srSz4M9rIBpL/Vkvrcz1qLOemobTHkNALUU6oIwedKmtXADQ9qSPpzDa+/SK6LV4ercBr1xpKgNTLCRKvWfYlG8vcJFcA4FodNmZrK/0443S5HlkTkxhDoSuxi0BWJZeVQxu8XrccGQrjvH0Pi48iHP3JbCqZAoGBAOUIvrezFowg8ftSHj68h7138fnw3lS+C37GmQwv96ifl+WLD6RuBZNwauImutBS8LSrU+R4X86VB9yI89PXRUnwm/ZDdKxjtlIcWRbKdqcp6EyjU7NstHNc3CkpA7mEgVQeKJ2am2byCq8WQukYxGU1c5HQwJmveZkAc4hutKEW';
		$aop->format = "json";
		$aop->charset = "UTF-8";
		$aop->signType = "RSA2";
		$aop->alipayrsaPublicKey = 'MIIBIjANBgkqhkiG9w0BAQEFAAOCAQ8AMIIBCgKCAQEAjHrWWgUm0nmDbxf/0zobPwRFWZaEpWPo1OqxAa5RhwgAfnvZn+AdFIUIXFEUjsV0PfoOrNSyFuM2zSusMtJWer1xX6rUDbhrRpnaxuibyCJUPWr4zZ4WChRGpgIYp3+7NJeRHkWM24cbkKvdPl4432kpmlh29A0armcKEke5PwzTaV+T+r2VduVGAIezHL0w0APdJFOgsqG+JnO+7rS08y86OOFTYZ2V3xJma5qg4E1UmtCmCQBqC9+MFKgrMzVeQqySw4I6WAFCfe7z5SoioeeFXCuFTekLC8XPmcTZH6wByWzF1nDBzlW2oEEUy5bGhj19d91LaCls5TOTEVJ7SwIDAQAB';
		$requests = new \AlipayTradeAppPayRequest();
        $order_id = $request->input("order_id");//支付金额
		$requests->setNotifyUrl("http://api.mithrilgaming.com:7777/PayPhone/notify?order_id=".$order_id);
		$subject = '实锤APP';// 订单标题
		$body = '这是主题'; // 订单详情
        $res = $this->selectorder($order_id);
        $total_amount= $res['total_amount'];
        $out_trade_no = $res['no'];
		//SDK已经封装掉了公共参数，这里只需要传入业务参数
		$bizcontent = "{\"body\":\"".$body."\","
                . "\"subject\": \"".$subject."\","
                . "\"out_trade_no\": \"".$out_trade_no."\","
                . "\"timeout_express\": \"30m\","
                . "\"total_amount\": \"".$total_amount."\","
                . "\"product_code\":\"QUICK_MSECURITY_PAY\""
                . "}";
		//实例化具体API对应的request类,类名称和接口名称对应,当前调用接口名称：alipay.trade.app.pay
		//$requests = new AlipayTradeAppPayRequest();
		//$requests->setNotifyUrl($notify_url);
		$requests->setBizContent($bizcontent);
		$response = $aop->sdkExecute($requests);
		$res = array(
                "data" => $response
            );
		return $res;

	}
	public function selectorder($order_id='')
	{
		$ret = DB::table('g_orders')
		 	->select('total_amount','no','payment_method')
            ->where('id', $order_id)
            ->first();
        $res = get_object_vars($ret);
        return $res;
	}
	//回调地址
	public function notify(request $request)
	{  
     
        $result = $request->all();
        $Comment = json_decode(json_encode($result), true);
        $aop = new \AopClient();
	    $aop->alipayrsaPublicKey = 'MIIBIjANBgkqhkiG9w0BAQEFAAOCAQ8AMIIBCgKCAQEAjHrWWgUm0nmDbxf/0zobPwRFWZaEpWPo1OqxAa5RhwgAfnvZn+AdFIUIXFEUjsV0PfoOrNSyFuM2zSusMtJWer1xX6rUDbhrRpnaxuibyCJUPWr4zZ4WChRGpgIYp3+7NJeRHkWM24cbkKvdPl4432kpmlh29A0armcKEke5PwzTaV+T+r2VduVGAIezHL0w0APdJFOgsqG+JnO+7rS08y86OOFTYZ2V3xJma5qg4E1UmtCmCQBqC9+MFKgrMzVeQqySw4I6WAFCfe7z5SoioeeFXCuFTekLC8XPmcTZH6wByWzF1nDBzlW2oEEUy5bGhj19d91LaCls5TOTEVJ7SwIDAQAB';
	    $flag = $aop->rsaCheckV1($result, NULL, "RSA2");
        $out_trade_no = $Comment['out_trade_no'];
        $trade_no =  $Comment['trade_no'];  
        // $paid_at = date('Y-m-d H:i:s'); 
        $order_id =$Comment['order_id'];
        $paid_at =$Comment['gmt_payment'];
     
        $ret = DB::table('g_orders')
            ->where('id', $order_id)
            ->update(['out_trade_no' => $out_trade_no,'payment_no' => $trade_no,'payment_method' => '支付宝','paid_at' => $paid_at,'paid_status' => '已支付','ship_status' => '待发货']);
        if($ret == FALSE){
            $res = array(
                "errNo" => "0003",
                "errMsg" => "支付失败"
            );
            // $this->_response($res);
        }
        $res = array(
            "errNo" => 0,
            "errMsg" => "支付成功"
        );
        // $this->_response($res);
   
      
	}
    // public function iiii(Request $request)
    // {
       //  $a = json_encode($data);
 
       // $str = trim($data,"{}");
       // $string = explode(',',$str);

       // $a = substr($string[21],15);
       // $s = trim($a,'""');
       // $aa = substr($string[23],12);
       // $st = trim($aa,'"');
       // $new = array();
       // $new['out_trade_no'] = $s;
       // $new['trade_no'] = $st;
       //  $this->updateDB($new);
    // }
   public function updateDB($data)
    {
        // $a = json_encode($data);

        $namafile = "updateDB.txt"; 
        $fh = fopen($namafile,"w");      
        fwrite($fh,"updateDB");
        fclose($fh);
        $out_trade_no = $data['out_trade_no'];
        $trade_no =  $data['trade_no'];  

        $paid_at = date('Y-m-d H:i:s'); 
        // if (!session_id()) session_start();
        // $order_id = session('order_id') ;
        $ret = DB::table('g_orders')
            ->where('id', 30)
            ->update(['out_trade_no' => $out_trade_no,'payment_no' => $trade_no,'payment_method' => '支付宝','paid_at' => $paid_at,'paid_status' => '已支付','ship_status' => '待发货']);
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
/**
 * 查询订单支付状态 
 * Author Amber
 * Date 2019-01-24
 * Params [params]
 * @param Request $reques [description]
 */
    public function SelectPay(Request $reques)
    {
        $aop = new \AopClient ();
        $aop->gatewayUrl = 'https://openapi.alipay.com/gateway.do';
        $aop->appId = '2019011062842792';
        $aop->rsaPrivateKey = 'MIIEowIBAAKCAQEA0eaUU8GLz93lCunIq+1mECFpABRPFE32+4Gri2PLRGI9ndbrQLpsQhqZNfxnJ1/cvI2kLNDs3/ZUkB/yi4OjRtHoCeRnzVZYnnsszweCxckPFr4BauSgsTGygpVrVbo2dIzBsBW2EWBHVi/ohWijEDs7kMLpsIFKJ4ug1qq5e5DGNHrv+cW/k+Goor6pWPoj6R8sjhfPuZYrN+JzgFnstvEBGL7yR2MrgKj84vzbgVLaX+CqRGe87fI9JquPhuNGB+cWoxuyqK2YSMzmXDTP+Tym/kDZytRtSODmZli6Ksny+MCMOr1qpuMPQ6bc0tTkKes6oFdO4fryvkRWWU/x3wIDAQABAoIBABN8WGC+IwCVFOJCecKcM1FkCJ9dQ0obQsZub0JtbT1X8Whpv0UvCUXJuldsqxbYq2FFtOwEKTlRYOBQVu/ktI+qhOQGNCy3y1pLDQnbJKS/2Yq+8Nq/hrtsZaoBvQkkVFHVj1WNbm2GhpjVsbxQznJ/TTRPI+qi1gN9ztye1MFHsDMsf4E5gWOnuiBoLZjDXRvit05Ow2XUbjHzqhtudErLFc8ZuokUuZC/Iz1w8VHVnVPK5v3kj0jcPwnDhkK8kRk3sa8nXLGQKO8H/eKSMPtFuXtOmNFU6+aIt7F3S6YzSq7Tz4Fta9x18My/WlmWqmRoGKFUUW+giDcJ3JYy8uECgYEA+ptyA6IfBZxW493clEyUS1DulAKpHuflI6FYy09yS/Vn7kL7++vhzvIruq86f+tpNOT/QnArH4zpE+z3zP9dGPoeDphtlS9tEqb/2M7BXgpZx9qJZHcERDJBecPp3bFabMo7rKiEMorh/eMZAtSzpCFX2Fi62WWcmD93DSDNJBsCgYEA1mrjchOchJBSJDIFAacz8ou+ArSU00w6icKOiRd3YGixnGu+2qLmZ5po5MP+VBAmnCnp30vBh8aL8UMFDYAq9W+1YqQil6DE4I27ra0XD78j+duCGUPAHoH6jglQLov+jP5HAVl2xDuSGSy1Ri02y2741NbNqFFd2u8lU/10HY0CgYBzqWCSqrVUmpZDrrbKPxnGNQEXkK7LU82ehy37D5y5z/Z6sbGo0HI0V/K0w4DlXxn8TqA84pYUhq1gA+NOWqF2EKHkrJcO3oehry+vuaTnKTHMmmEE3CU88FDlyPTb26nXQfMOuevhg9XPnouBkfejDbyEXldGVK5UWh4xEe179wKBgD6CVuCQ+xZihK/srSz4M9rIBpL/Vkvrcz1qLOemobTHkNALUU6oIwedKmtXADQ9qSPpzDa+/SK6LV4ercBr1xpKgNTLCRKvWfYlG8vcJFcA4FodNmZrK/0443S5HlkTkxhDoSuxi0BWJZeVQxu8XrccGQrjvH0Pi48iHP3JbCqZAoGBAOUIvrezFowg8ftSHj68h7138fnw3lS+C37GmQwv96ifl+WLD6RuBZNwauImutBS8LSrU+R4X86VB9yI89PXRUnwm/ZDdKxjtlIcWRbKdqcp6EyjU7NstHNc3CkpA7mEgVQeKJ2am2byCq8WQukYxGU1c5HQwJmveZkAc4hutKEW';;
        $aop->alipayrsaPublicKey='MIIBIjANBgkqhkiG9w0BAQEFAAOCAQ8AMIIBCgKCAQEAjHrWWgUm0nmDbxf/0zobPwRFWZaEpWPo1OqxAa5RhwgAfnvZn+AdFIUIXFEUjsV0PfoOrNSyFuM2zSusMtJWer1xX6rUDbhrRpnaxuibyCJUPWr4zZ4WChRGpgIYp3+7NJeRHkWM24cbkKvdPl4432kpmlh29A0armcKEke5PwzTaV+T+r2VduVGAIezHL0w0APdJFOgsqG+JnO+7rS08y86OOFTYZ2V3xJma5qg4E1UmtCmCQBqC9+MFKgrMzVeQqySw4I6WAFCfe7z5SoioeeFXCuFTekLC8XPmcTZH6wByWzF1nDBzlW2oEEUy5bGhj19d91LaCls5TOTEVJ7SwIDAQAB';
        $aop->apiVersion = '1.0';
        $aop->signType = 'RSA2';
        $aop->postCharset='UTF-8';
        $aop->format='json';
        $request = new \AlipayTradeQueryRequest ();
        $order_id = $reques->input('order_id');
        // $order_id =27;
        $res = $this->selectorder($order_id); 
        $out_trade_no = $res['no']; 
        $total_amount = $res['total_amount']; 
        $payment_method = "支付宝"; 

        $request->setBizContent("{" .
        "\"out_trade_no\":\"".$out_trade_no."\"" .
        "  }");
        $result = $aop->execute ($request); 
        $responseNode = str_replace(".", "_", $request->getApiMethodName()) . "_response";

        $resultCode = $result->$responseNode->code;
        $data['out_trade_no'] = $out_trade_no;
        $data['total_amount'] = $total_amount;
        $data['payment_method'] = $payment_method;
          if(!empty($resultCode)&&$resultCode == 10000){
            $res = array(
                "errNo" => "success",
                "errMsg" => "成功", 
                "data" => $data
            );
            $this->_response($res);
        }
        $res = array(
            "errNo" => '8002',
            "errMsg" => "失败", 
            "data" => $data
        );
        $this->_response($res);
    }
}

