<?php
/**
 * Created by PhpStorm.
 * User: Duncan
 * Date: 2019/2/26
 * Time: 16:11
 */
namespace Map;
class Map{
    /**
     * 根据地址来获取经纬度
     */
    //http://api.map.baidu.com/geocoder/v2/?address=
    //北京市海淀区上地十街10号&output=json&ak=您的ak&callback=showLocation //GET请求
    public function getLatLong($address) {
        if (!$address) {
            return '';
        }
        /**
         * 先获取数据
         */
        $data = [
            'address' => $address,
            'ak' => config('map.ak'),
            'output' => 'json'
        ];


        $url = config('map.baidu_map_url') . config('map.geocoder'). '?' .http_build_query($data);
        //1、 file_get_contends
        //2、 curl
        /**
         * url的请求
         */
        $result = doCurl($url);
        if ($result) {
            return json_decode($result);
        } else {
            return [];
        }
    }

    /**
     * 获取静态图
     */
    //http://api.map.baidu.com/staticimage/v2
    public function staticImage($center) {
        if (!$center) {
            return '';
        }
        $data = [
            'ak' => config('map.ak'),
            'width' => config('map.width'),
            'height' => config('map.height'),
            'center' => $center,
            'markers' => $center
        ];

        $url = config('map.baidu_map_url') . config('map.staticimage') . '?' . http_build_query($data);
        $result = doCurl($url);
        return $result;
    }


    /**
     * 根据经纬度来获取地址
     */
}