<?php

namespace App\Http\Controllers\Api\V1\Web;

use App\Formatters\AddressFormatter;
use App\Services\AddressService;
use Illuminate\Http\Request;
use App\Http\Controllers\Api\ApiController;

class AddressController extends ApiController
{

    //依赖注入
    public function __construct(AddressService $service, AddressFormatter $formatter)
    {
        parent::__construct();
        $this->service = $service;
        $this->formatter = $formatter;
    }

    /**
     * 用户收货地址列表
     * @param Request $request
     * @return array
     */
    public function index(Request $request)
    {
        try {
            // 创建数据验证规则
            $rules = array(
                'member_id' => array('integer', 'required')
            );
            // 开始验证
            $this->validateData($request->input(), $rules);

            $member_id = $request->input('member_id');
            $data = $this->service->getAddressByMemberId($member_id);
            if (!empty($data)) {
                $list = $this->formatter->transformList($data);
                return $this->trueResponse($list, '获取数据成功！');
            }

            return $this->failResponse('暂无数据！');
        } catch (\Exception $e) {
            return $this->exceptionResponse($e);
        }
    }

    /**
     * 新增收货地址
     * @param Request $request
     * @return array
     */
    public function create(Request $request)
    {
        try {
            $rules = array(
                'member_id' => array('integer', 'required'),
                'mob_phone' => array('integer', 'required', 'regex:/^1[34578]{1}\d{9}$/'),
                'true_name' => array('string', 'required'),
                'province_id' => array('integer', 'required'),
                'area_id' => array('integer', 'required'),
                'city_id' => array('integer', 'required'),
                'area_info' => array('string', 'required'),
                'address' => array('string', 'required'),
            );
            $data = $request->input();

            $this->validateData($data, $rules);

            if ($this->service->addAddressOne($data)) {
                return $this->trueResponse([], '添加数据成功！');
            }
            return $this->failResponse('添加数据失败！');

        } catch (\Exception $e) {
            return $this->exceptionResponse($e);
        }
    }

    /**
     * 更新收货地址
     * @param Request $request
     * @return array
     */
    public function update(Request $request)
    {
        try {
            $rules = array(
                'member_id' => array('integer', 'required'),
                'mob_phone' => array('integer', 'regex:/^1[34578]{1}\d{9}$/'),
                'true_name' => array('string'),
                'province_id' => array('integer'),
                'area_id' => array('integer'),
                'city_id' => array('integer'),
                'is_default' => array('integer'),
                'area_info' => array('string'),
                'address' => array('string'),
            );
            // 开始验证
            $this->validateData($request->input(), $rules);

            $column = [
                'mob_phone',
                'true_name',
                'province_id',
                'area_id',
                'city_id',
                'area_info',
                'address',
                'is_default',
            ];

            $data = $this->service->getExistData($request, $column);
            if (empty($data)) return $this->failResponse('您没有更新任何字段！');

            $member_id = $request->input('member_id');
            $res = $this->service->updateAddressByMemberId($member_id, $data);
            if ($res) {
                return $this->trueResponse([], '更新数据成功！');
            }

            return $this->failResponse('更新数据失败！');
        } catch (\Exception $e) {
            return $this->exceptionResponse($e);
        }
    }

    /**
     * 删除收货地址
     * @param Request $request
     * @return array
     */
    public function delete(Request $request)
    {
        try {
            $rules = array(
                'member_id' => array('integer', 'required'),
                'address_id' => array('integer', 'required')
            );
            $data = $request->input();
            $this->validateData($data, $rules);

            if ($this->service->delAddress($data)) {
                return $this->trueResponse([], '删除数据成功！');
            }

            return $this->failResponse('删除数据失败！');
        } catch (\Exception $e) {
            return $this->exceptionResponse($e);
        }
    }


}
