<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Http\Redirect;
// use alipay\aop\AopClient;
// use alipay\aop\request\AlipayTradeAppPayRequest;
use DB;
class PayPhoneController extends Controller
{
	public $partner_public_key  = "MIGfMA0GCSqGSIb3DQEBAQUAA4GNADCBiQKBgQCitD16CypwZILTpdJL8nPV9rVFHYf5UWa/URNX6469mbQLpWfjKM/VSWRXsNVGSM3itOO/KG2Pw4x5g9xjH6iaE4LlaidjBIPpifISSlnpbyi4HxQTZYgMPv/TuiWofUN5kcwg/KQAQxB2OwTOeFu2i3LhqSCDmv6koTvHW15/hQIDAQAB";
	public $alipay_public_key   = "MIGfMA0GCSqGSIb3DQEBAQUAA4GNADCBiQKBgQDIgHnOn7LLILlKETd6BFRJ0GqgS2Y3mn1wMQmyh9zEyWlz5p1zrahRahbXAfCfSqshSNfqOmAQzSHRVjCqjsAw1jyqrXaPdKBmr90DIpIxmIyKXv4GGAkPyJ/6FTFY99uhpiq0qadD/uSzQsefWo0aTvP/65zi3eof7TcZ32oWpwIDAQAB";
		//公用变量
	public $serverUrl = 'http://publicexprod.d5336aqcn.alipay.net/chat/multimedia.do';//'http://publicexprod.d5336aqcn.alipay.net/chat/multimedia.do';//'http://i.com/works/photo-sdk/_data/1.jpg';//"http://i.com/works/photo-sdk/_data/publicexprod.php";//"http://publicexprod.d5336aqcn.alipay.net/chat/multimedia.do";
	public $appId = "2013121100055554";

	public $partner_private_key = 'MIICdwIBADANBgkqhkiG9w0BAQEFAASCAmEwggJdAgEAAoGBAKK0PXoLKnBkgtOl0kvyc9X2tUUdh/lRZr9RE1frjr2ZtAulZ+Moz9VJZFew1UZIzeK0478obY/DjHmD3GMfqJoTguVqJ2MEg+mJ8hJKWelvKLgfFBNliAw+/9O6Jah9Q3mRzCD8pABDEHY7BM54W7aLcuGpIIOa/qShO8dbXn+FAgMBAAECgYA8+nQ380taiDEIBZPFZv7G6AmT97doV3u8pDQttVjv8lUqMDm5RyhtdW4n91xXVR3ko4rfr9UwFkflmufUNp9HU9bHIVQS+HWLsPv9GypdTSNNp+nDn4JExUtAakJxZmGhCu/WjHIUzCoBCn6viernVC2L37NL1N4zrR73lSCk2QJBAPb/UOmtSx+PnA/mimqnFMMP3SX6cQmnynz9+63JlLjXD8rowRD2Z03U41Qfy+RED3yANZXCrE1V6vghYVmASYsCQQCoomZpeNxAKuUJZp+VaWi4WQeMW1KCK3aljaKLMZ57yb5Bsu+P3odyBk1AvYIPvdajAJiiikRdIDmi58dqfN0vAkEAjFX8LwjbCg+aaB5gvsA3t6ynxhBJcWb4UZQtD0zdRzhKLMuaBn05rKssjnuSaRuSgPaHe5OkOjx6yIiOuz98iQJAXIDpSMYhm5lsFiITPDScWzOLLnUR55HL/biaB1zqoODj2so7G2JoTiYiznamF9h9GuFC2TablbINq80U2NcxxQJBAMhw06Ha/U7qTjtAmr2qAuWSWvHU4ANu2h0RxYlKTpmWgO0f47jCOQhdC3T/RK7f38c7q8uPyi35eZ7S1e/PznY=';

	public $format = "json";
	public $charset = "GBK";



	function __construct(){

	}

