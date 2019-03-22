<?php
namespace app\admin\controller;

use think\App;
use think\Controller;
class Data extends Controller{
    public function index() {
        return $this->fetch();
    }
}