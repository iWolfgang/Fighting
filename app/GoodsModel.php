<?php
namespace App;

use Illuminate\Database\Eloquent\Model;

use DB;
use Illuminate\Support\Facades\Redis;


class GoodsModel extends Model{

    public $_tabName = 'g_goods';
    // const LIKE_ZAN_COUNT = 'Like_zan_%d';//点赞功能
    /**
     * 商品列表页 功能
     * Author Amber
     * Date 2018-07-23
     * Params [params]
     * @param [type] $page    [文章id]
     * @param [type] $user_id [用户i
     *d]
     */
    public function GoodsList($classify_id)
    {
        $isset = $this->only_this_goodslist($classify_id);
        return $isset;
    }
    public function only_this_goodslist($classify_id='')
    {
        $article = DB::table($this->_tabName)
            ->select('id','goods_name')
            ->where("goods_cat", $classify_id)
            ->get();
         // print_r($article);die;
        $data = json_decode(json_encode($article), true);

        return $data ? $data : False;        
    }

    public function detail_page($goods_id='')
    {
        $data = DB::table($this->_tabName)
            ->select()
            ->where("id", $goods_id)
            ->first();
         // print_r($article);die;
        // $data = json_decode(json_encode($article), true);

        return $data ? get_object_vars($data): False;     
    }
}