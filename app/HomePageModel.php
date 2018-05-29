<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use itbdw\QiniuStorage\QiniuStorage;

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
       // $url = $this->actionUpload($slideshow);//上传媒体库
 $disk = QiniuStorage::disk('qiniu');
    $disk->exists('file.jpg');                      //文件是否存在
    $disk->get('file.jpg');                         //获取文件内容
    $disk->put('file.jpg',$contents);               //上传文件，$contents 二进制文件流
    $disk->prepend('file.log', 'Prepended Text');   //附加内容到文件开头
    $disk->append('file.log', 'Appended Text');     //附加内容到文件结尾
    $disk->delete('file.jpg');                      //删除文件
    $disk->delete(['file1.jpg', 'file2.jpg']);
    $disk->copy('old/file1.jpg', 'new/file1.jpg');  //复制文件到新的路径
    $disk->move('old/file1.jpg', 'new/file1.jpg');  //移动文件到新的路径
    
    $size = $disk->size('file1.jpg');               //取得文件大小
    $time = $disk->lastModified('file1.jpg');       //取得最近修改时间 (UNIX)
    $files = $disk->files($directory);              //取得目录下所有文件
    $files = $disk->allFiles($directory);            //取得目录下所有文件，包括子目录


    //这三个对七牛来说无意义
    $directories = $disk->directories($directory);      //这个也没实现。。。
    $directories = $disk->allDirectories($directory);   //这个也没实现。。。
    $disk->makeDirectory($directory);               //这个其实没有任何作用

    $disk->deleteDirectory($directory);             //删除目录，包括目录下所有子文件子目录
    
    $disk->uploadToken();            //获取上传Token ,可选参数'file.jpg'
    $disk->putFile('file.jpg', 'local/filepath');            //上传本地大文件
    $disk->downloadUrl('file.jpg');            //获取下载地址
    $disk->privateDownloadUrl('file.jpg');     //获取私有bucket下载地址
    $disk->imageInfo('file.jpg');              //获取图片信息
    $disk->imageExif('file.jpg');              //获取图片EXIF信息
    $disk->imagePreviewUrl('file.jpg','imageView2/0/w/100/h/200');              //获取图片预览URL
    $disk->persistentFop('file.flv','avthumb/m3u8/segtime/40/vcodec/libx264/s/320x240');   //执行持久化数据处理
    $disk->persistentStatus($persistent_fop_id);          //查看持久化数据处理的状态。
    $disk->fetch($url, $key);          //从指定URL抓取资源，并将该资源存储到指定空间中。
        $data['slideshow'] = $slideshow;
        $data['slideshow_url'] = $slideshow_urll;
        $data['type'] = $slideshow_type;
        $data['title'] = $title;
        print_r($data);die;
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
