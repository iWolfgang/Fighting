<?php
namespace App\Http\Controllers;

use Illuminate\Support\Facades\Redis;
use Illuminate\Http\Request;
use Illuminate\Http\Redirect;
use DB;
use app\Packages\alipay;
use App\PayModel;
Class PayPhoneController extends Controller
{
    public function alicof()
    {
        $aop = new \AopClient();
        $aop->gatewayUrl = "https://openapi.alipay.com/gateway.do";
        $aop->appId = "2019011062842792";
        $aop->rsaPrivateKey = 'MIIEowIBAAKCAQEA0eaUU8GLz93lCunIq+1mECFpABRPFE32+4Gri2PLRGI9ndbrQLpsQhqZNfxnJ1/cvI2kLNDs3/ZUkB/yi4OjRtHoCeRnzVZYnnsszweCxckPFr4BauSgsTGygpVrVbo2dIzBsBW2EWBHVi/ohWijEDs7kMLpsIFKJ4ug1qq5e5DGNHrv+cW/k+Goor6pWPoj6R8sjhfPuZYrN+JzgFnstvEBGL7yR2MrgKj84vzbgVLaX+CqRGe87fI9JquPhuNGB+cWoxuyqK2YSMzmXDTP+Tym/kDZytRtSODmZli6Ksny+MCMOr1qpuMPQ6bc0tTkKes6oFdO4fryvkRWWU/x3wIDAQABAoIBABN8WGC+IwCVFOJCecKcM1FkCJ9dQ0obQsZub0JtbT1X8Whpv0UvCUXJuldsqxbYq2FFtOwEKTlRYOBQVu/ktI+qhOQGNCy3y1pLDQnbJKS/2Yq+8Nq/hrtsZaoBvQkkVFHVj1WNbm2GhpjVsbxQznJ/TTRPI+qi1gN9ztye1MFHsDMsf4E5gWOnuiBoLZjDXRvit05Ow2XUbjHzqhtudErLFc8ZuokUuZC/Iz1w8VHVnVPK5v3kj0jcPwnDhkK8kRk3sa8nXLGQKO8H/eKSMPtFuXtOmNFU6+aIt7F3S6YzSq7Tz4Fta9x18My/WlmWqmRoGKFUUW+giDcJ3JYy8uECgYEA+ptyA6IfBZxW493clEyUS1DulAKpHuflI6FYy09yS/Vn7kL7++vhzvIruq86f+tpNOT/QnArH4zpE+z3zP9dGPoeDphtlS9tEqb/2M7BXgpZx9qJZHcERDJBecPp3bFabMo7rKiEMorh/eMZAtSzpCFX2Fi62WWcmD93DSDNJBsCgYEA1mrjchOchJBSJDIFAacz8ou+ArSU00w6icKOiRd3YGixnGu+2qLmZ5po5MP+VBAmnCnp30vBh8aL8UMFDYAq9W+1YqQil6DE4I27ra0XD78j+duCGUPAHoH6jglQLov+jP5HAVl2xDuSGSy1Ri02y2741NbNqFFd2u8lU/10HY0CgYBzqWCSqrVUmpZDrrbKPxnGNQEXkK7LU82ehy37D5y5z/Z6sbGo0HI0V/K0w4DlXxn8TqA84pYUhq1gA+NOWqF2EKHkrJcO3oehry+vuaTnKTHMmmEE3CU88FDlyPTb26nXQfMOuevhg9XPnouBkfejDbyEXldGVK5UWh4xEe179wKBgD6CVuCQ+xZihK/srSz4M9rIBpL/Vkvrcz1qLOemobTHkNALUU6oIwedKmtXADQ9qSPpzDa+/SK6LV4ercBr1xpKgNTLCRKvWfYlG8vcJFcA4FodNmZrK/0443S5HlkTkxhDoSuxi0BWJZeVQxu8XrccGQrjvH0Pi48iHP3JbCqZAoGBAOUIvrezFowg8ftSHj68h7138fnw3lS+C37GmQwv96ifl+WLD6RuBZNwauImutBS8LSrU+R4X86VB9yI89PXRUnwm/ZDdKxjtlIcWRbKdqcp6EyjU7NstHNc3CkpA7mEgVQeKJ2am2byCq8WQukYxGU1c5HQwJmveZkAc4hutKEW';
        $aop->format = "json";
        $aop->charset = "UTF-8";
        $aop->signType = "RSA2";
        $aop->alipayrsaPublicKey = 'MIIBIjANBgkqhkiG9w0BAQEFAAOCAQ8AMIIBCgKCAQEAjHrWWgUm0nmDbxf/0zobPwRFWZaEpWPo1OqxAa5RhwgAfnvZn+AdFIUIXFEUjsV0PfoOrNSyFuM2zSusMtJWer1xX6rUDbhrRpnaxuibyCJUPWr4zZ4WChRGpgIYp3+7NJeRHkWM24cbkKvdPl4432kpmlh29A0armcKEke5PwzTaV+T+r2VduVGAIezHL0w0APdJFOgsqG+JnO+7rS08y86OOFTYZ2V3xJma5qg4E1UmtCmCQBqC9+MFKgrMzVeQqySw4I6WAFCfe7z5SoioeeFXCuFTekLC8XPmcTZH6wByWzF1nDBzlW2oEEUy5bGhj19d91LaCls5TOTEVJ7SwIDAQAB';

        return $aop;
    }
	public function index(Request $request)
	{
		$aop = $this->alicof();
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
		
		$requests->setBizContent($bizcontent);
		$response = $aop->sdkExecute($requests);
		$res = array(
                "data" => $response
            );
		return $res;

	}
/**
 * 订单相关信息 
 * Author Amber
 * Date 2019-02-19
 * Params [params]
 * @param  string $order_id [description]
 * @return [type]           [description]
 */
	public function selectorder($order_id='')
	{
		$ret = DB::table('g_orders')
		 	->select('total_amount','no','payment_method','out_trade_no','payment_no')
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
        
        $this->alipaynotify($Comment);        
	}

    public function alipaynotify($Comment)
    {
        $out_trade_no = $Comment['out_trade_no'];
        $trade_no =  $Comment['trade_no'];  
        $order_id =$Comment['order_id'];
        $paid_at =$Comment['gmt_payment'];
  
        $data = "i'm 待发货";     
        $namafile = "alipa.txt";    
        $fh = fopen($namafile,"w");      
        fwrite($fh,$data);     // 生成日志的方法
        fclose($fh); 
        $ret = DB::table('g_orders')
        ->where('id', $order_id)
        ->update(['out_trade_no' => $out_trade_no,'payment_no' => $trade_no,'payment_method' => '支付宝','paid_at' => $paid_at,'paid_status' => '已支付','ship_status' => '待发货']);

        if($ret == FALSE){
            echo fail;
        }else{
            echo  Success;
        }
    }

/**
 * 支付宝退款 
 * Author Amber
 * Date 2019-02-19
 * Params [params]
 * @param  REQUEST $requests [description]
 * @return [type]            [description]
 */
    public function returnmoney(REQUEST $requests)
    {
        $aop = $this->alicof();
        $order_id = $requests->input('order_id');
        $data = $this->selectorder($order_id);
        $out_trade_no = $data['out_trade_no'];
        $trade_no =  $data['payment_no']; 
        $total_amount =  $data['total_amount']; 
        $request = new \AlipayTradeRefundRequest ();
        $request->setBizContent("{" .
        "\"out_trade_no\":\"".$out_trade_no."\"," .
        "\"trade_no\":\"".$trade_no."\"," .
        "\"refund_amount\":\"".$total_amount."\"" .
        "  }");
        $result = $aop->execute ($request); 
        $results = json_decode(json_encode($result), true);
        $res = $this->upreturn($results,$order_id);
        if($res == FALSE){
            return "退款失败";
        }
        $responseNode = str_replace(".", "_", $request->getApiMethodName()) . "_response";

        $resultCode = $result->$responseNode->code;
        if(!empty($resultCode)&&$resultCode == 10000){
            return "Success";
        } else {
            return "fail";
        }
    }
/**
 * 退款修改数据库状态 
 * Author Amber
 * Date 2019-02-19
 * Params [params]
 * @param  [type] $reques   [description]
 * @param  [type] $order_id [description]
 * @return [type]           [description]
 */
    public function upreturn($reques,$order_id)
    {
        $data['buyer_logon_id'] = $reques['alipay_trade_refund_response']['buyer_logon_id'];
        $data['buyer_user_id'] =  $reques['alipay_trade_refund_response']['buyer_user_id'];  
        $data['fund_change'] =  $reques['alipay_trade_refund_response']['fund_change'];  
        $data['gmt_refund_pay'] =  $reques['alipay_trade_refund_response']['gmt_refund_pay'];  
        $data['refund_fee'] =  $reques['alipay_trade_refund_response']['refund_fee'];  
        $refund_no =  $reques['alipay_trade_refund_response']['trade_no'];  
        $order_id =  $order_id;  

        $ret = DB::table('g_order_refund')
            ->where('order_id',$order_id)
            ->update($data);//在退款详情表添加信息退款失败on
        if($ret == FALSE){
            return FALSE;
        }
        $res = DB::table('g_orders')
        ->where('id', $order_id)
        ->first();
        if(empty($res)){
             $res = DB::table('g_orders')
            ->where('id', $order_id)
            ->update(['out_trade_no' => $out_trade_no,'payment_no' => $trade_no,'payment_method' => '支付宝','paid_at' => $paid_at,'paid_status' => '已支付','ship_status' => '待发货']);
        }else{
            // $data = "i'm ap";   //       
            // $namafile = "ali.txt";     //       
            // $fh = fopen($namafile,"w");      
            // fwrite($fh,$data);     // 生成日志的方法
            // fclose($fh); 
            $res =  DB::table('g_orders')//在订单表修改信息退款失败one
            ->where('id',$order_id)
            ->update(['refund_status'=>'退款成功','ship_status'=>'退款','refund_no'=>$refund_no]);
        }
     
        if($res == FALSE){
            return FALSE;
        }
        $small_order = DB::table('g_order_items')
             ->select()
             ->where('order_id',$order_id)
             ->get();
             $small = json_decode(json_encode($small_order), true);
             foreach ($small as $k => $v) {
                 $del_item =  DB::update('update  g_productSkus set stock = stock + '.$v['amout'].' where id = '.$v['goods_id'].'');
             }
    }
/**
 * 申请退款 
 * Author Amber
 * Date 2019-01-29
 * Params [params]
 * @param  string $value [description]
 * @return [type]        [description]
 */
    public function apply_refund(Request $request)
    {
        $data['user_id'] = $request->input("user_id"); 
        $data['order_id'] = $request->input("order_id"); 
        $data['refund_reason'] = $request->input("refund_reason");
        $data['refund_msg'] = $request->input("refund_msg");
        $data['refund_imgs'] = $request->file("refund_imgs"); 
        
        $PayModel = new PayModel();
        $res = $PayModel->apply_refund($data);
        if($res){
            $res = array(
                "errNo" => "success",
                "errMsg" => "提交成功"
            );
            $this->_response($res);
        }
        $res = array(
            "errNo" => '9004',
            "errMsg" => "提交失败"
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
        $aop = $this->alicof();
        $request = new \AlipayTradeQueryRequest ();
        $order_id = $reques->input('order_id');
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
        $data['order_id'] = $order_id;
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

