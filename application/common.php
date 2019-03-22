<?php
// +----------------------------------------------------------------------
// | ThinkPHP [ WE CAN DO IT JUST THINK ]
// +----------------------------------------------------------------------
// | Copyright (c) 2006-2016 http://thinkphp.cn All rights reserved.
// +----------------------------------------------------------------------
// | Licensed ( http://www.apache.org/licenses/LICENSE-2.0 )
// +----------------------------------------------------------------------
// | Author: 流年 <liu21st@gmail.com>
// +----------------------------------------------------------------------

// 应用公共文件
function status($status) {
    if ($status == 1) {
        $str = "<span class='label label-success radius'>正常</span>";
    } else if ($status == 0) {
        $str = "<span class='label label-danger radius'> 待审</span>";
    } else {
        $str = "<span class='label label-danger radius'>删除</span>";
    }

    echo $str;
}

/**
 * @param $url
 * @param int $type
 * @param array $data
 */
 function doCurl($url, $type=0, $data=[]) {
    $ch = curl_init();//初始化
     curl_setopt($ch, CURLOPT_URL, $url);
     curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
     curl_setopt($ch, CURLOPT_HEADER, 0);

     //这个是post的方式
     if ($type == 1) {
         curl_setopt($ch, CURLOPT_POST, 1);
         curl_setopt($ch, CURLOPT_POSTFIELDS, $data);
     }

     //执行并获取内容
     $output = curl_exec($ch);

     //释放curl句柄
     curl_close($ch);
     return $output;
 }

 /**
  * 返回给前端
  */
 function showStatus($status, $message = '', $data = []) {
     return json([
         'status' => $status,
         'message' => $message,
         'data' => $data
     ]);
 }

 /**
  * 商户入驻申请文案
  */
 function bisRegister($status) {
     if ($status == 1) {
         $str = "入驻申请成功";
     } else if ($status == 0){
        $str = "待审核，审核后平台方会发送邮件通知，请关注邮件";
     } else if ($status == 2) {
         $str = "非常抱歉，您提交的材料不符合条件，请重新提交";
     } else {
         $str = "该申请已经别删除";
     }

     echo $str;
 }

 /**
  * 分页的样式
  */
 function page($page) {
     echo "<div class='cl pd-5 bg-1 bk-gray mt-20 tp5-o2o'>" . $page ." </div>";
 }

/**
 * @param $status
 * 门店的状态
 */
function status_bis($status) {
    if ($status == 1) {
        $str = "<span class='label label-success radius'>上架</span>";
    } else if ($status == 0) {
        $str = "<span class='label label-danger radius'> 下架</span>";
    }

    echo $str;
}

function countLocation($ids){
    if (!$ids) {
        return 1;
    }

    if (preg_match('/,/', $ids)) {
        $arr = explode(',', $ids);
        return count($arr);
    } else {
        return 1;
    }
}

function pay_status($type) {
    if ($type == 1) {
        $str = "<span class='label label-success radius'>已支付</span>";
    } else{
        $str = "<span class='label label-danger radius'>待支付</span>";
    }

    echo $str;
}

/**
 * id 转化为商品名称
 */
function get_deal_name($id){
    $deal_name = model('Deal')->find($id);
    echo $deal_name->name;
}

/**
 * @param $id
 */
function get_category_name($id){
    $deal_name = model('Category')->find($id);
    echo $deal_name->name;
}

/**
 * @param $id
 */
function get_city_name($id){
    $deal_name = model('City')->find($id);
    echo $deal_name->name;
}
