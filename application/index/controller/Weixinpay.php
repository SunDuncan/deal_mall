<?php
/**
 * Created by PhpStorm.
 * User: Duncan
 * Date: 2019/3/5
 * Time: 23:00
 */
namespace app\index\controller;
use think\Controller;

class Weixinpay extends Controller{
    public function notify(){
        $weixinData = file_get_contents("php://input");
        file_put_contents("/tmp/2.txt", $weixinData, FILE_APPEND);
    }
}