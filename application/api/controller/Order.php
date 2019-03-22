<?php
/**
 * Created by PhpStorm.
 * User: Duncan
 * Date: 2019/3/22
 * Time: 18:59
 */
namespace app\api\controller;
use think\Controller;
class Order extends Controller{
    private $obj = null;
    public function initialize() {
        parent::initialize();
        $this->obj = model('Order');
    }

    public function payStatus() {
        $id = input('post.id', 0, 'intval');
        if (!$id) {
            return showStatus(0, 'error');
        }

        //判定是否登陆
        $order = $this->obj->get($id);
        if ($order->pay_status == 1) {
            return showStatus(1, 'success');
        }

        return showStatus(0, 'error');
    }
}