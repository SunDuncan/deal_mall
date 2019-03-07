<?php
/**
 * Created by PhpStorm.
 * User: Duncan
 * Date: 2019/3/4
 * Time: 13:45
 */
namespace app\index\validate;
use think\Validate;

class User extends Validate{
    protected $rule = [
        'username' => 'require',
        'email' => 'email',
        'password' => 'require',
        'verifycode' => 'require'
    ];

    protected $scene = [
        'add' => ['username', 'email', 'password', 'verifycode'],
        'login' => ['username', 'password']
    ];
}