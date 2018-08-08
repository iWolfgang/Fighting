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
        // echo time();die;
    	$bool = DB::table('g_orders')
            // ->select('g_orders.id','g_orders.no','g_orders.address','g_orders.total_amount','g_orders.remark','g_orders.paid_status','g_orders.creatorder_at','g_orders.expiration_at')
        ->select('id','no','total_amount','remark','paid_status','creatorder_at','expiration_at')
            ->where('user_id',$user_id)
            ->where('paid_status',"待支付")
            ->get();
        $objects = json_decode(json_encode($bool), true);
        // print_r($objects);die;
        $list = array();
        if(!empty($objects)){
            foreach ($objects as $key => $val) {
            if(time() > $val['expiration_at']){
                //echo 2;die;
                $del_order =  DB::delete('delete from g_orders where id = '.$val['id'].'');
            
                if($del_order){
                    $small_order = DB::table('g_order_items')
                     ->select()
                     ->where('order_id',$val['id'])
                     ->get();
                     $small = json_decode(json_encode($small_order), true);

                   // print_r($small);die;
                    foreach ($small as $key => $value) {
                        
                        $del_item =  DB::update('update  g_goods set inventory = inventory + '.$value['amout'].' where id = '.$value['goods_id'].'');
                        $del_items =  DB::delete('delete from g_order_items where order_id = '.$val['id'].'');
                        
                    }
                }


            }
          else{
                foreach ($objects as $key => $value) {
                    
                    $small_order = DB::table('g_order_items')
                     ->select()
                     ->where('order_id',$value['id'])
                     ->get();
                     $small[] = json_decode(json_encode($small_order), true);
                // }
                    
                     // print_r($small);die;
                    foreach ($small as $ke => $va) {

                        foreach ($va as $k => $v) {
                            $goods = DB::table('g_goods')
                                ->select('id','goods_name','goods_thumb')
                                ->where('id',$v['goods_id'])
                                ->first();
                          
                            $list[$ke][$k]['goods'] = get_object_vars($goods);
                            // $list[$ke][$k]['no'] =$value['no'];
                            // $list[$ke][$k]['order_id'] =$value['id'];
                            $list[$ke][$k]['amout'] =$v['amout'];
                            $list[$ke][$k]['price'] =$v['price'];
                         }    
                 }
             }

           // print_r($list);die;
                    return $list ? $list : False; 
            }
        }

    }else{
            return False;
        }
        
    }
}