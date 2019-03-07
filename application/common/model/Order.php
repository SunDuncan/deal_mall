<?php
/**
 * Created by PhpStorm.
 * User: Duncan
 * Date: 2019/3/5
 * Time: 21:55
 */
namespace app\common\model;
use think\Model;

class Order extends Model {
    protected $autoWriteTimestamp = true;

    public function add($data) {
        $data['status'] = 1;
//        $data['create_time'] = time();
        $result = $this->save($data);
        return $result;
    }
}