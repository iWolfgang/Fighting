<?php
namespace App;

use Illuminate\Database\Eloquent\Model;

use DB;
use Illuminate\Support\Facades\Redis;


class OrderModel extends Model{

    public $_tabName = 'g_order';

}