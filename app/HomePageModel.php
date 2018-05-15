<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

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
        ->get(['slideshow','slideshow_url']);

        return $data;
        
    }

  /**
   * 添加轮播图 
   * Author Amber
   * Date 2018-05-08
   * @param  string $slideshow      [图片信息]
   * @param  string $slideshow_urll [将要天转的路径]
   */
    public function slideshow_add($slideshow = '', $slideshow_urll = '', $slideshow_type = '')
    {
       // $url = $this->actionUpload($slideshow);//上传媒体库

        $data['slideshow'] = $slideshow;
        $data['slideshow_url'] = $slideshow_urll;
        $data['type'] = $slideshow_type;

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
