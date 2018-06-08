<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use App\Services\OSS;

use DB;

class HomePageModel extends Model
{
    public $_tabName = 't_slideshow';

    /**
     * 展示轮播图 
     * Author Amber
     * Date 2018-05-08
     * Params [params]
     
     */
    public function slideshow($slideshow_type = '')
    {
        // if($slideshow_type != 1){
            
        //     return False;
        // }
        $data = DB::table($this->_tabName)
        ->where('type', 1)
        ->limit(3)
        ->get(['slideshow','title','slideshow_url']);

        $data = json_decode(json_encode($data), true);
        return $data;
        
    }

  /**
   * 添加轮播图 
   * Author Amber
   * Date 2018-05-08
   * @param  string $slideshow      [图片信息]
   * @param  string $slideshow_urll [将要天转的路径]
   */
    public function slideshow_add($slideshow = '', $title = '', $slideshow_urll = '', $slideshow_type = '')
    {

        $file = $slideshow;
                // print_r($file);die;
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

            $data['slideshow'] = $img;
            $data['slideshow_url'] = $slideshow_urll;

            $data['type'] = $slideshow_type;
            $data['title'] = $title;

            $into = DB::table($this->_tabName)
                ->insert($data); 

            return $into;
    }

/**
 * 上传图片至素材库
 * Author Amber
 * Date 2018-05-08
 * Params [params]
 * @param  [type] $img [要处理的图片]
 */
    public function actionUpload($img)
    {
            $img_name = $img['name'];
            $url = "./slideshow_img/";//路径
            move_uploaded_file($img['tmp_name'],  $url . rand() . $file['name']);//原路径，新路径
            $str =  $url . rand() . $file['name'];
            return $str;
     
    }

  public function long_articlelist()
    {
          $objects = DB::table('t_article')  
                ->select('article_thumb','article_title','article_type','updatetime','article_source')
                ->join('t_article_main','t_article.id','=','t_article_main.m_id')
                ->limit(9)
                ->get();
                //  
                // ->paginate(3);
            
          $data = json_decode(json_encode($objects), true);
      
          return empty($data) ? false : $data;
    }

   public function short_articlelist()
    {
      $objects = DB::table('t_shorts_article')  
        ->select('t_shorts_article.id','title','content','updatetime','source','image_url')
        ->join('t_shorts_img','t_shorts_article.id','=','t_shorts_img.shorts_article_id')
        ->get();
       $data = json_decode(json_encode($objects), true);
        $imgArr = array();
        foreach ($data as $key => $value) {
          $imgArr[$value['id']][] = $value['image_url'];
          
        }

        $res = array();
        foreach ($data as $key => $value) {
          $res[$value['id']] = $value;

          $res[$value['id']]['image_url'] = $imgArr[$value['id']];
        }

          return empty($res) ? false : $res;
    }


  public function game_videolist()
    {
          $objects = DB::table('t_video')
          // ->("select `id`,`video_title`,`Video_text`,`video_url` from `t_video` where video_type ='1'  limit 4");
                ->select('id','video_title','Video_text','video_url')
                ->where(  'video_type','1')
                ->limit(4)
                ->get();
 
          $data = json_decode(json_encode($objects), true);
   
          return empty($data) ? false : $data;
    }


  public function videolist()
    {
          $objects = DB::table('t_video')  
                ->select('id','Video_text','video_url','video_source','update')
                ->where(  'video_type','2')
                ->limit(4)
                ->get();
 
          $data = json_decode(json_encode($objects), true);

          return empty($data) ? false : $data;
    }

    public function q_ask()
    {
        $objects = DB::table('t_issue')  
        ->select('t_issue.id','issue_id','issue','answer')
        ->join('t_answer','t_issue.id','=','t_answer.issue_id')
        ->get();
       $data = json_decode(json_encode($objects), true);

       // print_r($data);die;
       $arr = array();
       foreach ($data as $key => $value) {
         $arr[$value['issue_id']][] = $value['answer'];
       }
        // 
       $res = array();
       foreach ($data as $key => $value) {
         $res[$value['id']] = $value;
         $res[$value['id']]['answer'] = $arr[$value['id']];
       }

          return empty($res) ? false : $res;
   }
     

}
