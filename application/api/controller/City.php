<?php
/**
 * Created by PhpStorm.
 * User: Duncan
 * Date: 2019/2/27
 * Time: 14:30
 */

namespace app\api\controller;
use think\App;
use think\Controller;
use app\common\model;
class City extends Controller {
    private $city_model = null;
    public function __construct(App $app = null)
    {
        parent::__construct($app);
        $this->city_model = new model\City();
    }

    public function getChildCities() {
        $parent_id = input('post.id');

        if (!$parent_id) {
            showStatus(0, "参数传的有误");
        }

        $cityChildData = $this->city_model->getCitiesByParent_id($parent_id);
        if (!$cityChildData) {
            showStatus(0, "没有数据");
        }

        return showStatus(1,"获取成功", $cityChildData);

    }
}