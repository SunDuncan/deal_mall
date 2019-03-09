<?php
/**
 * Created by PhpStorm.
 * User: Duncan
 * Date: 2019/3/4
 * Time: 9:14
 */
namespace app\common\model;
use think\Model;

class Deal extends Model{
    protected $field = true;

    public function getDealInfo($data) {
        $data['status'] = 1;
        $order = [
            'listorder' => 'desc',
            'id'    => 'desc'
        ];
        return $this->where($data)->order($order)->select();
    }

    public function addDeal($data) {
        if (empty($data)) {
            return false;
        }

        $data['create_time'] = time();
        $data['status'] = 0;
        return $this->save($data);
    }

    /**
     * 商品分类-美食-推荐的数据
     */
    /**
     * @param $id 分类的id
     * @param $cityId 城市的id
     * @param int $limit
     */
    public function getNormalDealByCategoryCityId($id, $cityId, $limit = 10){
        $data = [
            ['end_time' ,'>', time()],
            ['category_id' ,'=', $id],
            ['city_id' ,'=', $cityId],
            ['status' ,'=', 1]
        ];

        $order = [
            'listorder' => 'desc',
            'id' => 'desc'
        ];

        $result = $this->where($data)->order($order)->limit($limit)->select();
        return $result;
    }

    public function getSingleById($id) {
        return $this->find($id);
    }

    public function getDealByCondition($data = [], $order = []) {
        if (!empty($order['order_sales'])) {
            $order_data['buy_count'] = 'desc';
        }
        if (!empty($order['order_price'])) {
            $order_data['current_price'] = 'desc';
        }
        if (!empty($order['order_time'])) {
            $order_data['create_time'] = 'desc';
        }

        $category_id = '';
        $order_data['id'] = "desc";
        $datas[] = ["end_time", '>'  ,time()];
        if (!empty($data['se_category_id'])) {
            $category_id = " find_in_set(" . $data['se_category_id'] . ",se_category_id)" ;
        }

        if (!empty($data['category_id'])) {
            $datas[] = ['category_id' ,' = ', $data['category_id']];
        }

        if (!empty($data['city_id'])) {
            $datas[] = ['city_id', "=", $data['city_id']];
        }
        $datas[] = ['status', "=", 1];
        return $this->where($category_id)->where($datas)->order($order_data)->paginate();
    }

    /**
     * 查询所有的子分类
     */
    public function getSeCategories() {
        return $this->field('se_category_id')->select();
    }

    /**
     * 查询所有的团购商品
     */
    public function getAllDeals($data){
        if (empty($data)){
            return;
        }

        $order = [
            'listorder' => 'desc',
            'id' => 'desc'
        ];

        return $this->where($data)->order($order)->paginate();
    }

    public function updateDeal($data) {
        if(is_null($data)) {
            return false;
        }

        $data['update_time'] = time();
        $res = $this->where('id', $data['id'])->update($data);
        return $res;
    }
}