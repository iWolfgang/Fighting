<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\LogisticesModel;
use DB;

class LogisticsController extends Controller
{

/**
 * 查看物流 
 * Author Amber
 * Date 2018-12-13
 * Params [params]
 * @param string $value [description]
 */
  public function selectLog(Request $request)
  {
    $LogisticsModel = new LogisticesModel();
    $data = $LogisticsModel->selectLog();
  }

}