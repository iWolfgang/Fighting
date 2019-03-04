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
     * @param  [type] $id [接受的文章id]$ShipperCode
     */
    public function selectLog($OrderCode,$ShipperCode,$LogisticCode)
    {
        $logisticResult = $this->getOrderTracesByJson($OrderCode,$ShipperCode,$LogisticCode);
        echo $logisticResult;
    }
/**
 * Json方式 查询订单物流轨迹
 */
   function getOrderTracesByJson($OrderCode,$ShipperCode,$LogisticCode){

    $requestData= "{'OrderCode':'$OrderCode','ShipperCode':'$ShipperCode','LogisticCode':'$LogisticCode'}";

    $appkey = '294c2737-4e63-4e20-90e9-852d3cc5e1db';
    $datas['DataSign'] = encrypt($requestData,$appkey);
    $sign = urlencode(base64_encode(md5($requestData.$appkey)));
    $datas = array(
        'EBusinessID' => '1415349',
        'RequestType' => '1002',
        'RequestData' => urlencode($requestData) ,
        'DataType' => '2-json',
        'DataSign' => $sign,
    );
   

	$result=$this->sendPost('http://api.kdniao.com/Ebusiness/EbusinessOrderHandle.aspx', $datas);	
	
	//根据公司业务处理返回的信息......
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

    public function ReceiptList($user_id='')
    {
        $bool = DB::table('g_orders')
            ->select('id','no','total_amount','remark','paid_status','creatorder_at','expiration_at')
            ->where('user_id',$user_id)
            ->where('paid_status',"待收货")
            ->get();
        $objects = json_decode(json_encode($bool), true);//未支付的订单列表
        // dd($objects);die;
         $pay_items = collect([]);
                foreach ($objects as $key => $value) {
                   $arr = DB::table('g_order_items')
                    ->where('order_id',$value['id'])
                    ->get(); 
                    // $arrs = json_decode(json_encode($arr), true);
                    // print_r($arrs);die;
                     $pay_items->push($arr);
                }
                $pay_items = $pay_items->flatten();
                $pay_items = json_decode(json_encode($pay_items), true);
                $list = array();
                foreach ((array)$pay_items as $k => $v) {
                         $goods = DB::table('g_productSkus')
                            ->select('g_product.id','g_product.goods_thumb','g_product.goods_name','g_productSkus.title')
                            ->join('g_product','g_productSkus.product_id','=','g_product.id')
                            ->where('g_productSkus.id',$v['goods_id'])
                            ->first();
                            $list[$k]['goods'] = (array)$goods;
                            $list[$k]['amout'] = $v['amout'];
                            $list[$k]['price'] = $v['price'];
                            $list[$k]['total_amount'] = $v['price']*$v['amout'];
               
                 }
        // $CloseOrder = array();
        // foreach ($objects as $key => $value) {//关闭支付超时的订单
            
        //     else{//未支付订单的详细商品列表
               
                 // dd($list);die;
        //     }

        // }
       return $list;
    
}
    public function Receiptitem($user_id,$order_id,$goods_id)
    {
       $bool = DB::table('g_orders')
          ->select('g_order_items.id','g_order_items.order_id','no','g_orders.total_amount','g_orders.address','g_orders.creatorder_at','g_order_items.goods_id','g_order_items.amout','g_order_items.price')
          ->join('g_order_items','g_orders.id','=','g_order_items.order_id')
          ->where('g_orders.id',$order_id)
          ->get();
        $objects = json_decode(json_encode($bool), true);
        $goods_item = array();
        foreach ($objects as $key => $value) {
          $bool = DB::table('g_productSkus')
            ->select('g_productSkus.id','g_productSkus.sku_thumb','g_product.goods_name','g_productSkus.title','g_productSkus.pricenow')
            ->join('g_product','g_productSkus.product_id','=','g_product.id')
            ->where('g_productSkus.id',$value['goods_id'])
            ->first();
          $goods_item[$key] = json_decode(json_encode($bool), true);
          $goods_item[$key]['order_itemid'] = $value['id'];
          $goods_item[$key]['amout'] = $value['amout'];

        }
          $goods_item['order_id'] = $value['order_id'];
          $goods_item['no'] = $value['no'];
          $goods_item['address'] = $value['address'];
          $goods_item['total_amount'] = $value['total_amount'];
          $goods_item['creatorder_at'] = $value['creatorder_at'];
        return $goods_item;
 }



// ====================================支付宝面单
// 
public function faceLog($value='')
{
 
}
    function submitEOrder($requestData){
      $datas = array(
            'EBusinessID' => '1415349',
            'RequestType' => '1007',
            'RequestData' => urlencode($requestData) ,
            'DataType' => '2',
        );
        $datas['DataSign'] = encrypt($requestData, AppKey);
      $result=sendPost(ReqURL, $datas); 
      
      //根据公司业务处理返回的信息......
      
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