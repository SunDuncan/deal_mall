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
        $data = input("get.");
        $select_data = [];
        if (!empty($data['datemin'])) {
            $select_data[] = ['create_time' ,'>' , strtotime($data['datemin'])];
        }
        if (!empty($data['datemax'])) {
            $select_data[] = ['create_time' ,'<' , strtotime($data['datemax'])];
        }

        if (!empty($data['datemin']) && !empty($data['datemax'])) {
            $this->error("日期范围出错");
        }

        if(!empty($data['name'])){
            $data['username'] = $data['name'];
            $select_data[] = ['username' ,'like' , "%". $data['username']. "%"];
        }

        $order_info = $this->order_model->getOrders($select_data);
        $page = $order_info->render();
        $this->assign("datemin", empty($data['datemin'])? '' : $data['datemin']);
        $this->assign('datemax',empty($data['datemax'])? '' : $data['datemax']);
        $this->assign('username',empty($data['username'])? '' : $data['username']);
        $this->assign('page', $page);
        $this->assign('orders', $order_info);
        return $this->fetch();
    }

    public function detail() {
        return $this->fetch();
    }

}