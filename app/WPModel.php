<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

use DB;

class WPModel extends Model
{
    public $_tabName = 'send_code';

    public function sel(){

    	// echo $_tabName;die;
        $userId = DB::table($this->_tabName)
            ->select()->get();

        return $userId;
    }
}