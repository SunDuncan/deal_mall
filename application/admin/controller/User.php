<?php
/**
 * Created by PhpStorm.
 * User: Duncan
 * Date: 2019/3/22
 * Time: 11:12
 */
namespace app\admin\controller;
use think\Controller;
class User extends Controller {
    protected $user_model = null;
    public function initialize() {
        parent::initialize();
        $this->user_model = new \app\common\model\User();
    }


    public function index() {
        $user_info = $this->user_model->getUsers();
        $this->assign('users', $user_info);
        return $this->fetch();
    }
}