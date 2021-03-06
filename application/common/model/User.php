<?php
/**
 * Created by PhpStorm.
 * User: Duncan
 * Date: 2019/3/4
 * Time: 13:51
 */
namespace app\common\model;
use think\Exception;
use think\Model;

class User extends Model{
    protected $field = true;

    public function getSingleUserInfo($data){
        if (empty($data)) {
            \exception("查询条件为空");
        }

        $res = $this->where($data)->find();
        return $res;
    }

    public function addUser($data=[]) {
        if (!is_array($data)){
            return false;
        }

        $data['status'] = 1;
        $data['create_time'] = time();
        $data['listorder'] = 0;
        $res = $this->save($data);
        return $res;
    }

    public function updateUser($data) {
        if(empty($data)) {
            return ;
        }

        return $this->update($data);
    }

    /**
     * 查询数据库的用户
     */
    public function getUsers() {
        $order = [
            'status' => 'desc',
            'listorder'=> 'asc',
            'id'=> 'asc'
        ];

        return $this->field("id,mobile,username,email,listorder,status,create_time,update_time,last_login_time")->order($order)->paginate();
    }

    public function updateUserById($data) {
        if(is_null($data)) {
            return false;
        }

        $data['update_time'] = time();
        $res = $this->where('id', $data['id'])->update($data);
        return $res;
    }
}