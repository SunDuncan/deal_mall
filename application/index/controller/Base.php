<?php
/**
 * Created by PhpStorm.
 * User: Duncan
 * Date: 2019/3/4
 * Time: 15:33
 */

namespace app\index\controller;

use app\common\model\Category;
use app\common\model\City;
use think\Controller;

class Base extends Controller
{
    /**
     * 获取用户的信息
     */
    private $hot_cities = null;
    private $city_model = null;
    protected $account = null;
    private $category_model = null;
    private $categories = null;
    protected $city_id = null;

    public function initialize()
    {
        parent::initialize();
        $this->city_model = new City();
        $this->category_model = new Category();
        $this->categories = $this->mixCategroy();
        $this->getHotCities();
        $this->getUserInfo();
        $this->assign("controller", strtolower($this->request->controller()));
        $default_name = empty(input("get.name")) ? '南京' : input("get.name");
        $this->city_id = empty(input("get.city_id")) ? '17' : input("get.city_id");
        $this->assign('city_id', $this->city_id);
        $this->assign("city_name", $default_name);
        $this->assign("title", "o2o团购网");
        $this->assign("categories", $this->categories);
        $this->assign('user_info', $this->account);
        $this->assign('hot_cities', $this->hot_cities);
    }

    public function getHotCities()
    {
        $hot_cities = $this->city_model->getCities(['is_default' => 1, 'status' => 1]);
        $this->hot_cities = $hot_cities;
    }

    public function getUserInfo()
    {
        $this->account = session('index_user', '', 'index');
    }

    /**
     * 1、获取父分类n个
     */
    public function getCategoriesFather($limit)
    {
        return $this->category_model->getCategoriesFatherByLimit($limit);
    }

    /**
     * 2、通过父类获取子类
     */

    public function getCategoriesChild($parent_id)
    {
        return $this->category_model->getFirstCategorysName($parent_id);
    }

    /**
     * 封装一个id=>name的category表
     */
    public function getCategroyIdName()
    {
        $categories = $this->category_model->getAllCategories();
        $category_index = [];
        foreach ($categories as $key) {
            $category_index[$key->id] = $key->name;
        }

        return $category_index;
    }

    /**
     * 封装一个有一级和二级分类的分类
     */
    public function mixCategroy()
    {
        $category = [];
        $categories = [];
        $father_categories = $this->getCategoriesFather(5);
        foreach ($father_categories as $key) {
            $child_categories = $this->getCategoriesChild($key->id);
            $category['id'] = $key->id;
            $category['name'] = $key->name;
            $category['se_categories'] = $child_categories;
            array_push($categories, $category);
        }

        return $categories;
    }
}