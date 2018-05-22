<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

use DB;
use Illuminate\Support\Facades\Redis;

class RbacModel extends Model
{
    public $_tabName = 'a_user';

    /**
     * 用户登陆
     * Author Amber
     * Date 2018-05-22
     * Params [params]
     * @param  integer $user_id [用户id]
     */
    public function UserLogin($name, $pwd)
    {
        $userInfo = DB::table($this->_tabName)
            ->where('u_name', $name)
            ->where('u_pwd', $pwd)
            ->first();
//var_dump( $userInfo);die;
        return empty($userInfo) ? false : get_object_vars($userInfo);
    }

    /**
     * 创建用户
     * Author Amber
     * Date 2018-05-22
     * Params [params]
     * @param  integer $user_id [用户id]
     */
    public function UserRegist($name, $pwd)
    {
        $data = array();
        $data['u_name'] = $name;
        $data['u_pwd'] = $pwd;
        $data['u_mobile'] = '1234456';
        $add = DB::table($this->_tabName)
            ->insert($data);

        return $add;
    }
}