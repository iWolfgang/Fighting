<?php
namespace App;

use Illuminate\Database\Eloquent\Model;

use DB;
use App\GetPYModel;

class funsModel extends Model{

    public $_tabName = 'ecs_goods';


    public function search($keyword = '')
    {
    	$sql = "select goods_name from ecs_goods where goods_name like '".$keyword."%' or goods_en_name like '".$keyword."%' limit 10";

 		$productInfo = DB::select($sql);

 		$res = array();

 		foreach ($productInfo as $key => $value) {
 			$value = get_object_vars($value);

 			$res[] = $value['goods_name'];
 		}

 		return $res;

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
     * Function 
     * Author Liuran
     * Date 2018-04-10
     * @param  [type] $id [接受的文章id]
     */
    public function article_sms($id)
    {
    	
    }
}