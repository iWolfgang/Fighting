<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\RbacModel;
use App\UserModel;
use DB;
use PDO;

class RbacController extends Controller
{
    public function index(Request $request)
    {
        return view('rbac/rbac');
    }
    public function left(Request $request)
    {
        return view('rbac/left');
    }
    public function swich(Request $request)
    {
        return view('rbac/swich');
    }
    public function mains(Request $request)
    {
        return view('rbac/mains');
    }
    public function top(Request $request)
    {
        return view('rbac/top');
    }
    public function bottom(Request $request)
    {
        return view('rbac/bottom');
    }
    /**
     * 用户列表
     * Author Amber
     * Date 2018-05-23
     */
    public function userlist(Request $request)
    {
       $users = DB::table('t_user_info')->paginate(2);
       $data = json_decode(json_encode($users), true);
//       print_r($data);die;
      return view('rbac/userlist', ['data' => $data]);



    }
    /**
     * 用户登陆
     * Author Amber
     * Date 2018-05-21
     */
    public function login(Request $request)
    {

        $name = $request['name'];
        $pwd = $request['pwd'];
        $RbacModel = new RbacModel();

        $ret = $RbacModel->UserLogin($name, $pwd);
       // echo $name;die;
        if($ret == FALSE){
            echo "<script>alert('用户名或密码有误');window.location.href = 'index';</script>";
        }else{
 
            session_start();
          
            $request->session()->put('name', $name);
          
            echo "<script>alert('登陆成功');window.location.href = 'main'</script>";
        }
    }
    /**
     * 主页面
     * Author Amber
     * Date 2018-05-21
     */
    public function main()
    {
        return view('rbac/main');
    }
    /**
     * 用户注册
     * Author Amber
     * Date 2018-05-22
     */
    public function regist(Request $request)
    {
        return view('rbac/regist');

    }

    public function regist_do(Request $request){

        $name = $request['name'];
        $pwd = $request['pwd'];
        $RbacModel = new RbacModel();

        $ret = $RbacModel->UserRegist($name, $pwd);

        if ($ret == FALSE) {
            echo "<script>alert('系统错误');window.location.href = 'regist'</script>";

        } else {
            echo "<script>alert('注册成功');window.location.href = 'index'</script>";

        }
    }
}