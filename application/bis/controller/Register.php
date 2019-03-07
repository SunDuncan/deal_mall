<?php
/**
 * Created by PhpStorm.
 * User: Duncan
 * Date: 2019/2/23
 * Time: 19:58
 */
namespace app\bis\controller;
use app\bis\validate\Bis;
use app\common\model\BisAccount;
use app\common\model\BisLocation;
use app\common\model\Category;
use Map\Map;
use Phpmailer\Email;
use think\App;
use think\Controller;
use app\common\model\City;


class Register extends Controller {
    private $city_model = null;
    private $categories_model = null;
    private $bis_validate = null;
    private $map = null;
    private $bis_model = null;
    private $bis_account_model = null;
    private $bis_location_model = null;
    private $email = null;
    public function initialize()
    {
        parent::initialize();
        $this->city_model = new City();
        $this->categories_model = new Category();
        $this->bis_validate = new Bis();
        $this->map = new Map();
        $this->bis_model = new \app\common\model\Bis();
        $this->bis_account_model = new BisAccount();
        $this->bis_location_model = new BisLocation();
        $this->email = new Email();
    }

    public function index() {
        $cities_data = $this->city_model->getCitiesFather();
        //获取分类的一级目录
        $categories_data = $this->categories_model->getFirstCategorysName();
        $this->assign("cities_data", $cities_data);
        $this->assign('categories', $categories_data);
        return $this->fetch();
    }

    public function waiting($id) {
        if (empty($id)) {
            $this->error("失败");
        }

        $detail = $this->bis_model->getBisById($id);
        $this->assign("detail", $detail);
        return $this->fetch();
    }

    /**
     * 增加一个商户登陆的验证
     */
    public function add() {
        if (!$this->request->isPost()) {
            $this->error("请求错误");
        }

        //获取表单的值
        $data = input('post.', '', 'htmlentities');
//        //校验数据
        $res = $this->bis_validate->scene('add')->check($data);
        if (!$res) {
            $this->error($this->bis_validate->getError());
        }
        //判断用户是否存在
        $user_is_exist = $this->bis_account_model->isAccountExist($data['name']);
        if ($user_is_exist) {
            $this->error("此用户名已存在,请重新分配");
        }

        //获取经纬度
        $location_lang = $this->map->getLatLong($data['address']);
        $location_result = $location_lang->result;
        if (empty($location_lang) || $location_lang->status != 0 || $location_result->precise != 1){
            $this->error("无法获取数据，或者匹配的地址不准确.");
        }

        //信息入库
        $bis_data = [
            'name' => $data['name'],
            'city_id' => $data['city_id'],
            'city_path' => empty($data['se_city_id']) ? $data['city_id'] : $data['city_id'] . "," . $data['se_city_id'],
            'logo' => $data['logo'],
            'licence_logo' => $data['licence_logo'],
            'description' => empty($data['description']) ? '' : $data['description'],
            'bank_info' => $data['bank_info'],
            'bank_user' => $data['bank_user'],
            'bank_name' => $data['bank_name'],
            'faren' => $data['faren'],
            'faren_tel' => $data['faren_tel'],
            'email' => $data['email']
        ];

        $bis_id = $this->bis_model->addBis($bis_data);
        //总店的相关信息校验

        $data['cat'] = '';
        if(!empty($data['se_category_id'])) {
            $data['cat'] = implode('|', $data['se_category_id']);
        }
        //总店的信息入库
        $location_data = [
            'bis_id' => $bis_id,
            'name' => $data['name'],
            'tel' => $data['tel'],
            'contact' => $data['contact'],
            'category_id' => $data['category_id'],
            'category_path' => empty($data['category_id'])? $data['category_id'] : $data['category_id'] . ',' . $data['cat'],
            'city_id' => $data['city_id'],
            'city_path' => empty($data['se_city_id']) ? $data['city_id'] : $data['city_id'] . ',' .$data['se_city_id'],
            'address' => $data['address'],
            'open_time' => $data['open_time'],
            'content' => empty($data['content']) ? '' : $data['content'],
            'is_main' => 1,
            'xpoint' => empty($location_result->location->lng) ? '' : $location_result->location->lng,
            'ypoint' => empty($location_result->location->lat) ? '' : $location_result->location->lat,
        ];
        //账户相关信息校验

        //账户信息的入库
        $location_id = $this->bis_location_model->addLocation($location_data);

        //加盐
        $data['code'] = mt_rand(100, 10000);
        $account_data = [
            'bis_id' => $bis_id,
            'username' => $data['username'],
            'password' => md5($data['password']. $data['code']),
            'code' => $data['code'],
            'is_main' => 1
        ];
        $account_id = $this->bis_account_model->addLocation($account_data);
        if (!$account_id) {
            $this->error("申请失败");
        }

        $url = $this->request->domain().url('bis/register/waiting', ['id' => $bis_id]);
        //发送邮件
        $title = "o2o入驻申请通知";
        $content = "您提交的入驻申请需等待平台方审核，你可以通过点击链接<a href='" . $url . "' target='_blank'>查看链接</a> " . "查看审核状态";
        $email_res = $this->email->sendEmail($data['email'], $title, $content);
        if(!$email_res) {
            $this->error("邮箱验证发送失败");
        }

        $this->success("申请成功，等待审核", url('register/waiting', ['id' => $bis_id]));
    }
}