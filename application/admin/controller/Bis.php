<?php
/**
 * Created by PhpStorm.
 * User: Duncan
 * Date: 2019/2/25
 * Time: 16:13
 */
namespace app\admin\controller;
use app\common\model\BisAccount;
use app\common\model\BisLocation;
use app\common\model\City;
use Phpmailer\Email;
use think\App;
use think\Controller;

class Bis extends Controller {
    private $bis_model = null;
    private $bis_account_mdoel = null;
    private $bis_location_model = null;
    private $city_model = null;
    private $category_model = null;
    private $mail = null;
    public function __construct(App $app = null)
    {
        parent::__construct($app);
        $this->bis_model = new \app\common\model\Bis();
        $this->bis_location_model = new BisLocation();
        $this->bis_account_mdoel = new BisAccount();
        $this->city_model = new City();
        $this->category_model = new \app\common\model\Category();
        $this->mail = new Email();
    }

    public function apply () {
        $bis_data = $this->bis_model->getBisByStatus(0);
        $page = $bis_data->render();
        $this->assign("bis_data", $bis_data);
        $this->assign("page", $page);
        return $this->fetch();
    }

    public function dellist() {
        $bis_info = $this->bis_model->getBisByStatus(-1);
        $this->assign("bis_info", $bis_info);
        return $this->fetch();
    }

    public function index() {
        $bis_info = $this->bis_model->getBisByStatus(1);
        $this->assign("bis_info", $bis_info);
        return $this->fetch();
    }

    public function detail($id) {
        if (empty($id)) {
            $this->error("参数未接收到");
        }

        $bis_data = $this->bis_model->getBisById($id);
        $bis_account_data = $this->bis_account_mdoel->getAccountByBisId($bis_data['id']);
        $bis_location_data = $this->bis_location_model->getLocationByBisId($bis_data['id']);
        $city = $this->city_model->getCityById($bis_data['city_id']);
        $city_path = $bis_data['city_path'];

        if (preg_match("/,/", $city_path)) {
            $child_data = explode(",", $city_path);
            $child_id = $child_data[1];
        } else {
            $child_id = $city_path;
        }

        $child_data_info = $this->city_model->getCityById($child_id);
        $category_data = $this->category_model->getCategoryById($bis_location_data['category_id']);
        $category_path = explode(",", $bis_location_data['category_path']);
        $se_category = [];
        if (!empty($category_path[1])) {
            foreach ($category_path as $key=>$value){
                if ($key != 0) {
                    array_push($se_category, $this->category_model->getCategoryById($value));
                }
            }
        }
        $description = html_entity_decode($bis_data['description']);
        $introduction = html_entity_decode($bis_location_data['content']);
        $this->assign("description", $description);
        $this->assign("se_category", $se_category);
        $this->assign("city", $city);
        $this->assign('bis_data', $bis_data);
        $this->assign("introduction", $introduction);
        $this->assign("se_city", $child_data_info);
        $this->assign("bis_account_data", $bis_account_data);
        $this->assign("bis_location_data", $bis_location_data);
        $this->assign("category_data", $category_data);
        return $this->fetch();
    }

    /**
     * 修改状态
     */
    public function updateStatus() {
        $data = input("get.");
        //商户表的更改状态
        $bis_res = $this->bis_model->updateBisById($data);
        //商户总店信息
        $location_data['bis_id'] = $data['id'];
        $location_data['is_main'] = 1;
        $location_data['status'] = $data['status'];
        $bis_location_res = $this->bis_location_model->updateBisById($location_data);

        $account_data['bis_id'] = $data['id'];
        $account_data['is_main'] = 1;
        $account_data['status'] = $data['status'];
        $bis_account_data = $this->bis_account_mdoel->updateBisById($account_data);

        if (!$bis_location_res && !$bis_account_data && !$bis_res) {
            $this->error("操作失败");
        }

        if ($data['status'] == 1 ) {
            $str = "恭喜你，申请已经审核成功。";
        }else {
            $str = "抱歉你的账号申请被驳回，请资讯服务台。。。";
        }

        $bis_data = $this->bis_model->getBisById($data['id']);
        $title = "ApplyResult";
        $res = $this->mail->sendEmail($bis_data['email'], $title, $str);
        if (!$res) {
            $this->error("邮件未发送");
        }

        $this->success("操作成功,邮件已发送");
    }
}