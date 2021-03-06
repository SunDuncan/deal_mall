<?php
/**
 * Created by PhpStorm.
 * User: Duncan
 * Date: 2019/2/23
 * Time: 19:55
 */
namespace app\bis\controller;
use app\bis\validate\BisAccount;
use think\Controller;
class Login extends Controller{
    private $validate = null;
    private $bis_account_model = null;

    public function initialize()
    {
        parent::initialize(); // TODO: Change the autogenerated stub
        $this->validate = new BisAccount();
        $this->bis_account_model = new \app\common\model\BisAccount();

    }

    public function index() {
//判断登陆的逻辑
        if ($this->request->isPost()) {
            $data = input("post.");
            $validate_res = $this->validate->scene("login")->check($data);
            if (!$validate_res) {
                $this->error($this->validate->getError());
            }

            //检查是否有这个用户
            $check_data['username'] = $data['username'];
            $check_data['status'] = 1;
            $is_exist = $this->bis_account_model->getBisAccount($check_data);
            if (empty($is_exist)) {
                $this->error("用户不存在，或者未审核成功");
            }

            if (md5($data['password'] . $is_exist['code']) != $is_exist['password']) {
                $this->error("用户名或密码错误");
            }

            session("account_info", $is_exist, "bis");
            $this->success("登陆成功", url("index/index"));
        } else {
            return $this->fetch();
        }
    }

}