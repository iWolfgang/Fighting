<?php
namespace App;

use Illuminate\Database\Eloquent\Model;

use DB;

class LogisticesModel extends Model{

    public $_tabName = '';

// defined('EBusinessID') or define('EBusinessID', '请到快递鸟官网申请http://kdniao.com/reg');
// //电商加密私钥，快递鸟提供，注意保管，不要泄漏
// defined('AppKey') or define('AppKey', '请到快递鸟官网申请http://kdniao.com/reg');
// //请求url
// defined('ReqURL') or define('ReqURL', 'http://api.kdniao.com/Ebusiness/EbusinessOrderHandle.aspx');
    /**
     * 查看物流
     * Author Liuran
     * Date 2018-12-13
     * @param  [type] $id [接受的文章id]
     */
    public function selectLog()
    {
        $logisticResult = $this->getOrderTracesByJson();
        echo $logisticResult;
    }


    /**
 * Json方式 查询订单物流轨迹
 */
   function getOrderTracesByJson(){
        $requestData= "{'OrderCode':'','ShipperCode':'ZTO','LogisticCode':'75114124635543'}";
        $appkey = '294c2737-4e63-4e20-90e9-852d3cc5e1db';
        $sign = urlencode(base64_encode(md5($requestData.$appkey)));

    $datas = array(
        'EBusinessID' => '1415349',//14153494d3964ec-705d-4702-8783-45acaf1cfb63
        'RequestType' => '1002',
        'RequestData' => urlencode($requestData) ,
        'DataType' => '2-json',
        'DataSign' => $sign,
    );
    $datas['DataSign'] = encrypt($requestData,$appkey);//

	$result=$this->sendPost('http://api.kdniao.com/Ebusiness/EbusinessOrderHandle.aspx', $datas);	
	
	//根据公司业务处理返回的信息......
	// dd($result);die;
	return $result;
}

/**
 *  post提交数据 
 * @param  string $url 请求Url
 * @param  array $datas 提交的数据 
 * @return url响应返回的html
 */
    function sendPost($url, $datas) {
       
    $temps = array();	
    foreach ($datas as $key => $value) {
        $temps[] = sprintf('%s=%s', $key, $value);		
    }	
    print_r($url);die;
    $post_data = implode('&', $temps);
    $url_info = parse_url($url);
	if(empty($url_info['port']))
	{
		$url_info['port']=80;	
	}
    $httpheader = "POST " . $url_info['path'] . " HTTP/1.0\r\n";
    $httpheader.= "Host:" . $url_info['host'] . "\r\n";
    $httpheader.= "Content-Type:application/x-www-form-urlencoded\r\n";
    $httpheader.= "Content-Length:" . strlen($post_data) . "\r\n";
    $httpheader.= "Connection:close\r\n\r\n";
    $httpheader.= $post_data;
    $fd = fsockopen($url_info['host'], $url_info['port']);
    fwrite($fd, $httpheader);
    $gets = "";
	$headerFlag = true;
	while (!feof($fd)) {
		if (($header = @fgets($fd)) && ($header == "\r\n" || $header == "\n")) {
			break;
		}
	}
    while (!feof($fd)) {
		$gets.= fread($fd, 128);
    }
    fclose($fd);  
    
    return $gets;
}

/**
 * 电商Sign签名生成
 * @param data 内容   
 * @param appkey Appkey
 * @return DataSign签名
 */
    function encrypt($data, $appkey) {
        return urlencode(base64_encode(md5($data.$appkey)));
    }
}