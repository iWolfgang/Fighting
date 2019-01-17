<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

use DB;
use Illuminate\Support\Facades\Redis;

class CreatOrderModel extends Model
{	public $_tabName = 'g_productSkus';
    // protected $table
    public function SelectSkuItem($sku_id ='')
    {
    	//$this_table->
    	$flights = DB::table($this->_tabName)->select('title','pricenow','stock')->where('id', $sku_id);
    	// print_r($flights);die;
    	return $flights;
    }

}