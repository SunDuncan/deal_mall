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
use wxpay\database\WxPayResults;
class Weixinpay extends Controller{
    public function notify(){
        $weixinData = file_get_contents("php://input");
        try {
            $resultObj = new WxPayResults();
            $weixinData = $resultObj->Init($weixinData);

        }catch(\Exception $e) {
            $resultObj->setData('return_code', 'FAIL');
            $resultObj->setData('return_msg', $e->getMessage());
            return $resultObj->toXml();
        }
        if ($weixinData['return_code'] === 'FAIL' || $weixinData['result_code'] != 'SUCCESS') {
            $resultObj->setData('return_code', 'FAIL');
            $resultObj->setData('return_msg', "错误");
            return $resultObj->toXml();
        }

        $outTradeTo = $weixinData['out_trade_to'];
        $order = model('Order')->get(['out_trade_to' => $outTradeTo]);
        if (!$order || $order->pay_status  == 1) {
            $resultObj->setData('return_code', 'SUCCESS');
            $resultObj->setData('return_msg', "OK");
            return $resultObj->toXml();
        }

        //更新表 订单表 商品表
        try {
            $order_res = model('Order')->updateOrderByOutTradeNo($outTradeTo, $weixinData);
            model('Deal')->updateBuyCountById($order->deal_id, $order->deal_count);
        }catch (\Exception $e){
            $resultObj->setData('return_code', 'Error');
            $resultObj->setData('return_msg', "OK");
            return $resultObj->toXml();
        }

        $resultObj->setData('return_code', 'SUCCESS');
        $resultObj->setData('return_msg', "OK");
        return $resultObj->toXml();

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