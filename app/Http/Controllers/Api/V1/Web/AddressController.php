<?php

namespace App\Http\Controllers\Api\V1\Web;

use App\Formatters\AddressFormatter;
use App\Services\AddressService;
use Illuminate\Http\Request;
use App\Http\Controllers\Api\ApiController;
use Illuminate\Support\Facades\Validator;

class AddressController extends ApiController
{
    protected $formatter;
    protected $service;

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
        // 创建数据验证规则
        $rules = array(
            'member_id' => array('integer', 'required')
        );
        // 开始验证
        $validator = Validator::make($request->input(), $rules);
        if ($validator->fails()) { // 验证失败
            return $this->formatter->formatFalseReturn($validator->errors());
        }

        $member_id = $request->input('member_id');
        $data = $this->service->getAddressByMemberId($member_id);
        if (!empty($data)) {
            return $this->formatter->format($request, $data, '获取数据成功！');
        }

        return $this->formatter->format($request, [], '暂无数据！');
    }

    /**
     * 新增收货地址
     * @param Request $request
     * @return array
     */
    public function create(Request $request)
    {
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
        // 开始验证
        $validator = Validator::make($data, $rules);
        if ($validator->fails()) { // 验证失败
            return $this->formatter->formatFalseReturn($validator->errors());
        }

        if ($this->service->addAddressOne($data)) {
            return $this->formatter->format($request, true, '插入数据成功！');
        }
        return $this->formatter->format($request, false, '插入数据失败！');
    }

    /**
     * 更新收货地址
     * @param Request $request
     * @return array
     */
    public function update(Request $request)
    {
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
        $validator = Validator::make($request->input(), $rules);
        if ($validator->fails()) { // 验证失败
            return $this->formatter->formatFalseReturn($validator->errors());
        }

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

        $data = $this->service->getFilterData($request, $column);
        if (empty($data)) return $this->formatter->format($request, false, '您没有更新任何字段！');

        $member_id = $request->input('member_id');
        $res = $this->service->updateAddressByMemberId($member_id, $data);
        if ($res) {
            return $this->formatter->format($request, true, '更新数据成功！');
        }

        return $this->formatter->format($request, false, '更新数据失败！');
    }

    /**
     * 删除收货地址
     * @param Request $request
     * @return array
     */
    public function delete(Request $request)
    {
        $rules = array(
            'member_id' => array('integer', 'required'),
            'address_id' => array('integer', 'required')
        );
        $data = $request->input();
        // 开始验证
        $validator = Validator::make($data, $rules);
        if ($validator->fails()) { // 验证失败
            return $this->formatter->formatFalseReturn($validator->errors());
        }

        if ($this->service->delAddressById($data['address_id'])) {
            return $this->formatter->format($request, true, '删除数据成功！');
        }

        return $this->formatter->format($request, false, '删除数据失败！');
    }


}
