<?php
/**
 * Created by PhpStorm.
 * User: Duncan
 * Date: 2019/2/28
 * Time: 10:10
 */
namespace app\common\model;
use think\Model;

class BisAccount extends Model {
        public function addLocation ($data){
            if(empty($data)) {
                return false;
            }

            $data['status'] = 0;
            $data['create_time'] = time();
            $res = $this->save($data);
            return $res;
        }

        public function isAccountExist($name) {
            if (!$name) {
                return false;
            }

            return $this->where(['username'=> $name])->field('id')->find();
        }

        public function getAccountByBisId($id) {
            if (!$id){
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
     * 根据条件来查询信息
     */
     public function getBisAccount($data) {
         if (empty($data)) {
             return false;
         }

         $res = $this->where($data)->find();
         return $res;
     }
}