	public function load() {
		$alipayClient = new AlipayMobilePublicMultiMediaClient(
			$this -> serverUrl,
			$this -> appId,
			$this -> partner_private_key,
			$this -> format,
			$this -> charset
		);
		$response = null;
		$outputStream = null;
		$request = $alipayClient -> getContents() ;

		//200
		//echo( '状态码：'. $request -> getCode() .', ');
		//echo '<hr /><br /><br /><br />';

		$fileType = $request -> getType();
		//echo( '类型：'. $fileType .', ');
		if( $fileType == 'text/plain'){
			//出错，返回 json
			echo $request -> getBody();

		}else{

			$type = $request -> getFileSuffix( $fileType );

			//echo $this -> getParams();
			//exit();

			//返回 文件流
			header("Content-type: ". $fileType ); //类型


			header("Accept-Ranges: bytes");//告诉客户端浏览器返回的文件大小是按照字节进行计算的
			header("Accept-Length: ". $request -> getContentLength() );//文件大小
			header("Content-Length: ". $request -> getContentLength() );//文件大小
			header('Content-Disposition: attachment; filename="'. time() .'.'. $type .'"'); //文件名
			echo $request -> getBody() ;
			exit ( ) ;
		}

		//echo( '内容： , '. $request -> getContentLength()  );

		//echo '<hr /><br /><br /><br />';
		//echo  '参数：<pre>';

		//echo ($request -> getParams());

		//echo '</pre>' ;
	}
}





//  测试
$test1 = new TestImage();
$test1 -> load();


	// public function index(Request $request)
	// {
	// 	// echo 1;die;
	// 	// $debug = config("AopClient");
	// 	// dd($debug);die;
	// 	// // $AopClient = base_path('vendor/alipay/aop/AopClient');

	// 	// // vendor('alipay.aop.request.AlipayTradeAppPayRequest');
	// 	// $aop = new config("AopClient");
	// 	// dd($aop);die;
	// 	// $aop->gatewayUrl = "https://openapi.alipay.com/gateway.do"; //支付宝网关
	// 	// $aop->appId = "2016091700532476"; 
	// 	// $aop->rsaPrivateKey = "商户私钥，您的原始格式RSA私钥()";
	// 	// $aop->alipayrsaPublicKey = "支付宝公钥";
	// 	// $aop->apiVersion = '1.0';
	// 	// $aop->signType = "签名方式，如 RSA2 ";
	// 	// $aop->postCharset = 'UTF-8';
	// 	// $aop->format = "json";
	// 	// //实例化具体API对应的request类,类名称和接口名称对应,当前调用接口名称：alipay.trade.app.pay
	// 	// $appRequest = new \AlipayTradeAppPayRequest();
	// 	// //SDK已经封装掉了公共参数，这里只需要传入业务参数
	// 	// $bizcontent = json_encode([
	// 	//     'body' => '余额充值',  //订单描述
	// 	//     'subject' => '充值',   //订单标题
	// 	//     'timeout_express' => '30m',
	// 	//     'out_trade_no' => ‘20170125test01’, //商户网站唯一订单号
	// 	//     'total_amount' => '0.01', //订单总金额
	// 	//     'product_code' => 'QUICK_MSECURITY_PAY', //固定值
	// 	// ]);
	// 	// $appRequest->setNotifyUrl($url);  //设置异步通知地址
	// 	// $appRequest->setBizContent($bizcontent);
	// 	// //这里和普通的接口调用不同，使用的是sdkExecute
	// 	// $response = $aop->sdkExecute($appRequest);
	// 	// //htmlspecialchars是为了输出到页面时防止被浏览器将关键参数html转义，实际打印到日志以及http传输不会有这个问题
	// 	// echo htmlspecialchars($response);//就是orderString 可以直接给客户端请求，无需再做处理。
	// 	// // 如果最后有问题可以尝试把htmlspecialchars方法去掉，直接返回$response

	// }




}
