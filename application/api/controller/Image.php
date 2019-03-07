<?php
/**
 * Created by PhpStorm.
 * User: Duncan
 * Date: 2019/2/27
 * Time: 16:48
 */

namespace app\api\controller;
use think\Controller;
use think\Request;
use think\File;
class Image extends Controller{
    public function upload() {
        $upload_request = new Request();
        $file = $upload_request->file('file');

        //配置一个图片的目录
        $info = $file->move('upload');
        if ($info && $info->getPathname()) {
            return showStatus(1,' success', "\\". $info->getPathname());
        } else {
            return showStatus(0,'upload_error');
        }
    }
}
