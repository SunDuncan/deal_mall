<?php
/**
 * Created by PhpStorm.
 * User: Duncan
 * Date: 2019/2/27
 * Time: 11:29
 */
namespace app\common\model;
use think\Model;

class City extends Model{
    public function getCitiesByParent_id($parent_id = 0) {
        if (!$parent_id) {
            return false;
        }

        $data = [
            'parent_id' => $parent_id,
            'status' => 1
        ];

        $order = [
            'id' => 'asc'
        ];

        $result = $this->where($data)->order($order)->field('id , name')->select();
        return $result;
    }

    public function getCitiesFather(){
        $data = [
            'parent_id' => 0,
            'status' => 1
        ];

        $order = [
            'id' => 'asc'
        ];

        $result = $this->where($data)->order($order)->field('id , name')->select();
        return $result;
    }

    /**
     * 通过id来获取name
     */
    public function getCityById($id) {
        if (!$id){
            return false;
        }

        return $this->find($id);
    }

    public function getNormalCities($parent_id = 0) {
        $data = [
            ['status','<>', -1],
            ['parent_id' ,'=' ,$parent_id]
        ];

        $order = [
            'listorder' => 'desc',
            'id' => 'desc'
        ];

        return $this->where($data)->order($order)->paginate();
    }

    /**
     * 更新编辑操作
     */
    public function updateCityById($data) {
        if(is_null($data)) {
            return false;
        }

        $data['update_time'] = time();
        $res = $this->where('id', $data['id'])->update($data);
        return $res;
    }

    /**
     * @param $data
     * @return bool
     * 添加城市
     */
    public function add($data) {
        $data['status'] = 1;
        $data['create_time'] = time();
        return $this->save($data);
    }

    /**
     * 获取一些条件性的城市
     */
    public function getCities($data) {
        if (empty($data)) {
            return false;
        }

        return $this->where($data)->select();
    }
}