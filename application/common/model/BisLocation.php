<?php
/**
 * Created by PhpStorm.
 * User: Duncan
 * Date: 2019/2/28
 * Time: 10:02
 */
namespace app\common\model;
use think\Model;

class BisLocation extends Model {
    protected $field = true;
    public function addLocation ($data){
        if(empty($data)) {
            return false;
        }

        $data['status'] = 0;
        $this->save($data);
        return $this->id;
    }

    public function getLocationByBisId($id) {
        if (empty($id)) {
            return false;
        }

        $data['bis_id'] = $id;
        $data['is_main'] = 1;
        return $this->where($data)->find();
    }

    /**
     * 更新编辑操作
     */
    public function updateBisById($data) {
        if(is_null($data)) {
            return false;
        }

        $condition_data['is_main'] = $data['is_main'];
        $condition_data['bis_id'] = $data['bis_id'];
        $data['update_time'] = time();
        $res = $this->where($condition_data)->update($data);
        return $res;
    }


    /**
     * 添加分店的信息
     */
    public function addSeBis($data) {
        if (empty($data)) {
            return false;
        }

        $data['create_time'] = time();
        $res = $this->save($data);
        return $res;
    }

    /**
     * @param $id
     * @return array|bool|null|\PDOStatement|string|Model
     * 寻找分店的信息
     */
    public function getLocationByParentId($id) {
        if (empty($id)) {
            return false;
        }

        $data['parent_id'] = $id;
        $data['is_main'] = 0;
        return $this->where($data)->where("status <> -1")->select();
    }

    /**
     * 更新编辑操作
     */
    public function updateBisLocation($data) {
        if(is_null($data)) {
            return false;
        }

        $data['update_time'] = time();
        $res = $this->where('id', $data['id'])->update($data);
        return $res;
    }

    public function getSeLocationByBisId($id) {
        if (empty($id)) {
            return false;
        }

        $data['id'] = $id;
        $data['is_main'] = 0;
        return $this->where($data)->find();
    }


    public function getLocationNames($ids){
        $data = [
            ['id', 'in', $ids],
            ['status', '=', 1]
        ];

        return $this->where($data)->select();
    }
}