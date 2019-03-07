<?php
/**
 * Created by PhpStorm.
 * User: Duncan
 * Date: 2019/2/23
 * Time: 19:53
 */
namespace app\bis\controller;

class Index extends Base {
    public function index() {
        return $this->fetch();
    }


    public function logout(){
        session(null,'bis');
        $this->success("成功退出", url("bis/login/index"));
    }
}