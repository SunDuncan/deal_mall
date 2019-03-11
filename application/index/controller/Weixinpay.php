<?php
/**
 * Created by PhpStorm.
 * User: Duncan
 * Date: 2019/3/5
 * Time: 23:00
 */
namespace app\index\controller;
use think\Controller;
use wxpay\database\WxPayUnifiedOrder;
class Weixinpay extends Controller{
    public function notify(){
        $weixinData = file_get_contents("php://input");
        file_put_contents("/tmp/test/2.txt", $weixinData, FILE_APPEND);
    }

    public function wxpayQCode() {
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
    }
}