<?php
namespace App;

use Illuminate\Database\Eloquent\Model;

use DB;
use Illuminate\Support\Facades\Redis;
class GoodsCatModel extends Model{
  public $_tabName = 'g_classify';
/**
 * 商品列表页 功能
 * Author Amber
 * Date 2018-07-23
 * Params [params]
 * @param [type] $page   
 * @param [type] $user_id 
 **/
    public function only_this_homelist()
    {
        $article = DB::table($this->_tabName)
            ->select('id','cat_name','cat_imageUrlCor')
            ->where("pid", 1)
            ->get();
         
        $data = json_decode(json_encode($article), true);
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