<?php
/**
 * Created by PhpStorm.
 * User: Duncan
 * Date: 2019/3/4
 * Time: 23:53
 */
namespace app\admin\validate;
use think\Validate;

class Feature extends Validate{
    protected $rule=[
        'title' => 'require',
        'image' => 'require',
        'url' => 'require',
        'description' => 'require'
    ];

    protected $scene = [
        'add' => ['title', 'image', 'url', 'description']
    ];
}