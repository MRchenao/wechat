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

        $where = [
            'area_info' => $data['area_info'],
            'address' => $data['address']
        ];
        if (!empty($this->repository->getOneRow($where, 'address_id'))) {
            throwException(302, '地址信息重复，请不要重复添加！');
        }

        return $this->create($data);
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
     * @param $data
     * @return bool|null
     */
    public function delAddress($data)
    {
        $where = [
            'member_id' => $data['member_id'],
            'address_id' => $data['address_id'],
        ];

        return $this->repository->del($where);
    }
}