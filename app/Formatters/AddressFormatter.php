<?php
/**
 * Created by PhpStorm.
 * User: VemServer
 * Date: 2018/8/1
 * Time: 9:50
 */


namespace App\Formatters;

use Illuminate\Http\Request;
use Yish\Generators\Foundation\Format\FormatContract;
use Yish\Generators\Foundation\Format\Statusable;

class AddressFormatter implements FormatContract
{
    use Format;

    /**
     * 成功格式化数据
     * @param Request $request
     * @param array $items
     * @param string $message
     * @param bool $status
     * @return array
     */
    public function format(Request $request, $items = [], $message = '', $status = true, $code = 200)
    {
        return [
            'status' => $status,
            'code' => $code,
            'message' => $message,
            'data' => $items,
        ];
    }

}