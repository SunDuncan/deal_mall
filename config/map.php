<?php
/**
 * Created by PhpStorm.
 * User: Duncan
 * Date: 2019/2/26
 * Time: 16:15
 */
//关于百度的地图的基础的配置
//http://api.map.baidu.com/geocoder/v2/?address=
//北京市海淀区上地十街10号&output=json&ak=您的ak&callback=showLocation //GET请求
return [
    'ak' => 'ORP5CI2Po9ahWP4BG9S2V5SOQubQC9uj',
    'baidu_map_url' => 'http://api.map.baidu.com/',
    'geocoder' => 'geocoder/v2/',
    'width' => 400,
    'height' => 300,
    'staticimage' => 'staticimage/v2'
];