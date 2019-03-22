<?php
/**
 * Created by PhpStorm.
 * User: Duncan
 * Date: 2019/3/22
 * Time: 11:05
 */

namespace app\admin\controller;

use think\Controller;

class Order extends Controller {
    protected $order_model = null;
    public function initialize(){
        parent::initialize();
        $this->order_model =  new \app\common\model\Order();
    }
    public function index() {
        $order_info = $this->order_model->getOrders();
        $this->assign('orders', $order_info);
        return $this->fetch();
    }

    public function detail() {
        return $this->fetch();
    }

}