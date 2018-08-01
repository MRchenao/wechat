<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Address extends Model
{
    use CommonDB;
    protected $table = 'address';

    //修改主键id，默认为id
    protected $primaryKey = 'address_id';
    /**
     * 表明模型是否应该被打上时间戳
     *
     * @var bool
     */
    public $timestamps = false;

    protected $fillable = [
        'member_id',
        'mob_phone',
        'true_name',
        'province_id',
        'area_id',
        'city_id',
        'area_info',
        'address',
    ];

}
