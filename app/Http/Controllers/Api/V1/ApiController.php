<?php
/**
 * Created by PhpStorm.
 * User: Gilbert.Ho
 * Date: 20/03/2018
 * Time: 10:44 AM
 * FILENAME:BaseController.php
 */

namespace App\Http\Controllers\Api;

use App\Formatters\Format;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Validator;

class ApiController extends Controller
{
    use Format;
    public $pageSize = 10;
    protected $startTime;
    public $isCache = false;
    public $app = null;
    protected $formatter;
    protected $service;


    public function __construct()
    {
        $this->startTime = microtime(true);
        $app = \app();
        $this->app = $app;
    }

    /**
     * 成功返回数据
     * @param array $data
     * @param string $message
     * @param int $status
     * @param array $headers
     * @param int $options
     * @return \Illuminate\Http\JsonResponse
     */
    public function trueResponse($data = [], $message = '', $status = 200, array $headers = [], $options = 0)
    {
        return response()->json($this->formatCommonData($data, $message), $status, $headers, $options);
    }

    /**
     * 失败返回数据
     * @param string $message
     * @param int $status
     * @param array $headers
     * @param int $options
     * @return \Illuminate\Http\JsonResponse
     */
    public function failResponse($message = '', $status = 200, array $headers = [], $options = 0)
    {
        return response()->json($this->formatFalseReturn($message), $status, $headers, $options);
    }

    /**
     * 异常返回数据
     * @param string $message
     * @param int $status
     * @param array $headers
     * @param int $options
     * @return \Illuminate\Http\JsonResponse
     */
    public function exceptionResponse(\Exception $e, $status = 200, array $headers = [], $options = 0)
    {
        return response()->json($this->formatException($e), $status, $headers, $options);
    }

    /**
     * 验证数据
     * @param $data
     * @param $rules
     * @return bool
     */
    public function validateData($data, $rules)
    {
        if (empty($data) || empty($rules)) {
            throwException(301, '无验证数据或验证规则！');
        }
        // 开始验证
        $validator = Validator::make($data, $rules);
        if ($validator->fails()) { // 验证失败
            throwException(301, $validator->errors());
        }

        return true;
    }
}
