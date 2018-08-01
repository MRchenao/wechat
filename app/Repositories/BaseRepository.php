<?php

namespace App\Repositories;

use Yish\Generators\Foundation\Repository\Repository;

class BaseRepository extends Repository
{
    protected $model;
    public $cacheExpire = 5;//5分钟有效期
    public $cachePrefix = 'haolegou'; //cache prefix

    /**
     * @param        $where
     *  [
     *      where a=b | a > b | a like b  [a => b] |  [a => [ >,  b]] |  [a => ['like', 'xxxx' ]]
     *      where a in|notin|between|notbetween (b,c,d)  [a => [ in | notin| between | notbetween  , [b,c,d ]]]
     *      where a is null | a is notnull  [a => ['null']] | [ a => ['notnull']]
     *      where c = d or where  a in [ 1, 2, 3]  [ c => d, a => [orwherein, [1, 2, 3]]]
     * ]
     * @param string $field
     * @param int    $start
     * @param int    $limit
     * @param array  $order
     * @param bool   $return_total
     *
     * @return mixed
     * @throws \Exception
     */
    public function getList($where, $field = '*', $start = 0, $limit = 10, $order = [], $return_total = true)
    {
        return $this->model->getList($where, $field, $start, $limit, $order, $return_total);
    }

    /**
     * @param $where
     *
     * @return mixed
     */
    public function deleteByWhere($where)
    {
        return $this->model->deleteByWhere($where);
    }

    /**
     * 批量插入
     *
     * @param array $data
     *
     * @return mixed
     */
    public function multiInsert(array $data)
    {
        return $this->model->multiInsert($data);
    }

    /**
     * @param $data
     *
     * @return mixed
     */
    public function insertGetId($data)
    {
        return $this->model->insert($data);
    }

    /**
     * @param $where
     *
     * @return mixed
     */
    public function count($where)
    {
        return $this->model->count($where);
    }

    /**
     * 查单条信息
     *
     * @param        $where
     * @param string $field
     *
     * @return mixed
     */
    public function getOne($where, $field = '*')
    {
        return $this->model->getOne($where, $field);
    }

    /**
     * @param $where
     * @param $values
     *
     * @return mixed
     */
    public function updateWhere($where, $values)
    {
        return $this->model->updateWhere($where, $values);
    }

    /**
     * 获取cacheKey
     *
     * @param string $className
     * @param string $funName
     * @param string $key
     *
     * @return string
     */
    public function getCacheKey($className, $funName, $key = '')
    {
        return $this->cachePrefix . "_" . $className . "_" . $funName . "_" . $key;
    }
}
