<?php

namespace App\Http\Controllers;

use Illuminate\Foundation\Bus\DispatchesJobs;
use Illuminate\Routing\Controller as BaseController;
use Illuminate\Foundation\Validation\ValidatesRequests;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;

class Controller extends BaseController
{
    use AuthorizesRequests, DispatchesJobs, ValidatesRequests;

    /**
     * 统一出口 
     * Author JiaXu
     * Date 2018-04-04
     * Params [params]
     * @param  array  $res [description]
     */
    public function _response($res = array())
    {
        header('Content-type: application/json');
        echo json_encode($res);
        exit;
    }
}
