<?php
/**
 * Created by PhpStorm.
 * User: Duncan
 * Date: 2019/3/5
 * Time: 14:20
 */
namespace app\index\controller;
use think\Controller;

class Map extends Controller{
    public function getImage($data){
        $map = new \Map\Map();
        $res = $map->staticImage($data);
        return $res;
    }
}