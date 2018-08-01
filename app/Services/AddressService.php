<?php
/**
 * Created by PhpStorm.
 * User: VemServer
 * Date: 2018/8/1
 * Time: 9:43
 */

namespace App\Services;

use App\Models\Address;
use App\Repositories\AddressRepository;
use Illuminate\Http\Request;

class AddressService extends BaseService
{
    protected $repository;
    const IS_DEFALUT_ADDREDD = 0;

    public function __construct()
    {
        $this->repository = new AddressRepository(new Address());
    }

    /**
     * 通过member_id获取用户地址
     * @param $member_id
     * @return array|mixed
     */
    public function getAddressByMemberId($member_id)
    {
        $where = [
            'member_id' => $member_id
        ];
        return $this->repository->getMulti($where);
    }

    /**
     * 添加一条地址数据
     * @param $data 要添加的数据数组
     * @return \Illuminate\Database\Eloquent\Model
     */
    public function addAddressOne($data)
    {
        //新增的地址默认为默认地址
        $data['is_default'] = self::IS_DEFALUT_ADDREDD;
        unset($data['s']);

        return $this->create($data);
    }

    /**
     * 获取过滤过后的数据
     * @param Request $request 请求的request
     * @param $array 需要过滤的数组字段
     * @return array
     */
    public function getFilterData(Request $request, array $array)
    {
        $data = [];
        foreach ($array as $value) {
            if ($request->has($value) && $request->input($value) != '') {
                $data[$value] = $request->input($value);
            }
        }

        return $data;
    }

    /**
     * 更新地址信息
     * @param $member_id
     * @param $data
     * @return bool
     */
    public function updateAddressByMemberId($member_id, $data)
    {
        $column = 'member_id';

        return $this->updateBy($column, $member_id, $data);
    }

    /**
     * 删除地址
     * @param $id address_id
     * @return bool|null
     */
    public function delAddressById($id)
    {
        $column = 'address_id';

        if (!empty($id)) {
            return $this->destroyBy($column, $id);
        }

        return false;
    }
}