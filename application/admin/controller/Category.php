<?php
/**
 * Created by PhpStorm.
 * User: Duncan
 * Date: 2019/2/25
 * Time: 16:11
 */

namespace app\admin\controller;
use think\App;
use think\Controller;

class Category extends Controller {
    private $category_model;
    private $category_validate;
    public function __construct(App $app = null)
    {
        parent::__construct($app);
        $this->category_model = new \app\common\model\Category;
        $this->category_validate = new \app\admin\validate\Category;
    }

    public function add() {
       $categories = $this->category_model->getFirstCategorysName();
       $this->assign('categories' ,$categories);
       return $this->fetch();
    }

    /**
     * @return mixe
     * 页面展示
     */
    public function index() {
        $parent_id = input('get.parent_id') ? input('get.parent_id') : 0;
        $categories = $this->category_model->getFirstCategorys($parent_id);
        $page = $categories->render();
        $this->assign('categories' , $categories);
        $this->assign("page", $page);
        return $this->fetch();
    }

    /*
     * 添加分类
     */
    public function save() {
        $data = input('post.');
        $result = $this->category_validate->scene('add')->check($data);
        if (!$result) {
            $this->error($this->category_validata->getError());
            return ;
        }

        $res = $this->category_model->add($data);
        if (!$res) {
            $this->error("保存失败");
            return ;
        }

        $this->success("保存成功");
    }

    /**
     * 编辑
     */
    public function edit($id = 0) {
        if (intval($id) < 1) {
            $this->error("参数不合法");
        }

        $categories = $this->category_model->getFirstCategorysName();
        $category = $this->category_model->get($id);
        $this->assign("categories", $categories);
        $this->assign("category", $category);
        return $this->fetch();
    }

    /**
     * 编辑的操作
     */
    public function update($id) {
        if (!$this->request->isPost()) {
            $this->error("传入参数方式不合法");
        }

        if (intval($id) < 1) {
            $this->error("参数不合法");
        }

        $data = input( 'post.');
        $result = $this->category_validate->check($data);
        if (!$result) {
            $this->error($this->category_validate->getError());
        }

        $data['id'] = $id;
        $res = $this->category_model->updateCategoryById($data);
        if (!$res) {
            $this->error("更新失败");
        }

        $this->success("更新成功");
    }

    /**
     * 排序
     */
    public function listOrder($id, $listorder){
        $data['id'] = $id;
        $data['listorder'] = $listorder;
        $res = $this->category_model->updateCategoryById($data);
        if (!$res) {
            $this->result($_SERVER['HTTP_REFERER'], 0, 'category中的排序出错');
        } else {
            $this->result($_SERVER['HTTP_REFERER'],1,'success');
        }
    }

    /**
     * 修改状态
     */
    public function updateStatus() {
        $data = input("get.");
        $result = $this->category_validate->scene('update')->check($data);
        if (!$result) {
            $this->error($this->category_validate->getError());
        }
        $res = $this->category_model->updateCategoryById($data);

        if (!$res) {
            $this->error("操作失败");
        }

        $this->success("操作成功");
    }

    public function test(){
        $email = new \Phpmailer\Email();
        $result = $email->sendEmail("sun-duncan@qq.com","Eric", "This a test22");
//        $result = $email->test();
        if ($result) {
            return "发送邮件成功";
        } else {
            return "发送邮件失败";
        }
    }
}