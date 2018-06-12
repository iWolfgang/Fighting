<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use App\Services\OSS;
use DB;
use Illuminate\Support\Facades\Redis;

class RbacModel extends Model
{
    public $_tabName = 'a_user';

    /**
     * 用户登陆
     * Author Amber
     * Date 2018-05-22
     * Params [params]
     * @param  integer $user_id [用户id]
     */
    public function UserLogin($name, $pwd)
    {
        $userInfo = DB::table($this->_tabName)
            ->where('u_name', $name)
            ->where('u_pwd', $pwd)
            ->first();
        return empty($userInfo) ? false : get_object_vars($userInfo);
    }

    /**
     * 创建用户
     * Author Amber
     * Date 2018-05-22
     * Params [params]
     * @param  integer $user_id [用户id]
     */
    public function UserRegist($name, $pwd)
    {
        $data = array();
        $data['u_name'] = $name;
        $data['u_pwd'] = $pwd;
       // $data['u_mobile'] = '1234456';
        $add = DB::table($this->_tabName)
            ->insert($data);

        return $add;
    }

    // public function img_dispose($value='')
    // {
        
    // }

/**
 * 游戏视频上传
 * Author Amber
 * Date 2018-06-11
 * @return [type]             [description]
 */
    public function game_video_info($title, $content,$game_video,$source,$video_type)
    {
        $file = $game_video;
        
        if($file -> isValid()){  
            //检验一下上传的文件是否有效  
            $clientName = $file -> getClientOriginalName(); //获取文件名称  
            $tmpName = $file -> getFileName();  //缓存tmp文件夹中的文件名，例如 php9372.tmp 这种类型的  
            $realPath = $file -> getRealPath();  //

            $entension = $file -> getClientOriginalExtension();  //上传文件的后缀  

            $mimeTye = $file -> getMimeType();  //大家对MimeType应该不陌生了，我得到的结果是 image/jpeg  

            $newName = date('ymdhis').$clientName;
            $path = $file -> move('services',$newName);  
        }
        OSS::publicUpload('mithril-capsule',$newName, $path);// 上传一个文件

        $img = OSS::getPublicObjectURL('mithril-capsule',$newName); // 打印出某个文件的外网链接


            $data['video_title'] = $title;
            $data['video_text'] = $content;
            $data['video_url'] = $img;
            $data['video_type'] = $video_type;
            $data['video_source'] = $source;
            $data['update'] = '20'.date('y-m-d h:i:s');
            

            $into = DB::table('t_video')
                ->insert($data); 

            return $into;
    }

    public function article_add($headimg,$content,$title,$source,$type,$game_name,$article_author)
    {

        $file = $headimg;
        
        if($file -> isValid()){  
            //检验一下上传的文件是否有效  
            $clientName = $file -> getClientOriginalName(); //获取文件名称  
            $tmpName = $file -> getFileName();  //缓存tmp文件夹中的文件名，例如 php9372.tmp 这种类型的  
            $realPath = $file -> getRealPath();  //

            $entension = $file -> getClientOriginalExtension();  //上传文件的后缀  

            $mimeTye = $file -> getMimeType();  //大家对MimeType应该不陌生了，我得到的结果是 image/jpeg  

            $newName = date('ymdhis').$clientName;
            $path = $file -> move('services',$newName);  
        }
        OSS::publicUpload('mithril-capsule',$newName, $path);// 上传一个文件

        $img = OSS::getPublicObjectURL('mithril-capsule',$newName); // 打印出某个文件的外网链接
            $data['article_title'] = $title;
            $data['article_content'] = $content;
            // print_r($data);die;
            $into = DB::table('t_article_main')
                ->insert($data); 

            if($into){

                $dat['article_img'] = $headimg;
                $dat['fk_game_name'] = $game_name;
                $dat['article_thumb'] = $headimg;
                $dat['article_type'] = $type;
                $dat['article_author'] = $article_author;
                $dat['article_source'] = $source;
                $dat['updatetime'] = '20'.date('y-m-d h:i:s');
                $int = DB::table('t_article')
                      ->insert($dat); 
                return $int;      
            }
            return False;
    }
}