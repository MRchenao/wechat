<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2018/8/1/0
 * Time: 10:14
 */

namespace App\Models;

use Illuminate\Support\Facades\DB;

trait CommonDB
{
    /**
     * @param array $where
     * @param string $field
     * @param int $skip
     * @param int $limit
     * @param array $order
     * @param bool $return_total
     *
     * @return mixed
     *
     * @throws \Exception
     */
    public function getList(array $where, $field = '*', int $skip = 0, int $limit = 10, $order = [], $return_total = true)
    {
        $data = DB::table($this->table);
        $data = $this->excuteWhere($data, $where);
        $total = 0;
        if ($return_total)
        {
            $total = $data->count();
        }
        $data = $data->skip($skip)->take($limit);
        $data = $this->excuteOrder($data, $order);
        $data = $data->select($field)->get();
        return ['total' => $total, 'data' => $data];
    }

    /**
     * 单条插入
     *
     * @param $data
     *
     * @return int
     */
    public function insert($data)
    {
        return DB::table($this->getTable())->insertGetId($data);
    }

    /**
     * 批量插入
     * @param array $data
     *
     * @return bool
     */
    public function multiInsert(array $data)
    {
        return DB::table($this->getTable())->insert($data);
    }

    /**
     * 根据条件查询数据
     * @param array $where
     * @param string $field
     * @return mixed
     */
    public function getData(array $where, $field = '*')
    {
        $model = DB::table($this->table)
            ->select($field);
        return $this->excuteWhere($model, $where)
            ->get()->map(function ($value) {
                return (array)$value;
            })->toArray();
    }

    /**
     * @param $db DB
     * @param $where
     * @return mixed
     */
    private function excuteWhere($db, $where)
    {
        $method_map = [
            'in' => 'whereIn',
            'notin' => 'whereNotIn',
            'between' => 'whereBetween',
            'notbetween' => 'whereNotBetween',
            'wheredate' => 'whereDate',
            'wheremonth' => 'whereMonth',
            'whereday' => 'whereDay',
            'whereyear' => 'whereYear',
        ];
        if (!empty($where)) {
            foreach ($where as $k => $v) {
                if (is_array($v)) {
                    $whereType = strtolower(current($v));
                    if (isset($method_map[$whereType])) {
                        $method = $method_map[$whereType];
                        $db = $db->$method($k, end($v));
                        continue;
                    }
                    $vals = end($v);
                    switch ($whereType)
                    {
                        case 'like':
                            $db = $db->where($k, 'like', $vals);
                            break;
                        case 'null':
                            $db = $db->whereNull($k);
                            break;
                        case 'notnull':
                            $db = $db->whereNotNull($k);
                            break;
                        case 'wheretime':
                            if (count($vals) != 2)
                            {
                                break;
                            }
                            $db = $db->whereTime($k, ...$vals);
                            break;
                        case 'or':
                            $db = $db->orWhere($k, $vals);
                            break;
                        case 'orwherein':
                            $db = $db->orWhere(function ($db) use ($k, $vals) {
                                $db->whereIn($k, $vals);
                            });
                            break;
                        default:
                            $db = $db->where($k, ...$v);
                            break;
                    }
                }
                else
                {
                    $db = $db->where($k, $v);
                }
            }
        }
        return $db;
    }

    /**
     * @param $db DB
     * @param $order
     * @return mixed
     */
    private function excuteOrder($db, $order)
    {
        if (!empty($order)) {
            foreach ($order as $k => $v) {
                $v = strtoupper($v);
                if (is_numeric($k) || !is_string($k) || !in_array($v, ['ASC', 'DESC'])) {
                    continue;
                }
                $db = $db->orderBy($k, $v);
            }
        }
        return $db;
    }

    public function getOne($where, $field="*")
    {
        $db = DB::table($this->getTable());
        return $this->excuteWhere($db, $where)->select($field)->first();
    }

    /**
     * @param $where
     * @return mixed
     */
    public function count($where)
    {
        $db = DB::table($this->table);
        return $this->excuteWhere($db, $where)->count();
    }

    /**
     * @param $where
     * @param $column
     * @return mixed
     */
    public function sum($where,$column)
    {
        $db = DB::table($this->table);
        return $this->excuteWhere($db, $where)->sum($column);
    }

    /**
     * @param $where
     * @return mixed
     */
    public function deleteByWhere($where)
    {
        $db = DB::table($this->table);
        return $this->excuteWhere($db, $where)->delete();
    }

    /**
     * @param $where
     * @param $values ['vote'=>1]
     * @return mixed
     */
    public function updateWhere($where, $values)
    {
        return $this->excuteWhere(DB::table($this->table), $where)->update($values);;
    }

    /**
     * @param $where = [
     *                  'user_id'=>$user_id,
     *                  'order_status'=>['in',$status]
     *                 ];
     * @param $increment_column string
     * @param array $values ['vote'=>1]
     * @return mixed
     */
    public function updateAndIncrement($where, $increment_column, $values = [], $step = 1)
    {
        return $this->excuteWhere(DB::table($this->table), $where)->increment($increment_column, $step, $values);
    }

}
