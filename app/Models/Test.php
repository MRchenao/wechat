<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Test extends Model
{
    protected $table = 'test';

    public function test($data)
    {
        return $data.'牛啊，你都成功到模型了';
    }

}
