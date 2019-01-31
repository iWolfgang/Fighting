<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use App\Services\OSS;
use DB;
use Illuminate\Support\Facades\Redis;
//Call to undefined method Illuminate\Database\MySqlConnection::()
use Illuminate\Database\MySqlConnection\paginate;

class PayModel extends Model
{

	public function apply_refund($data)
	{
	  if(!empty($data['refund_imgs'])){
            // echo 1;die;
           // $file = $head_img;
           //      if($file -> isValid()){//检验一下上传的文件是否有效  
           //          $clientName = $file -> getClientOriginalName(); //获取文件名称  
           //          $tmpName = $file -> getFileName();  //缓存tmp文件夹中的文件名，例如 php9372.tmp 这种类型的  
           //          $realPath = $file -> getRealPath();  
           //          $entension = $file -> getClientOriginalExtension();  //上传文件的后缀  
           //          $mimeTye = $file -> getMimeType();  //大家对MimeType应该不陌生了，我得到的结果是 video/mp4   
           //          $newName = date('ymdhis').$clientName;
           //          $path = $file -> move('services',$newName);  
           //      }
           //  $dat = OSS::publicUpload('mithril-capsule',$newName, $path,['ContentType' => $mimeTye]);

           //  $img = OSS::getPublicObjectURL('mithril-capsule',$newName); // 打印出某个文件的外网链接
            $img = "https://mithril-capsule.oss-cn-beijing.aliyuncs.com/8f738d2ea188a7fb937f349a440e77e9.jpg";
            $bool = DB::table('g_order_refund')
                    ->insert($data);
            return $bool;
		}


	}	

}