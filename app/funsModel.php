<?php
namespace App;

use Illuminate\Database\Eloquent\Model;

use DB;
use App\GetPYModel;
use Illuminate\Support\Facades\Redis;

class funsModel extends Model{

    public $_tabName = 't_game_main';
    const HISTORY_SEARCH_KEY = 'MY_HISTORY_%d'; //历史搜索

    public function search($keyword = '',$user_id)
    {

        // $key = sprintf(self::HISTORY_SEARCH_KEY,$user_id);//存关键字

        // Redis::SADD($key,$keyword);

    	$sql = "select id,article_thumb as g_thumb,article_title as g_name from t_article where article_content like '%".$keyword."%' or article_title like  '%".$keyword."%' ";
        $sqltwo = "select id,video_text,video_cover from t_video where video_desc like '%".$keyword."%' or video_text like  '%".$keyword."%' ";
        $sqlthree = "select id,goods_name,goods_thumb from g_product where goods_name like '%".$keyword."%' ";
 //or goods_en_name like '".$keyword."%'
        $productInfo['article'] = DB::select($sql);
        $productInfo['video'] = DB::select($sqltwo);
 		$productInfo['goods'] = DB::select($sqlthree);
 		$res = array();
        $product =  json_decode(json_encode($productInfo), true);
       // print_r($product);die;
 		

 		return empty($product) ? false : $product;         

    }

    public function formatPY()
    {
        $productNameList = $this->getProductNameList();
        $GetPYModel = new GetPYModel();
        foreach ($productNameList as $key => $value) {
            $data = array();
            $data['goods_en_name'] = $GetPYModel->encode($value, "all");

            DB::table("ecs_goods")
            ->where('goods_id', $key)
            ->update($data);
        }

        echo "success";
    }

    public function getProductNameList()
    {
        $productInfo = DB::table("ecs_goods")
            ->pluck( 'goods_name', 'goods_id');

        return $productInfo;
    }

    /**
     * 历史搜索
     * Author Liuran
     * Date 2018-06-21
     * @param 
     */
    public function history_Search($id)
    {
    	//HISTORY_SEARCH_KEY
       //查询该用户的搜索记录
       $key = sprintf(self::HISTORY_SEARCH_KEY,$id);
       
       $history_search =  Redis::SUNION($key);
       return  $history_search;
    }
}