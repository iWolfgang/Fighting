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
    public function Add_collect($article_id,$user_id)
    {
		$art_id = $this->isset_art_id($article_id);
        
        if($art_id == false){
                $res = array(
                "errNo" => "5005",
                "errMsg" => "文章信息获取失败"
            );

            return $res;
        }
       
        $num = $this->is_conllect($article_id,$user_id);
        
        if($num == false){
            $res = array(
                "errNo" => "5006",
                "errMsg" => "文章已收藏过"
            );
            return $res;
        }

        $bol = $this->add_conllects($article_id,$user_id);
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
    public function isset_art_id($article_id)
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
    public function is_conllect($article_id = '',$user_id = '')
    {
        $count = DB::table($this->_tabName)
            ->where("article_id", $article_id)
            ->where("user_id", $user_id)
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
    public function add_conllects($article_id = '',$user_id = '')
    {
        $data = array(
            'article_id' => $article_id,
            'user_id' => $user_id,
            'type_id' => 2
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

            $collectInfo = DB::select('select article_thumb,article_title,updatetime,article_author from t_article as a 
                                      join t_article_conllect as b on a.id=b.article_id 
                                      where user_id = :user_id',['user_id' => $user_id]);
            // print_r($collectInfo);die;get_object_vars()
            return empty($collectInfo) ? false : $collectInfo;
    }
}