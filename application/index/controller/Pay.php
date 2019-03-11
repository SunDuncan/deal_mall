<?php
/**
 * Created by PhpStorm.
 * User: Duncan
 * Date: 2019/2/23
 * Time: 20:04
 */
namespace app\index\controller;
use wxpay\database\WxPayUnifiedOrder;
use wxpay\NativePay;
class Pay extends Base {
    public function index() {
        if (!$this->account) {
            $this->error("请先登录,亲", url('user/login'));
        }

        $order_id = input("get.id", 0 ,'intval');
        if (empty($order_id)){
            $this->error("请求不合法");
        }

        $order_info = model('order')->find($order_id);
        if (empty($order_info) || $order_info->status != 1 || $order_info->pay_status != 0) {
            $this->error("无法进行该项操作");
        }

        if ($order_info->username != $this->account->username) {
            $this->error("订单不是本人操作");
        }

        $deal = model('Deal')->find($order_info->deal_id);

        //生成二维码
        $notify = new NativePay();
        $input = new WxPayUnifiedOrder();
        $input->setBody("支付0.01元");
        $input->setAttach("支付0.01元");
        $input->setOutTradeNo(WxPayConfig::MCHID.date("YmdHis"));
        $input->SetTotalFee("1");
        $input->SetTimeStart(date("YmdHis"));
        $input->SetTimeExpire(date("YmdHis", time() + 600));
        $input->SetGoodsTag("test");
        $input->SetNotifyUrl("http://o2o.ducnan.cn/index/weixinpay/notify");
        $input->SetTradeType("NATIVE");
        $input->SetProductId("123456789");
        $result = $notify->GetPayUrl($input);
        $url2 = $result["code_url"];
        $this->assign("url", $url2);
        $this->assign("order", $order_info);
        $this->assign("deal", $deal);
        return $this->fetch();
    }

    public function paysuccess() {
        return $this->fetch();
    }
}