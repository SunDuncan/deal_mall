<?php
/**
 * Created by PhpStorm.
 * User: Duncan
 * Date: 2019/2/27
 * Time: 15:14
 */
namespace app\api\controller;
use think\App;
use think\Controller;
use app\common\model;
class Category extends Controller {
    private $category_model = null;
    public function __construct(App $app = null)
    {
        parent::__construct($app);
        $this->category_model = new model\Category();
    }

    public function getChildCategory() {
        $parent_id = input('post.id') ? input('post.id') : 0;
        if (!$parent_id) {
            return showStatus(0, "参数传的有误");
        }
        $categories = $this->category_model->getFirstCategorysName($parent_id);

        if (!$categories) {
            showStatus(0, "没有数据");
        }
        return showStatus(1,"获取成功", $categories);
    }
}