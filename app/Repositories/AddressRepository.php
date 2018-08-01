<?php
/**
 * Created by PhpStorm.
 * User: VemServer
 * Date: 2018/8/1
 * Time: 9:43
 */

namespace App\Repositories;

use App\Models\Address;

class AddressRepository extends BaseRepository
{
    protected $model;

    public function __construct(Address $model)
    {
        $this->model = $model;
    }

    /**
     * 获取多条数据
     * @param $where 查询条件
     * @param string $field 查询字段
     * @return array|mixed
     */
    public function getMulti(array $where, $field = '*')
    {
        $result = $this->model->getData($where, $field);
        return empty($result) ? [] : $result;
    }

}