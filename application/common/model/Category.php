<?php
/**
 * Created by PhpStorm.
 * User: Duncan
 * Date: 2019/2/26
 * Time: 9:25
 */
namespace app\common\model;
use think\Model;

class Category extends Model {
    public function add($data) {
        $data['status'] = 1;
        $data['create_time'] = time();
        return $this->save($data);
    }

    public function getFirstCategorysName($parent_id = 0) {
        $data = [
            'status' => 1,
            'parent_id' => $parent_id
        ];

        $order = [
            'listorder'=> 'desc',
            'id' => 'desc',
        ];
        return $this->where($data)->order($order)->select();
    }

    public function getFirstCategorys($parent_id = 0) {
        $data = [
          ['status','<>', -1],
          ['parent_id', '=', $parent_id]
        ];

        $order = [
            'listorder'=> 'desc',
            'id' => 'desc',
        ];

        $result = $this->where($data)->order($order)->paginate();
        return $result;
    }

    /**
     * 更新编辑操作
     */
    public function updateCategoryById($data) {
        if(is_null($data)) {
            return false;
        }

        $data['update_time'] = time();
        $res = $this->where('id', $data['id'])->update($data);
        return $res;
    }

    /**
     * 通过id查询分类的信息
     */
    public function getCategoryById($id) {
        if (!$id) {
            return false;
        }

        return $this->find($id);
    }

    /**
     * 查询所有有效的分类
     */
    public function getAllCategories() {
        $data['status'] = 1;
        return $this->where($data)->field('id,name,parent_id')->select();
    }

    public function getCategoriesFatherByLimit($limit = 5) {
        $data = [
            'status' => 1,
            'parent_id' => 0
        ];

        $order = [
            'listorder'=> 'desc',
            'id' => 'desc',
        ];
        return $this->where($data)->order($order)->limit(5)->select();
    }
}