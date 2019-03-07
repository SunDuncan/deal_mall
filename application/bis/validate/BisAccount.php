<?php
/**
 * Created by PhpStorm.
 * User: Duncan
 * Date: 2019/3/1
 * Time: 9:13
 */
namespace app\bis\validate;
use think\Validate;

class BisAccount extends Validate {
    protected $rule = [
            'username' => 'require|max:25',
            'password' => 'require'
        ];

    protected $scene = [
        'login' => ['username', 'password']
    ];
}