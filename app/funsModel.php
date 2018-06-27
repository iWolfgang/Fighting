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

        $key = sprintf(self::HISTORY_SEARCH_KEY,$user_id);//存关键字

        Redis::SADD($key,$keyword);

    	$sql = "select g_name from t_game_main where g_name like '".$keyword."%' limit 10";
 //or goods_en_name like '".$keyword."%'
 		$productInfo = DB::select($sql);
 		$res = array();

 		foreach ($productInfo as $key => $value) {
 			$value = get_object_vars($value);

 			$res[] = $value['g_name'];
 		}

 		return empty($res) ? false : $res;         

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