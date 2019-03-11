<?php
/**
 * Created by PhpStorm.
 * User: Duncan
 * Date: 2019/2/23
 * Time: 20:05
 */

namespace app\index\controller;

class Order extends Base {
    public function buy() {
        $this->assign("controller", '');
        return $this->fetch();
    }

    public function confirm() {
        if (!$this->account) {
            $this->error("请先登录,亲", url('user/login'));
        }

        $id = input('get.id', 0, 'intval');
        if (!$id) {
            $this->error("参数不合法");
        }

        $count = input("get.count", 1, 'intval');
        $deal = model('Deal')->find($id);
        if (!$deal || $deal->status != 1){
            $this->error("商品不存在");
        }
        $this->assign("count", $count);
        $this->assign("deal", $deal);
        return $this->fetch();
    }

    public function index() {
        if (!$this->account) {
            $this->error("请先登录,亲", url('user/login'));
        }
        $id = input('get.id', 0, 'intval');
        if (!$id) {
            $this->error("参数不合法");
        }

        $count = input("get.deal_count", 0, 'intval');
        $price = input("get.deal_price", 0, 'intval');
        $deal = model('Deal')->find($id);
        if (!$deal || $deal->status != 1){
            $this->error("商品不存在");
        }

        if (empty($_SERVER['HTTP_REFERER'])) {
            $this->error("请求不合法");
        }

        $orderSn = $this->setOrderSn();
        $data = [
            'out_trade_no' => $orderSn,
            'user_id' => $this->account->id,
            'username' => $this->account->username,
            'deal_id' => $id,
            'deal_count' => $count,
            'total_price' => $price,
            'referer' => 'HTTP_REFERER'
        ];

        $order_model = new \app\common\model\Order();
        try {
            $order_id = $order_model->add($data);
        }catch (\Exception $e) {
            $this->error($e->getMessage());
        }
        if (!$order_id) {
            $this->error("订单处理失败");
        }
        $this->redirect(url('pay/index', ['id' => $order_id]));
    }

    /**
     * 设置订单号
     */
    public function setOrderSn() {
        list($t1, $t2) = explode(" ", microtime());
        $t3 = explode(".", $t1 * 10000);
        return $t2 . $t3[0] . (rand(10000, 99999));
    }
}