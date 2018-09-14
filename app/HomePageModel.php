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
            ->orderBy('created_at', 'desc')
            ->get(['slideshow','title','slideshow_url','type']);
          $data = json_decode(json_encode($data), true);

          return $data;
    }
/**
 * 长资讯列表
 * Author Amber
 * Date 2018-06-19
 * Params `
 * @return [type] [description]
 */
  public function long_articlelist($more)
    {
      // if($more == False){
      //   echo 2;die;

      //      $objects = DB::table('t_article')  
      //           ->select('id','article_thumb','article_title','article_type','created_at')
      //           ->where('its_type','2')
      //           ->orderBy('created_at', 'desc')
      //           ->get();
        
      // }
      // else{
        // echo 1;die;
        
           $objects = DB::table('t_article')  
                ->select('id','all_type','article_thumb','article_title','article_type','created_at')
                ->where('its_type','2')
                 ->limit(9)
                 ->orderBy('created_at', 'desc')
                ->get();
        
      // }
            
          $data = json_decode(json_encode($objects), true);
          
          return empty($data) ? false : $data;
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
              ->orderBy('created_at', 'desc')
              ->get();
      }else{
         $objects = DB::table('t_article')  
              ->select('id','ceping_type','article_thumb','article_title','article_type','created_at')
              ->where('its_type','1')
              ->limit(9)
              ->orderBy('created_at', 'desc')
              ->get();
      }
          
        $data = json_decode(json_encode($objects), true);
        
        return empty($data) ? false : $data;
  }
/**
 * 短资讯列表页
 */
   public function short_articlelist($more)
    {

      if($more == 1){
        $objects = DB::table('t_shorts_article')
        ->select('t_shorts_article.id','source_img','source','all_type','content','t_shorts_article.created_at','imageurl','videourl')
        ->join('t_shorts_img','t_shorts_article.id','=','t_shorts_img.shorts_article_id')
        ->orderBy('created_at', 'desc')
        ->get();
      }else{
        $objects = DB::table('t_shorts_article')  
        ->select('t_shorts_article.id','source_img','source','all_type','content','t_shorts_article.created_at','imageurl','videourl')
        ->join('t_shorts_img','t_shorts_article.id','=','t_shorts_img.shorts_article_id')
        ->limit(6)
        ->orderBy('created_at', 'desc')
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

  public function videolist($more)
    {
      
          if($more){
            // echo 1;
            // echo $video_type;die;

            $objects = DB::table('t_video')  
            ->select('id','video_type','source_img','source','video_text','video_url','created_at')
            // ->where('video_type',$video_type),$video_type
            ->orderBy('created_at', 'desc')
            ->get();
          }else{
                        // echo 2;die;
// echo $video_type;die;
          $objects = DB::table('t_video')  
                ->select('id','video_type','source_img','source','video_text','video_url','created_at')
                // ->where('video_type',$video_type)
                ->limit(4)
                ->orderBy('created_at', 'desc')
                ->get();

          }
          $data = json_decode(json_encode($objects), true);
          // print_r($data);die;
          return empty($data) ? false : $data;
    }

/**
 *视频资讯详情页信息
 * Author Amber-
 * Date 2018-06-22
 * Params [params]
 * @param  string $value [description]
 * @return [type]        [description],'fk_game_id'
 */
  public function video_info($article_id)
  {
              $objects = DB::table('t_video')  
                ->select('id','source_img','source','video_url','video_text','video_desc','created_at','fk_game_id','tapid')
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
        $objects = DB::table('t_answer')  
        ->select()
        ->where('t_answer.issue_id',$id)
        ->get();
       $data = json_decode(json_encode($objects), true);
     
          return empty($data) ? false : $data;
   }
     

}
