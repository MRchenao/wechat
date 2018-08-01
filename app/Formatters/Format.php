<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2018/8/1/16
 * Time: 11:39
 */

namespace App\Formatters;


use Illuminate\Database\QueryException;

trait Format
{
    /**
     * 格式化异常参数
     * @param \Exception $e
     * @return array
     */
    public function formatException(\Exception $e)
    {
        return [
            'status' => false,
            'code' => $e->getCode(),
            'message' => $e->getMessage(),
            'data' => [],
        ];
    }

    /**
     * 格式化查询失败异常
     * @param QueryException $e
     * @return array
     */
    public function formatQueryExcepiton(QueryException $e)
    {
        return [
            'status' => false,
            'code' => $e->getCode(),
            'message' => '操作失败',
            'data' => [],
        ];
    }

    /**
     * 失败返回数据
     * @param string $message
     * @return array
     */
    public function formatFalseReturn($message = '')
    {
        return [
            'status' => false,
            'code' => 201,
            'message' => $message,
            'data' => [],
        ];
    }

    /**
     * 成功返回数据
     * @param array $data
     * @return array
     */
    public function formatCommonData(array $data = [])
    {
        return [
            'status' => true,
            'code' => 200,
            'message' => '',
            'data' => $data,
        ];
    }
}