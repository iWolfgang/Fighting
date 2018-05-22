<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\RbacModel;


class RbacController extends Controller
{
    public function index(Request $request)
    {
        return view('rbac/rbac');
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

        if($ret == FALSE){
            echo "<script>alert('用户名或密码有误');window.location.href = 'index'</script>";
        }else{
            echo "<script>alert('登陆成功');window.location.href = 'main';</script>";
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