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
 **/
    public function slideshow()
    {
      $data = DB::table($this->_tabName)
            ->where('slideshow_type','article')
            ->orderBy('created_at', 'desc')
            ->get(['slideshow','slideshow_url','type']);
      $data = json_decode(json_encode($data), true);
      return $data;
    }
/**
 * 长资讯列表
 * Author Amber
 * Date 2018-06-19
 * Params `
 * @return [type] [description],$page
 */
  public function long_articlelist($more)
    {
      if($more == true){
        $objects = DB::table('t_article')  
          ->select('id','all_type','article_thumb','article_title','article_type','created_at')
          ->where('its_type','2')
          ->where('article_status','1')
          ->orderBy('created_at', 'desc')
          ->get();
          $data = json_decode(json_encode($objects), true);
          
          return empty($data) ? false : $data;
      }else{
          $objects = DB::table('t_article')  
          ->select('id','all_type','article_thumb','article_title','article_type','created_at')
          ->where('its_type','2')
          ->where('article_status','1')
          ->orderBy('created_at', 'desc')
          ->limit(9)
          ->get();
            
          $data = json_decode(json_encode($objects), true);
          return empty($data) ? false : $data;
      }
    }
/**
 * 测评列表
* Author Amber
 * Date 2018-06-19
 * Params `
 * @return [type] [description]
 */
  public function Evaluation_list($more )
  {
    if($more > 0){

        $objects = DB::table('t_article')  
              ->select('id','ceping_type','article_thumb','article_title','article_type','created_at')
              ->where('its_type','1')
              ->where('article_status','1')
              ->orderBy('created_at', 'desc')
              ->get();
      }else{
         $objects = DB::table('t_article')  
              ->select('id','ceping_type','article_thumb','article_title','article_type','created_at')
              ->where('its_type','1')
              ->where('article_status','1')
              ->limit(9)
              ->orderBy('created_at', 'desc')
              ->get();
      }
          
        $data = json_decode(json_encode($objects), true);
        
        return empty($data) ? false : $data;
  }
/**
 * 短资讯列表页$more,$page
 */
    public function short_articlelist($more)
    {
        if($more == 1){
          $objects = DB::table('t_shorts_article')
                ->select('t_shorts_article.id','t_shorts_article.title','source_img','source','all_type','content','t_shorts_article.created_at','imageurl','videourl')
                ->join('t_shorts_img','t_shorts_article.id','=','t_shorts_img.shorts_article_id')
                ->orderBy('created_at', 'desc')
                ->get();

          $data = json_decode(json_encode($objects), true);//全部的短资讯新闻
          $res = array();
          foreach ($data as $key => $value){
               $value['imageurl'] = $this->getImageurlAttribute($value['imageurl']);
               $res[] = $value;
          }
          return empty($res) ? false : $res;
        }else{
           $data = DB::table('t_shorts_article')
                ->select('t_shorts_article.id','t_shorts_article.title','source_img','source','all_type','content','t_shorts_article.created_at','imageurl','videourl')
                ->join('t_shorts_img','t_shorts_article.id','=','t_shorts_img.shorts_article_id')
                ->orderBy('created_at', 'desc')
                ->limit(5)
                ->get();

          $data = json_decode(json_encode($data), true);
          $res = array();
          foreach ($data as $key => $value){
               $value['imageurl'] = $this->getImageurlAttribute($value['imageurl']);
               $res[] = $value;
          }

         return empty($res) ? false : $res;
         
       }
     }

  public function getImageurlAttribute($cover)
    {
        return json_decode($cover, true);
    }

  public function videolist($more)
    {
          if($more){
            $object = DB::table('t_video')  
              ->select('id','video_type','video_desc','source_img','video_cover','source','video_text','video_url','created_at')
              ->orderBy('created_at', 'desc')
              ->get();
               $objects = json_decode(json_encode($object), true);
          }else{
            $object = DB::table('t_video')
              ->select('id','video_type','video_desc','source_img','video_cover','source','video_text','video_url','created_at')
              ->limit(5)
              ->orderBy('created_at', 'desc')
              ->get();
               $objects = json_decode(json_encode($object), true);
          }
          return empty($objects) ? false : $objects;
    }

  /**
   *视频资讯详情页信息
   * Author Amber-
   * Date 2018-06-22
   * Params [params]
   * @param  string $value [description]
   * @return [type]        [description]
   */
    public function video_info($article_id)
    {
          $objects = DB::table('t_video')  
                ->select('id','source_img','source','video_cover','video_url','video_text','video_desc','created_at','fk_game_id','tapid')
                ->where('id',$article_id)
                ->first();
 
          $data = json_decode(json_encode($objects), true);
          return empty($data) ? false : $data;
    }

    public function q_question(){
        $objects = DB::table('t_issue')  
        ->select('id','issue','describe')
        ->get();
       $data = json_decode(json_encode($objects), true);
       return empty($data) ? false : $data;
    }
    
    public function q_ask($id)
    {
        $objects = DB::table('t_answer')  
        ->select()
        ->where('t_answer.issue_id',$id)
        ->get();
        $data = json_decode(json_encode($objects), true);
     
       return empty($data) ? false : $data;
   }
     

}
