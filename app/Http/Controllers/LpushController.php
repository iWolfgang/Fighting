<?php
namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\LpushModel;
// use JPush\Client as JPush;
use Illuminate\Support\Facades\DB;

class LpushController extends Controller
{
	public function push(){

		$push = new LpushModel();

		$res = $push->push();
	}



}