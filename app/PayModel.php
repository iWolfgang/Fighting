<?php
namespace App;

use Illuminate\Database\Eloquent\Model;

use DB;

class PayModel extends Model{

    public $_tabName = '';


    /**
     * 文章收藏
     * Author Liuran
     * Date 2018-04-17
     * @param  [type] $id [接受的文章id]
     */
    public function (alipay)
    {
			$aop = new AopClient;
			$aop->gatewayUrl = "https://openapi.alipay.com/gateway.do";
			$aop->appId = "2017021705722510";
			$aop->rsaPrivateKey = 'MIIEogIBAAKCAQEAzLYj+7Mz0GAGLWpWCPCh9QjhwxUUZLisbc122Rv4T/7+rgS2hOG4ISstQwmxye6av+BN1/Z+M0POrGFr68y4pSEIJuki3WL02sU6GL8emsxDfktToCNghpEmRzbcPrWqvouSLNGzktre8KTzwZ73ODS1RN0ygNEynwpGPn3jM92mev9Qa3rEAtObNpgJ91k0uFqCd7ZpJfm1gY7gAPyLKAUy9npPWhQTKIlQSudqR71CKvo06hp/NU4K2fB3RbESitk5UeZ26/Sqp7cHNYZWR6pM+jP0EFXnptVA3cXn0sy0/4tNB7mmUlaI7KKpNKrS8hZiZss29lkzExKRsueudwIDAQABAoIBAASjd+tDI0BsfJdY2nw3X6QawGrYNm8V1CpkxnWi8Zx2bbTpvG0EZFS0EsF7HcEym+UPIxRqrG9i8PLs/jIIoIJ1Xibrj3Ouw1eVsnxPEFV3nWvmLw3o304NSe3BK3psSWMp7HUBh20jcnvHcRKJFJb8csMNtBBBLUF3TSIrXhOVvsfjQiHlr8YVuX22wY1C3hlu67bnMWH3ikvTI1rhlxJWTsTjCTPmjwxONZTPmwaRYz4F3CfMi/P1Fk3Yr+KNQ24TceQinVfCuPf+Um6OerDnj6Q8ub6qUdXxkQ+lB4w6mbOWcTMExsV/oVswtdm8luCnkXKW5I/tQpy567q9MMkCgYEA6+/F4JxsXcyDFbqRspVdkH8Fh8sPZp/WRVnF/pdd24rwTDc+mthu3awarHE6RlYKQobUPL7lQKpmvJtMuHesNczokncYaDvU1lipKEaQ60Yiny7EF/YvKpbJ0WgS8OIK5a+fVPMHBl/qDwDatPzkz2Ol6MqqvzKOaZE1tce486UCgYEA3h6cjs/zC78bU2pl+OjkeqA+40J5y1TZF6f36YL4gnW2EXdhRVNQOq5hjtCuB9CHap/+SfDRpTNSfM01Sg4fad8Pg+6K6j+24993pTQaRFMG+zuZlNZkBfBBZJAPKQ/DB06Ekkp84Adi2PBslHbK6EbSB84PuEUdnTSKNJYJDusCgYAgxXI3y6JluPVwPDTmmEahvnL3NKZT+9mYUmrk/QNlbjwIahgBDBUCJ1ihIS0V/fd43B94vI89Vy3j/rI+YSkDZA3d4jr6p2zdxPziAkM42soOUEGejmxovv8TXiBbYxpeYvF/rC4KnaH2KYk3YHUrtUyjNBtZaV1MnoXWVQKEnQKBgFiyLJ4yl8EnDQhgQlwE08fL2ZDyaKQzO4Vgw3Rxoj6mNo/+9c6zGCR2oepflj70nD6NqQNpsK2hMt03LIJn5U9njVCHnt0vmB6ii6piheKmrPEBubBfdA2TBOndRNCGxMBUMEgiin8DNAug5hra3Oen8BLBzDEvGEZARrf8+Z0FAoGAH53aHU6NbjQcP60l5IEz6UY4D2xfabh9nwzJ1LHpCywajM8EpuOfrhflnslGvvqWgL0KIikaLjJNfs/sO6Yfsso1FDCGIlYtzfg+y0dIWRZRDzntkLAK+qR2b33Wi/TbPjPbL92hDlqzQTjiwo/UKIZoHxOrdEt2s7ecNffTly0=';
			$aop->format = "json";
			$aop->charset = "UTF-8";
			$aop->signType = "RSA2";
			$aop->alipayrsaPublicKey = 'MIIBIjANBgkqhkiG9w0BAQEFAAOCAQ8AMIIBCgKCAQEAzLYj+7Mz0GAGLWpWCPCh9QjhwxUUZLisbc122Rv4T/7+rgS2hOG4ISstQwmxye6av+BN1/Z+M0POrGFr68y4pSEIJuki3WL02sU6GL8emsxDfktToCNghpEmRzbcPrWqvouSLNGzktre8KTzwZ73ODS1RN0ygNEynwpGPn3jM92mev9Qa3rEAtObNpgJ91k0uFqCd7ZpJfm1gY7gAPyLKAUy9npPWhQTKIlQSudqR71CKvo06hp/NU4K2fB3RbESitk5UeZ26/Sqp7cHNYZWR6pM+jP0EFXnptVA3cXn0sy0/4tNB7mmUlaI7KKpNKrS8hZiZss29lkzExKRsueudwIDAQAB';
			//实例化具体API对应的request类,类名称和接口名称对应,当前调用接口名称：alipay.trade.app.pay
			$request = new AlipayTradeAppPayRequest();
			//SDK已经封装掉了公共参数，这里只需要传入业务参数
			$bizcontent = "{\"body\":\"我是测试数据\"," 
			                . "\"subject\": \"App支付测试\","
			                . "\"out_trade_no\": \"20170125test01\","
			                . "\"timeout_express\": \"30m\"," 
			                . "\"total_amount\": \"0.01\","
			                . "\"product_code\":\"QUICK_MSECURITY_PAY\""
			                . "}";
			$request->setNotifyUrl("https://mithrilgaming.com/");//商户外网可以访问的异步地址
			$request->setBizContent($bizcontent);
			//这里和普通的接口调用不同，使用的是sdkExecute
			$response = $aop->sdkExecute($request);
			//htmlspecialchars是为了输出到页面时防止被浏览器将关键参数html转义，实际打印到日志以及http传输不会有这个问题
			echo htmlspecialchars($response);//就是orderString 可以直接给客户端请求，无需再做处理。
    }
}