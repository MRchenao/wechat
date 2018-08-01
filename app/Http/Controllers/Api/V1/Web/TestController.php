<?php

namespace App\Http\Controllers\Api\V1\Web;

use App\Formatters\TestFormatter;
use App\Services\TestService;
use Illuminate\Http\Request;
use App\Http\Controllers\Api\ApiController;

class TestController extends ApiController
{
    protected $formatter;
    protected $service;

    //依赖注入
    public function __construct(TestService $service, TestFormatter $formatter)
    {
        parent::__construct();
        $this->service = $service;
        $this->formatter = $formatter;
    }

    /**
     * 开发测试demo
     * @param Request $request
     * @return array
     */
    public function test(Request $request)
    {
        $data = $this->service->test('这是测试');
        return $this->formatter->format($request, $data);
    }


}
