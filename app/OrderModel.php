<?php
namespace App;

use Illuminate\Database\Eloquent\Model;

use DB;
use Illuminate\Support\Facades\Redis;


class OrderModel extends Model{

    public $_tabName = 'g_orders';

/**
 * 创建一个订单
 * Author Amber
 * Date 2018-08-01
 * Params [params]
 * @param  string $value [description]
 * @return [type]        [description]
 */
    public function store($order)
    {
    	$bool = DB::table('g_orders')
            ->insertGetId($order);
        return $bool;
    }


    public function store_items($items)
    {
    	// print_r($items);die;
    	foreach ($items as $key => $value) {
    	   $bool = DB::table('g_order_items')
            ->insert($value);
    	}
    	return $bool;
    }

    public function wait_paylist($user_id='')
    {
    	$bool = DB::table('g_order_items')
            ->select('')
            ->where('user_id',$user_id)
            ->where('paid_status',"待支付")
            ->get();
    }
}