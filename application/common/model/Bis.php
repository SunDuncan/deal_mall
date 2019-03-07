<?php
/**
 * Created by PhpStorm.
 * User: Duncan
 * Date: 2019/2/28
 * Time: 9:24
 */

namespace app\common\model;
use think\Model;

class Bis extends Model {
    public function addBis($data) {
        if(empty($data)) {
            return false;
        }

        $data['status'] = 0;
        $data['create_time'] = time();
        $this->save($data);
        return $this->id;
    }

    public function getBisById($id) {
        if(empty($id)){
            return false;
        }

        return $this->find($id);
    }

    /**
     * 通过状态获取商家数据
     */
    public function getBisByStatus($status = 0) {
        $order = [
            'id' => 'desc'
        ];

        $data = [
            'status' => $status
        ];

        $result = $this->where($data)->order($order)->paginate();
        return $result;
    }

    /**
     * 更新编辑操作
     */
    public function updateBisById($data) {
        if(is_null($data)) {
            return false;
        }

        $data['update_time'] = time();
        $res = $this->where('id', $data['id'])->update($data);
        return $res;
    }

}