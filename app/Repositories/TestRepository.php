<?php
/**
 * Created by PhpStorm.
 * User: VemServer
 * Date: 2018/8/1
 * Time: 9:43
 */

namespace App\Repositories;

use App\Models\Test;

class TestRepository extends BaseRepository
{
    protected $model;

    public function __construct (Test $model)
    {
        $this->model = $model;
    }

    public function test($data)
    {
        $data = $this->model->test($data);
        return "你成功调用了repository".$data;
    }
}