<?php
/**
 * Created by PhpStorm.
 * User: Duncan
 * Date: 2019/3/3
 * Time: 18:28
 */

namespace app\bis\validate;

use think\Validate;

class Deal extends Validate
{
    protected $rule = [
        'name' => 'require|max:25',
        'city_id' => 'require',
        'category_id' => 'require',
        'image' => 'require',
        'start_time' => 'require',
        'end_time' => 'require',
        'total_count' => 'require',
        'origin_price' => 'require',
        'coupons_begin_time' => 'require',
        'coupons_end_time' => 'require',
        'current_price' => 'require'
    ];

    protected $scene = [
        'add' => ['name', 'city_id', 'category_id', 'total_count', 'origin_price']
    ];
}