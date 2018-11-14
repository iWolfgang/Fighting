<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class CreatOrderModel extends Model
{
    protected $table = 'g_productSkus';
    public function SelectSkuItem($sku_id ='')
    {
    	$flights = App\CreatOrder::where('id', $sku_id)->pluck('title','pricenow','stock');
    	print_r($flights);die;
    }

}