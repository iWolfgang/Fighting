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
    public function banner(Request $request)
    {
        return view('rbac/banner');
    }
    public function game_video()
    {
          return view('rbac/game_video');
    }
    public function article()
    {
          return view('rbac/article');
    }
    public function article_add(Request $request)
    {
      

        $headimg =  $request->file("files");
        $content = $request->input('content');
        $title = $request['title'];
        $source = $request['source'];
        $type = $request['type'];
        $game_name = $request['game_name'];
        $article_author = $request['article_author'];
// print_r($headimg."||".$content."||".$title."||".$source."||".$type."||".$game_name);die;

        $RbacModel = new RbacModel();

        $ret = $RbacModel->article_add($headimg, $content,$title,$source,$type,$game_name,$article_author);
                if($ret == FALSE){
             echo "<script>alert('添加失败,可重新操作');window.location.href = 'http://api.mithrilgaming.com:8000/Rbac/article'</script>";
        }else{
            echo "<script>alert('添加成功,可继续操作');window.location.href = 'http://api.mithrilgaming.com:8000/Rbac/article'</script>";
        }




    }
    public function game_video_info(Request $request)
    {
       echo 1;die;
        $title = $request['title'];
        $content = $request['content'];
        $source = $request['source'];
        $game_name = $request['game_name'];
        $video_type = $request['video_type'];
        $game_video = $request->file("video");
        $RbacModel = new RbacModel();

        $ret = $RbacModel->game_video_info($title, $content,$game_video,$source,$video_type,$game_name);
        if($ret == FALSE){
             echo "<script>alert('添加失败,可重新操作');window.location.href = 'http://api.mithrilgaming.com:8000/Rbac/game_video'</script>";
        }else{
            echo "<script>alert('添加成功,可继续操作');window.location.href = 'http://api.mithrilgaming.com:8000/Rbac/game_video'</script>";
        }



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

        if($ret == FALSE){
            echo "<script>alert('用户名或密码有误');window.location.href = 'index';</script>";
        }else{
           session_start();
            $request->session()->put('name', $name);
            //echo session('name');die;
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