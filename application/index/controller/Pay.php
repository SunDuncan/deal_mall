<?php
/**
 * Created by PhpStorm.
 * User: Duncan
 * Date: 2019/2/23
 * Time: 20:04
 */
namespace app\index\controller;
use think\Controller;

class Pay extends Base {
    public function index() {
        return $this->fetch();
    }

    public function paysuccess() {
        return $this->fetch();
    }
}