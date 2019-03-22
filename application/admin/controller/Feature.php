<?php
/**
 * Created by PhpStorm.
 * User: Duncan
 * Date: 2019/2/25
 * Time: 16:16
 */
namespace app\admin\controller;
use app\common\model\Featured;
use think\Controller;

class Feature extends Controller {
    private $category_model = null;
    private $feature_model = null;
    private $feature_validate = null;
    public function initialize() {
        parent::initialize();
        $this->category_model = new \app\common\model\Category();
        $this->feature_model = new Featured();
        $this->feature_validate = new \app\admin\validate\Feature();
    }
    public function add() {
        $categories = $this->category_model->getFirstCategorysName(0);
        $this->assign('categories', $categories);
        return $this->fetch();
    }

    public function index($type = 0) {
        if ($type != 0) {
            $type = $type;
        }
        $feature_data = $this->feature_model->getPicByType($type);
        $page = $feature_data->render();
        $this->assign('page', $page);
        $this->assign("type", $type);
        $this->assign('feature', $feature_data);
        return $this->fetch();
    }

    public function save() {
        if (!$this->request->isPost()) {
            $this->error("没有post请求!!");
        }
        $data = input('post.');
        if (!$this->feature_validate->scene('add')->check($data)){
            $this->error($this->feature_validate->getError());
        }

        $res = $this->feature_model->addFeatured($data);
        if (!$res) {
            $this->error("保存失败");
        }

        $this->success("保存成功");
    }

    public function updateStatus() {
        $data = input("get.");
        $res = $this->feature_model->updateFeatureById($data);

        if (!$res) {
            $this->error("操作失败");
        }

        $this->success("操作成功");
    }
}