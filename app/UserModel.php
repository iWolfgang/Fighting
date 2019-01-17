<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use App\Services\OSS;
use DB;
use Illuminate\Support\Facades\Redis;
//Call to undefined method Illuminate\Database\MySqlConnection::()
use Illuminate\Database\MySqlConnection\paginate;

class UserModel extends Model
{
    public $_tabName = 't_user_info';

    /**
     * 用户注册 
     * Author JiaXu
     * Date 2018-04-07
     * Params [params]
     * @param  string  $user_mobile   [手机号码]
     * @param  string  $user_passwd   [登录密码]
     * @param  integer $sms_code      [短信验证码]
     * @param  string  $device_id     [注册设备id]
     * @param  string  $user_platform [注册平台]$device_id = '', $user_platform = '' $user_passwd = '',
     */
    public function regist($user_mobile = '', $sms_code = 0 )
    {

        $checkSmsCode = $this->checkSmsCode($user_mobile, $sms_code);
        if($checkSmsCode == FALSE){
            $res = array(
                "errNo" => "1002",
                "errMsg" => "短信验证码错误"
            );

            return $res;
        }

        $isRegist = $this->checkMobileIsRegist($user_mobile);//检查是否登陆过
        if($isRegist){
            $userId = $this->getUserInfoByMobile($user_mobile);
        }else{
            $add = $this->addUserInfoByMobile($user_mobile);
            $userId = $this->getUserInfoByMobile($user_mobile);
        }
        // if($isRegist){
        //     $res = array(
        //         "errNo" => "1003",
        //         "errMsg" => "该手机号码已被注册"
        //     );

        //     return $res;
        // }

        // if($add == false){
        //     $res = array(
        //         "errNo" => "1003",
        //         "errMsg" => "用户登陆失败"
        //     );

        //     return $res;
        // }
        if($userId == FALSE){
            $res = array(
                "errNo" => "1004",
                "errMsg" => "用户登录失败"
            );

            return $res;
        }

        $userid = $userId['id'];
        if(isset($userId['errNo'])){
            return $userId;
        }


        $userToken = $this->createUserToken($userid);
        // dump($userToken);die;
        $res = array(
            "token" => $userToken,
            "user_id" => $userid
        );
        return $res;
    }
    /**
     * 用户登录 
     * Author Liuran
     * Date 2018-04-07
     * Params [params]
     * @param  string  $user_mobile [手机号]
     * @param  integer $login_type  [登陆类型 1-短信登陆 2-密码登录]
     * @param  string  $sms_code    [短信验证码]
     * @param  string  $user_passwd [用户密码]
     */
    public function login($user_mobile = '', $login_type = 0, $sms_code = '', $user_passwd = '')
    {
        $userId = 0;

        $isRegist = $this->checkMobileIsRegist($user_mobile);

        if($isRegist == false){
            $res = array(
                "errNo" => "1003",
                "errMsg" => "该手机号码尚未注册"
            );

            return $res;
        }

        if($login_type == 1){
            // echo 1;
            //  $res = $this->getUserInfoByMobile($user_mobile);
            $userId = $this->getUserIdBySmsCode($user_mobile, $sms_code);
        }

        if($login_type == 2){
            // echo 2;die;
            $userId = $this->getUserIdByUserPasswd($user_mobile, $user_passwd);
        }

        if($userId == FALSE){
            $res = array(
                "errNo" => "1004",
                "errMsg" => "用户登录失败"
            );

            return $res;
        }

       $userid = $userId['id'];
        if(isset($userId['errNo'])){
            return $userId;
        }


        $userToken = $this->createUserToken($userid);
        // dump($userToken);die;
        $res = array(
            "token" => $userToken,
            "user_id" => $userid
        );
        return $res;
    }

    /**
     * 创建用户token 
     * Author Amber
     * Date 2018-04-11
     * Params [params]
     * @param  integer $user_id [用户id]
     */
    public function createUserToken($user_id = 0)
    {
        // echo 1;die;
        $time = time();
        $secret = md5($user_id . $time . rand(1111,9999));
        // echo $secret;die;
        $token = sprintf("%s|%s|%s", $user_id, $time, $secret);

        $key = sprintf("MYAPI_USER_TOKEN_%s", $user_id);

        $set = Redis::set($key, $secret);

        return $token;
    }

