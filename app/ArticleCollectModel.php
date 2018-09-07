<?php
namespace App;

use Illuminate\Database\Eloquent\Model;

use DB;

class ArticleCollectModel extends Model{

    public $_tabName = 't_article_conllect';


    /**
     * 文章收藏
     * Author Liuran
     * Date 2018-04-17
     * @param  [type] $id [接受的文章id]
     */
    public function Add_collect($article_id,$user_id,$type_id)
    {
		// $art_id = $this->isset_art_id($article_id,$type_id);
        
  //       if($art_id == false){
  //               $res = array(
  //               "errNo" => "5005",
  //               "errMsg" => "文章信息获取失败"
  //           );

  //           return $res;
  //       }
       
       
        $num = $this->is_conllect($article_id,$user_id,$type_id);
        
        if($num == false){
            $res = array(
                "errNo" => "5006",
                "errMsg" => "文章已收藏过"
            );
            return $res;
        }
 // echo 1;die;
        $bol = $this->add_conllects($article_id,$user_id,$type_id);
        if($bol == false){
             $res = array(
                "errNo" => "1005",
                "errMsg" => "系统错误"
            );

            return $res;
        }else{
            $res = array(
                "errNo" => "0",
                "errMsg" => "收藏成功"
            );

            return $res;
        }
         
   	}
    /**
     * 查看文章id是否存在
     * Author Amber
     * Date 2018-04-17
     * @param  string $article_id [文章id]
     */
    public function isset_art_id($article_id,$type_id)
    {
            $count = DB::table('t_article')
            ->where("id", $article_id)
            ->count();

        return $count > 0 ? true : false;
    }
    /**
     * 查看文章是否被收藏
     * Author Amber
     * Date 2018-04-17
     * @param  string $article_id [文章id]
     */
    public function is_conllect($article_id = '',$user_id = '',$type_id='')
    {
        $count = DB::table($this->_tabName)
            ->where("article_id", $article_id)
            ->where("user_id", $user_id)
            ->where("type_id", $type_id)
            ->count();

       return $count < 1 ? true : false;
    }

    /**
     * 添加收藏的信息 
     * Author Amber
     * Date 2018-04-17
     * Params [params]
     * @param string $article_id [description]
     * @param string $user_id    [description]
     */
    public function add_conllects($article_id = '',$user_id = '',$type_id = '')
    {
        // echo $type_id;die;
        $data = array(
            'article_id' => $article_id,
            'user_id' => $user_id,
            'type_id' => $type_id
        );
        $bool = DB::table($this->_tabName)->insert($data);
        
        return $bool ? true: false;
    }


    /**
     * 文章收藏列表展示
     * Author Amber
     * Date 2018-04-19
     * Params [user_id]
     * @param string $user_id [用户id]
     */
    public function Show_collect_reply($user_id='')
    {
        $col_type = DB::table('t_article_conllect')  
        ->select('article_id','type_id')
        ->where('user_id',$user_id)
        ->get();
        $CommentInfos = json_decode(json_encode($col_type), true);
        
        $arr = array();
        foreach ($CommentInfos as $key => $value) {
            if($value['type_id'] == 'longa'){
                $arr[] = DB::table('t_article')  
                        ->select('id','all_type','article_thumb','article_title','article_type','created_at')
                        ->where('id',$value['article_id'])
                        ->get();
            }
           elseif($value['type_id'] == 'shorta'){
                $arr[] = DB::table('t_shorts_article')
                        ->select('id','t_shorts_article.id','source_img','source','all_type','content','t_shorts_article.created_at','imageurl','videourl')
                        ->join('t_shorts_img','t_shorts_article.id','=','t_shorts_img.shorts_article_id')
                        ->where('t_shorts_article.id',$value['article_id'])
                        ->get();
            }
            elseif($value['type_id'] == 'video'){
                $arr[] = DB::table('t_video')  
                      ->select('id','video_type','source_img','source','video_text','video_url','created_at')
                      ->where('id',$value['article_id'])
                      ->get();
            }

        }
         $arr = json_decode(json_encode($arr), true);
         // print_r($arr);die;
         $res = array();
         foreach ($arr as $k => $v) {
            foreach ($v as $ke => $val) {
              $res[] = $val; 
            }  
         }
         return $res;
    }
}