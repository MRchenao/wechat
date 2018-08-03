<?php

namespace App\Services;

use Yish\Generators\Foundation\Service\Service;
use Illuminate\Http\Request;

class BaseService extends Service
{
    protected $repository;

    /**
     * 获取过滤过后的数据
     * @param Request $request 请求的request
     * @param $array 需要过滤的数组字段
     * @return array
     */
    public function getExistData(Request $request, array $array)
    {
        $data = [];
        foreach ($array as $value) {
            if ($request->has($value) && $request->input($value) != '') {
                $data[$value] = $request->input($value);
            }
        }

        return $data;
    }
}
