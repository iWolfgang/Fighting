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
    public function slideshow()
    {
      $data = DB::table($this->_tabName) 
            ->limit(3)
            ->get(['slideshow','title','slideshow_url','type']);
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
        OSS::publicUpload('mithril-capsule',$newName, $path,['Content-Type' => $mimeTye]);// 上传一个文件

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
/**
 * 长资讯列表
 * Author Amber
 * Date 2018-06-19
 * Params `
 * @return [type] [description]
 */
  public function long_articlelist($game_id )
    {
      if($game_id > 0){

          $objects = DB::table('t_article')  
                ->select('id','article_thumb','article_title','article_type','updated_at','article_source')
                ->where('fk_game_id', $game_id)
                // ->join('t_article_main','t_article.id','=','t_article_main.m_id')
                ->limit(9)
                ->get();
        }else{
           $objects = DB::table('t_article')  
                ->select('id','article_thumb','article_title','article_type','updated_at','article_source')
                // ->join('t_article_main','t_article.id','=','t_article_main.m_id')
                ->limit(9)
                ->get();
        }
            
          $data = json_decode(json_encode($objects), true);
          
          return empty($data) ? false : $data;
    }
//,'updatetime'
   public function short_articlelist($more)
    {

      if($more == 1){
        $objects = DB::table('t_shorts_article')
        ->select('t_shorts_article.id','title','content','source','imageurl')
        ->join('t_shorts_img','t_shorts_article.id','=','t_shorts_img.shorts_article_id')
        ->get();
      }else{
        $objects = DB::table('t_shorts_article')  
        ->select('t_shorts_article.id','title','content','source','imageurl')
        ->join('t_shorts_img','t_shorts_article.id','=','t_shorts_img.shorts_article_id')
        ->limit(6)
        ->get();
      }

       $data = json_decode(json_encode($objects), true);


       $imgArr = array();
        foreach ($data as $key => $value) {
          $imgArr[$value['id']] =  $this->getImageurlAttribute( $value['imageurl']);   
        }
        $res = array();
        foreach ($data as $key => $value) {
          $res[$value['id']] = $value;

          $res[$value['id']]['imageurl'] = $imgArr[$value['id']];
        }
          return empty($res) ? false : $res;
    }
    public function getImageurlAttribute($cover)
    {
        return json_decode($cover, true);
    }  

  public function game_videolist()
    {
          $objects = DB::table('t_video')
          // ->("select `id`,`video_title`,`Video_text`,`video_url` from `t_video` where video_type ='1'  limit 4");
                ->select('id','video_title','video_text','video_url')
                ->where(  'video_type','1')
                ->limit(4)
                ->get();
 
          $data = json_decode(json_encode($objects), true);
   
          return empty($data) ? false : $data;
    }


  public function videolist($more)
    {
          if($more == 1){
            // echo 1;die;
            $objects = DB::table('t_video')  
            ->select('id','video_text','video_url','created_at')
            ->get();
          }else{
          $objects = DB::table('t_video')  
                ->select('id','video_text','video_url','created_at')
                ->limit(4)
                ->get();

          }
          $data = json_decode(json_encode($objects), true);

          return empty($data) ? false : $data;
    }
/**
 *视频资讯详情页信息
 * Author Amber
 * Date 2018-06-22
 * Params [params]
 * @param  string $value [description]
 * @return [type]        [description],'fk_game_id'
 */
  public function video_info($article_id)
  {
              $objects = DB::table('t_video')  
                ->select('id','video_url','video_text','updated_at')
                ->where('id',$article_id)
                ->first();
 
          $data = json_decode(json_encode($objects), true);

          return empty($data) ? false : $data;
  }

    public function q_question(){
        $objects = DB::table('t_issue')  
        ->select('id','issue','describe')
        // ->join('t_answer','t_issue.id','=','t_answer.issue_id')
        ->get();
       $data = json_decode(json_encode($objects), true);
       return empty($data) ? false : $data;
    }
    public function q_ask($id)
    {
        
      //  $objects = DB::table('t_issue')  
      //   ->select('t_issue.id','issue_id','issue','describe','answer','user_id','user_id','user_name','head_portrait')
      //   ->join('t_answer','t_issue.id','=','t_answer.issue_id')
      //   ->get();
        $objects = DB::table('t_answer')  
        ->select()
        ->where('t_answer.issue_id',$id)
        // ->join('t_user_infos','t_answer.user_id','=','t_user_infos.user_id')
        ->get();
       $data = json_decode(json_encode($objects), true);
     
      //  $arr = array();
      //  foreach ($data as $key => $value) {
      //    $arr[$value['issue_id']][] = $value['answer'];
      //    $arr[$value['user_id']][] = $value['user_id'];
      //  }
      //   // 
      //  $res = array();
      //  foreach ($data as $key => $value) {
      //    $res[$value['id']] = $value;
      //    $res[$value['id']]['answer'] = $arr[$value['id']];
      //  }

          return empty($data) ? false : $data;
   }
     

}
