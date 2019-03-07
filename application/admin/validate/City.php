<?php
/**
 * Created by PhpStorm.
 * User: Duncan
 * Date: 2019/3/2
 * Time: 17:48
 */
namespace app\admin\validate;
use think\Validate;

class City extends Validate{
    protected $rule = [
        'name' => 'require|max:10',
        'parent_id' => 'number',
        'uname'     => 'require',
        'id' => 'number',
        'status' => 'number|in:-1,0,1',
        'listorder' => 'number'
    ];

    protected $message = [
        'name.require' => '名称必须',
        'name.max' => '名称不要超过10个字符',
        'status.number' => '状态必须是数字',
        'status.in' => '状态超出范围'
    ];

    protected $scene = [
        'add' => ['name', 'parent_id'],
        'index' => ['status', 'listorder'],
        'update' => ['id', 'status','listorder']
    ];
}