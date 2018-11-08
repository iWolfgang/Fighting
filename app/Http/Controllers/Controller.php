<?php

namespace App\Http\Controllers;

use Illuminate\Foundation\Bus\DispatchesJobs;
use Illuminate\Routing\Controller as BaseController;
use Illuminate\Foundation\Validation\ValidatesRequests;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;

use Illuminate\Support\Facades\Redis;

class Controller extends BaseController
{
    use AuthorizesRequests, DispatchesJobs, ValidatesRequests;

    public $startTime;
    public $user_id;

    function __construct(){
        $startTime = microtime(true);
        $debug = config("app.api_debug");
// echo $_POST['token'];die;
        if(isset($_POST['token']) && empty($_POST['token']) == false){

            $check = $this->checkUserToken($_POST['token']);

            if($check == false){
                $res = array(
                    "errNo" => "0003",
                    "errMsg" => "用户登录状态失效"
                );
                $this->_response($res);
            }
        }
        
        if($debug == false){
            $sign = trim($_POST['sign']);
            unset($_POST['sign']);
            $sign = $this->checkSign($_POST, $sign);

            if($sign == false){
                $res = array(
                    "errNo" => '0001',
                    "errMsg" => '签名校验失败'
                );

                $this->_response($res);
            }
        }
        
    }
    /**
     * 统一出口 
     * Author JiaXu
     * Date 2018-04-04
     * Params [params]
     * @param  array  $res [description]
     */
    public function _response($res = array())
    {
        header('Content-type: application/json');
        echo json_encode($res);
        exit;
    }

    /**
     * 校验签名信息 
     * Author Raven
     * Date 2018-04-09
     * Params [params]
     * @param  array  $params [签名参数]
     * @param  string $sign   [签名字符串]
     */
    public function checkSign($params = array(), $sign = '')
    {
        $signStr = '';
        asort($params);
        foreach ($params as $key => $value) {
            $signStr .= $key . $value;
        }

        if(abs(time() - $params['timestamp']) > 300){
            //如果客户端请求时间与服务器时间相差 5分钟 拒绝请求
            return false;
        }
        $secret = config("app.api_secret");

        $signStr .= $secret;

        $serSign = md5($signStr);

        $check = $serSign == $sign;

        return $check;
    }

    /**
     * 校验用户token 
     * Author Amber
     * Date 2018-04-12
     * Params [params]
     * @param  string $user_token [用户token]
     */
    public function checkUserToken($user_token = '')
    {
       // echo $user_token;die;
        $tokenArr = explode("|", $user_token);

        $user_id = $tokenArr[0];
        $time_ago = $tokenArr[1];//以前生成的时间戳 
        $secret = $tokenArr[2];

        $key = sprintf("MYAPI_USER_TOKEN_%s", $user_id);

        $cacheSecret = Redis::get($key);
        
        //$time_now = $time_ago + 1209600;
        $time_now = time();

// echo $time_ago."以前";
        if($time_ago  + 1209600 < $time_now){//如果 token生成时间 + 两周的时间 小于 当前时间  就过期了
            
            return false;
        }

        if($secret != $cacheSecret){
             
            return false;
        }

        $this->user_id = $user_id;
        return true;
    }
}
