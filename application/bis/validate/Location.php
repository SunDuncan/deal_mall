<?php
/**
 * Created by PhpStorm.
 * User: Duncan
 * Date: 2019/3/3
 * Time: 19:46
 */
namespace app\bis\validate;
use think\Validate;

class Location extends Validate{
    protected $rule = [
        'name' => 'require',
        'city_id' => 'require',
        'se_city_id' => 'require',
        'logo' => 'require',
        'category_id' => 'require',
        'address' => 'require',
        'tel' => 'mobile',
        'contact' => 'require',
        'open_time' => 'require'
    ];

    protected $scene = [
        'add' => ['name', 'city_id', 'se_city_id', 'logo', 'category_id', 'address', 'tel', 'contact', 'open_time']
    ];
}