    /**
     * 短信验证码登陆
     * Author Liuran
     * Date 2018-04-07
     * Params [params]
     * @param  string $user_mobile [用户手机号]
     * @param  string $sms_code    [短信验证码]
     */
    public function getUserIdBySmsCode($user_mobile = '', $sms_code = 0)
    {
       $checkSmsCode = $this->checkSmsCode($user_mobile, $sms_code);
       if($checkSmsCode == FALSE){
            $res = array(
                "errNo" => "1002",
                "errMsg" => "短信验证码错误"
            );

            return $res;
        }
        $res = $this->getUserInfoByMobile($user_mobile);
        return $res;
    }

    /**
     * 通过密码获取用户id 
     * Author Liuran
     * Date 2018-04-07
     * Params [params]
     * @param  string $user_mobile [手机号码]
     * @param  string $user_passwd [登陆密码]
     */
    public function getUserIdByUserPasswd($user_mobile = '', $user_passwd = '')
    {
        $userInfo = $this->getUserInfoByMobile($user_mobile);
    //    dump($userInfo);die;
        if($userInfo == false){
            $res = array(
                "errNo" => "1005",
                "errMsg" => "用户信息获取失败"
            );

            return $res;
        }
        if($userInfo['user_passwd'] != $this->createPasswd($user_passwd)){
            $res = array(
                "errNo" => "1006",
                "errMsg" => "密码有误，请重新输入"
            );
            return $res;
        }
        return $userInfo;
    }


    /**
     * 通过手机号获取用户信息 
     * Author Liuran
     * Date 2018-04-07
     * Params [params]
     * @param  string $user_mobile [手机号码]
     */
    public function getUserInfoByMobile($user_mobile = '')
    {
        $userInfo = DB::table($this->_tabName)
            ->where('user_mobile', $user_mobile)
            ->first();
        // dump($userInfo);die;
        return empty($userInfo) ? false : get_object_vars($userInfo);
    }

    /**
     * 通过手机号获取用户Id 
     * Author Liuran
     * Date 2018-04-07
     * Params [params]
     * @param  string $user_mobile [手机号码]
     */
    public function getUserIdByUserMobile($user_mobile = '')
    {
        $userId = DB::table($this->_tabName)
            ->where('user_mobile', $user_mobile)
            ->pluck('id')
            ->first();

        return empty($userId) ? false : $userId;
    }
    /**
     * 校验短信验证码 
     * Author JiaXu
     * Date 2018-04-07
     * Params [params]
     * @param  string  $user_mobile [手机号码]
     * @param  integer $sms_code    [短信验证码]
     */
    public function checkSmsCode($user_mobile = '', $sms_code = '')
    {
        // echo $user_mobile."............".$sms_code;die;
        // echo $sms_code;die;
        $SmsCodeModel = new SmsCodeModel();
        
        return $SmsCodeModel->chenckCode($user_mobile, $sms_code);
    }

    /**
     * 检测手机号码是否注册 
     * Author JiaXu
     * Date 2018-04-07
     * Params [params]
     * @param  string $mobile [手机号码]
     */
    public function checkMobileIsRegist($mobile = '')
    {
        $count = DB::table($this->_tabName)
            ->where("user_mobile", $mobile)
            ->count();

        return $count > 0 ? true : false;
    }

    /**
     * 添加手机登录用户 
     * Author JiaXu
     * Date 2018-04-07
     * Params [params]
     * @param string $user_mobile   [手机号码]
     * @param string $user_passwd   [登录密码]
     * @param string $device_id     [注册设备id]
     * @param string $user_platform [用户注册平台], $user_passwd = '', $device_id = '', $user_platform = ''
     */
    public function addUserInfoByMobile($user_mobile = '')
    {
        $data = array();
        $data['user_mobile'] = $user_mobile;
       // $data['user_passwd'] = $this->createPasswd($user_passwd);
        // $data['user_type'] = 1; //用户类型 1-手机号码 2-微信 3-QQ
        // $data['device_id'] = $device_id;
        // $data['user_platform'] = $user_platform;
        $data['create_time'] = time();
        $add = DB::table($this->_tabName)
            ->insertGetId($data);
        $res = array();
        $res['head_portrait'] = 'http://mithril-capsule.oss-cn-beijing.aliyuncs.com/%E5%A4%B4%E5%83%8F.jpg';
        $res['user_name'] = '秘银'.rand(1000,9999).'用户';
        $res['user_id'] = $add;
        $res['sex'] = '男';
        $res['email'] = '请绑定邮箱';
        $res = DB::table('t_user_infos')
            ->insert($res);
        return $res;
        // echo $res;die;
    }

    /**
     * 创建密码 
     * Author JiaXu
     * Date 2018-04-07
     * Params [params]
     * @param  string $passwd [密码原串]
     */
    public function createPasswd($passwd = '')
    {
        // $signStr = "r1zhaox1anglushengz1yan";

       // return md5($passwd . $signStr);
        return $passwd;
    }


