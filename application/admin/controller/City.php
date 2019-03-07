<?php
/**
 * Created by PhpStorm.
 * User: Duncan
 * Date: 2019/3/2
 * Time: 17:24
 */

namespace app\admin\controller;

use think\App;
use think\Controller;

class City extends Controller
{
    private $city_model = null;
    private $city_validate = null;

    public function initialize()
    {
        parent::initialize(); // TODO: Change the autogenerated stub
        $this->city_model = new \app\common\model\City();
        $this->city_validate = new \app\admin\validate\City();
    }

    public function index()
    {
        $parent_id = input('get.parent_id') ? input('get.parent_id') : 0;
        $cities = $this->city_model->getNormalCities($parent_id);
        $page = $cities->render();
        $this->assign("id", $parent_id);
        $this->assign("cities", $cities);
        $this->assign("page", $page);
        return $this->fetch();
    }

    /**
     * 修改状态
     */
    public function updateStatus()
    {
        $data = input("get.");
        $result = $this->city_validate->scene('update')->check($data);
        if (!$result) {
            $this->error($this->category_validate->getError());
        }
        $res = $this->city_model->updateCityById($data);

        if (!$res) {
            $this->error("操作失败");
        }

        $this->success("操作成功");
    }

    /**
     * 添加城市的控制器
     */
    public function add()
    {
        $cities = $this->city_model->getNormalCities(0);
        $this->assign("cities", $cities);
        return $this->fetch();
    }

    public function save()
    {
        $data = input('post.');
        $result = $this->city_validate->scene('add')->check($data);
        if (!$result) {
            $this->error($this->category_validata->getError());
            return;
        }

        $res = $this->city_model->add($data);
        if (!$res) {
            $this->error("保存失败");
            return;
        }

        $this->success("保存成功");
    }

    /**
     * 排序
     */
    public function listOrder($id, $listorder){
        $data['id'] = $id;
        $data['listorder'] = $listorder;
        $res = $this->city_model->updateCityById($data);
        if (!$res) {
            $this->result($_SERVER['HTTP_REFERER'], 0, 'city中的排序出错');
        } else {
            $this->result($_SERVER['HTTP_REFERER'],1,'success');
        }
    }

    public function edit($id) {
        if (intval($id) < 1) {
            $this->error("参数不合法");
        }

        $cities = $this->city_model->getCitiesFather();
        $city = $this->city_model->get($id);
        $this->assign("cities", $cities);
        $this->assign("city", $city);
        return $this->fetch();
        return $this->fetch();
    }
}