<?php
/**
 * Created by PhpStorm.
 * User: Duncan
 * Date: 2019/3/4
 * Time: 9:55
 */
namespace app\common\model;
use think\Model;

class Featured extends Model{
    protected $field=true;

    public function addFeatured($data) {
        if (empty($data)) {
            return false;
        }

        $data['create_time'] = time();
        $data['status'] = 0;
        return $this->save($data);
    }

    /**
     * 通过类别获取图片
     */
    public function getPicByType($type=0) {
        $data['type'] = $type;
        $order = [
            'status' => 'desc',
            'listorder' => 'desc',
            'id'=> 'desc'
        ];

        return $this->where($data)->order($order)->paginate();
    }

    public function updateFeatureById($data) {
        if(is_null($data)) {
            return false;
        }

        $data['update_time'] = time();
        $res = $this->where('id', $data['id'])->update($data);
        return $res;
    }
}