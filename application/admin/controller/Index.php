<?php
/**
 * Created by PhpStorm.
 * User: Duncan
 * Date: 2019/2/23
 * Time: 19:02
 */
namespace app\admin\controller;
use think\Controller;
class Index extends Controller {
    public function index() {
        return $this->fetch();
    }

    public function welcome() {
        return "欢迎来到团购网后台系统";
    }

    /**
     * 做测试
     */
    public function test() {
        $test = new \Map\Map();
        $test->getLatLong("江苏省无锡市滨湖区胡埭镇");
    }
}