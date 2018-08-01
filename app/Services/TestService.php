<?php
/**
 * Created by PhpStorm.
 * User: VemServer
 * Date: 2018/8/1
 * Time: 9:43
 */

namespace App\Services;

use App\Models\Test;
use App\Repositories\TestRepository;

class TestService extends BaseService
{
    protected $repository;

    public function __construct()
    {
        $this->repository = new TestRepository(new Test());
    }

    public function test($data)
    {
        $data = $this->repository->test($data);
        return $data.'你成功调用到我了控制器';
    }
}