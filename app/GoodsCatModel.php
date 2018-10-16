<?php
namespace App;

use Illuminate\Database\Eloquent\Model;

use DB;
use Illuminate\Support\Facades\Redis;


class GoodsCatModel extends Model{

    public $_tabName = 'g_classify';
    // const LIKE_ZAN_COUNT = 'Like_zan_%d';//点赞功能
    /**
     * 商品列表页 功能
     * Author Amber
     * Date 2018-07-23
     * Params [params]
     * @param [type] $page    [文章id]
     * @param [type] $user_id [用户i'cat_imageUrl',
     **/
    public function only_this_homelist()
    {
        $article = DB::table($this->_tabName)
            ->select('id','cat_name','cat_imageUrlCor')
            ->where("pid", 1)
            ->get();
         
        $data = json_decode(json_encode($article), true);
        // print_r($datas);
        // $data = array_splice($datas,1);

        // print_r($data);die;
        return $data ? $data : False;        
    }
    public function only_two_homelist($cat_id='')
    {
        $article = DB::table($this->_tabName)
            ->select('id','cat_name','cat_imageUrl','cat_imageUrlCor')
            ->where("pid", $cat_id)
            ->get();
         
        $data = json_decode(json_encode($article), true);
        return $data ? $data : False;  
    }

}