    /**
     * 用户列表
     * Author JiaXu
     * Date 2018-04-07
     * Params [params]
     * @param  string $passwd [密码原串]
     */
    public function UserList( )
    {
     // $User =  DB::select('select id,user_name,user_mobile,updatetime from t_user_info');
      //  $User = DB::;
       // $aa = $User->paginate(5);
      //  return empty($aa) ? false : json_decode(json_encode($aa), true);
        $users = DB::table('t_user_info')->paginate(2);
        return view('user.index', ['users' => $users]);
    }
/**
 * 用户信息查询
 * Author Amber
 * Date 2018-06-19
 * Params [params]
 * @param  [type] $user_id [description]
 * @return [type]          [description]
 */
    public function userinfo($user_id)
    {
       $user_item = DB::table('t_user_info')
        ->select('t_user_info.id','t_user_info.user_mobile','t_user_infos.head_portrait','t_user_infos.signature','t_user_infos.user_name')
        ->where("user_id", $user_id)
        ->join('t_user_infos','user_id','t_user_info.id')
        ->first();
        return $user_item ? get_object_vars($user_item) : False;

    }

/**
 * 修改手机号
 * Author Amber
 * Date 2018-11-26
 * Params [params]
 * @param  Request $request [description]
 * @return [type]           [description]
 */
    public function update_mobile($user_id,$user_mobile,$sms_code)
    {
      $checkSmsCode = $this->checkSmsCode($user_mobile, $sms_code);
        if($checkSmsCode == FALSE){
            $res = array(
                "errNo" => "1002",
                "errMsg" => "短信验证码错误"
            );

            return $res;
        }
      $res =  DB::table('t_user_info')
        ->where('id', $user_id)
        ->update(['user_mobile' => $user_mobile]);
      return $res;
    }
/**
 * 用户信息补全
 * Author Amber
 * Date 2018-08-01
 * Params [params]
 * @param  [type] $head_img  [description]
 * @param  [type] $user_name [description]$head_img,$user_name,$user_id,$sex,$email
 */
    public function userinfo_add($head_img,$user_name,$user_id,$signature)
    {
      // echo  "dfsdf".$head_img;die;
        if(!empty($head_img)){
            // echo 1;die;
           $file = $head_img;
                if($file -> isValid()){//检验一下上传的文件是否有效  
                    $clientName = $file -> getClientOriginalName(); //获取文件名称  
                    $tmpName = $file -> getFileName();  //缓存tmp文件夹中的文件名，例如 php9372.tmp 这种类型的  
                    $realPath = $file -> getRealPath();  
                    $entension = $file -> getClientOriginalExtension();  //上传文件的后缀  
                    $mimeTye = $file -> getMimeType();  //大家对MimeType应该不陌生了，我得到的结果是 video/mp4   
                    $newName = date('ymdhis').$clientName;
                    $path = $file -> move('services',$newName);  
                }
            $dat = OSS::publicUpload('mithril-capsule',$newName, $path,['ContentType' => $mimeTye]);

            $img = OSS::getPublicObjectURL('mithril-capsule',$newName); // 打印出某个文件的外网链接

            $bool = DB::table('t_user_infos')
                    ->where('user_id', $user_id)
                    ->update(['head_portrait' => $img,'user_name' => $user_name,'signature' => $signature]);
            return $bool;
        }else{
              // echo 2;die;
            $bool = DB::table('t_user_infos')
                ->where('user_id', $user_id)
                ->update(['user_name' => $user_name,'signature' => $signature]);
                // dd($bool);die;
             return $bool;
        }
    }
      

    public function del_old_news($user_id)
    {
      $res = DB::delete("delete from t_user_infos where user_id = $user_id");
    // echo $res;die;
       return $res;
    }

/**
 * 添加收货地址
 * Author Amber
 * Date 2018-08-01
 */
    public function add_user_address($order)
    {
       
       $res = DB::table('g_user_address')->insert($order);
       return $res;
    }
/**
 * 查询用户收货列表 
 * Author Amber
 * Date 2019-01-09
 * Params [params]
 */
    public function select_user_address($user_id)
    {
         // echo $user_id;die;
        $list = DB::table('g_user_address')
                ->where('user_id',$user_id)
                ->orderby('last_used_at','desc')
                ->get();
        $address_list = json_decode(json_encode($list), true); 
        return $address_list ? $address_list : FALSE;
    }
   public function del_user_address($user_id)
    {
        $res = DB::table('g_user_address')
                ->where('user_id',$user_id)
                ->delete();
        return $res;
    }
}
