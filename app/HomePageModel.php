<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use App\Services\OSS;

use DB;

class HomePageModel extends Model
{
    public $_tabName = 't_slideshow';

    /**
     * 展示轮播图 
     * Author Amber
     * Date 2018-05-08
     * Params [params]
     
     */
    public function slideshow($slideshow_type = '')
    {
        if($slideshow_type != 1){
            
            return False;
        }
        $data = DB::table($this->_tabName)
        ->where('type', 1)
        ->get(['slideshow','title','slideshow_url']);

        return $data;
        
    }

  /**
   * 添加轮播图 
   * Author Amber
   * Date 2018-05-08
   * @param  string $slideshow      [图片信息]
   * @param  string $slideshow_urll [将要天转的路径]
   */
    public function slideshow_add($slideshow = '', $title = '', $slideshow_urll = '', $slideshow_type = '')
    {

        $file = $slideshow;
                // print_r($file);die;
        if($file -> isValid()){  
            //检验一下上传的文件是否有效  
            $clientName = $file -> getClientOriginalName(); //获取文件名称  
            $tmpName = $file -> getFileName();  //缓存tmp文件夹中的文件名，例如 php9372.tmp 这种类型的  
            $realPath = $file -> getRealPath();  //

            $entension = $file -> getClientOriginalExtension();  //上传文件的后缀  

            $mimeTye = $file -> getMimeType();  //大家对MimeType应该不陌生了，我得到的结果是 image/jpeg  

            $newName = date('ymdhis').$clientName;
            $path = $file -> move('services',$newName);  
        }
        OSS::publicUpload('mithril-capsule',$newName, $path);// 上传一个文件

        $img = OSS::getPublicObjectURL('mithril-capsule',$newName); // 打印出某个文件的外网链接

            $data['slideshow'] = $img;
            $data['slideshow_url'] = $slideshow_urll;

            $data['type'] = $slideshow_type;
            $data['title'] = $title;

            $into = DB::table($this->_tabName)
                ->insert($data); 

            return $into;
    }

/**
 * 上传图片至素材库
 * Author Amber
 * Date 2018-05-08
 * Params [params]
 * @param  [type] $img [要处理的图片]
 */
    public function actionUpload($img)
    {
            $img_name = $img['name'];
            $url = "./slideshow_img/";//路径
            move_uploaded_file($img['tmp_name'],  $url . rand() . $file['name']);//原路径，新路径
            $str =  $url . rand() . $file['name'];
            return $str;
     
    }
}
