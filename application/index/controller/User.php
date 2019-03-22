<?php
/**
 * Created by PhpStorm.
 * User: Duncan
 * Date: 2019/2/23
 * Time: 20:03
 */
namespace app\index\controller;
use think\captcha\Captcha;
use think\Controller;

class User extends Controller {
    private $captcha = null;
    private $validate = null;
    private $user_model = null;
    public function initialize() {
        parent::initialize(); // TODO: Change the autogenerated stub
        $this->captcha = new Captcha();
        $this->validate = new \app\index\validate\User();
        $this->user_model = new \app\common\model\User();
    }

    public function login() {
        $user_info = session('index_user','', 'index');
        if (empty($user_info)) {
            return $this->fetch();
        }
        return $this->redirect(url("index/index"));

    }

    public function register() {
        if ($this->request->isPost()) {
            $data = input('post.');

            if (!$this->captcha->check($data['verifycode'])) {
                $this->error("验证码错误");
            }

            $res_validate = $this->validate->scene('add')->check($data);
            if (!$res_validate) {
                $this->error($this->validate->getError());
            }

            //1、检查密码是否重复
            if ($data['repassword'] != $data['password']) {
                $this->error("两次输入的密码不一致");
            }

            //2、检查用户名是否已经存在
            try {
                $res = $this->user_model->getSingleUserInfo(['username' => $data['username']]);
            }catch (\Exception $e) {
                $this->error($e->getMessage());
            }

            if ($res) {
                $this->error("用户名已经存在");
            }
            try {
                $res = $this->user_model->getSingleUserInfo(['email' => $data['email']]);
            }catch (\Exception $e) {
                $this->error($e->getMessage());
            }
            if ($res) {
                $this->error("邮箱已经存在");
            }
            $post_data['username'] = $data['username'];
            $post_data['code'] = mt_rand(100, 10000);
            $post_data['password'] = md5($data['password'] . $post_data['code']);
            $post_data['email'] = $data['email'];

            $res = $this->user_model->addUser($post_data);
            if (!$res) {
                $this->error("注册失败");
            } else {
                $this->success("注册成功", url('index/user/login'));
            }
        } else {
            return $this->fetch();
        }
    }

    public function logincheck() {
        if ($this->request->isPost()) {
            $data = input('post.');
            $res_validate = $this->validate->scene('login')->check($data);
            if (!$res_validate) {
                $this->error($this->validate->getError());
            }
            $user_info = $this->user_model->getSingleUserInfo(['username'=> $data['username']]);
            if (empty($user_info)) {
                $this->error("用户名错误或密码错误");
            }
            if (md5($data['password'] . $user_info['code'])!= $user_info['password']) {
                $this->error("用户名错误或密码错误");
            }

            $update_data['update_time'] = time();
            $update_data['id'] = $user_info['id'];
            $update_data['last_login_time'] = time();
            $update_data['last_login_ip'] = $_SERVER['REMOTE_ADDR'];
            if (!$res_update = $this->user_model->updateUser($update_data)) {
                $this->error("登陆失败");
            }
            session("index_user",$user_info, 'index');
            $this->success("登录成功", url("index/index"));
        }else {
            $this->error("传入的方式不是post");
        }
    }


    public function logout() {
        session(null, 'index');
        $this->success("退出成功", url('user/login'));
    }
}