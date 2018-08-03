<?php

namespace App\Http\Middleware;

use App\Formatters\Format;
use Closure;
use League\Flysystem\Config;

class Md5StringCheck
{
    use Format;

    /**
     * 对请求的数据做加密校验，防止被篡改
     * @param $request
     * @param Closure $next
     * @return \Illuminate\Http\JsonResponse|mixed
     */
    public function handle($request, Closure $next)
    {
        try {
            if (env('APP_ENV') == 'production' || env('APP_ENV') == 'dev') {
                $data = $request->input();
                $md5String = $request->header('md5String');
                $timestamp = $request->header('timeStamp');

                if (empty($timestamp)) {
                    throwException(301, '请求的时间戳不能为空');
                }
                if (empty($md5String)) {
                    throwException(301, '加密的请求串不能为空');
                }

                if (time() - $timestamp >= 5 * 60) {
                    throwException(40000, '请求超时');
                }

                $key = $this->_getKey($data);
                unset($data['s']);
                $sign = getSign($data, $key);

                if ($sign != $md5String) {
                    throwException(40000, '网络错误');
                }
            }

            return $next($request);
        } catch (\Exception $e) {
            return response()->json($this->formatException($e));
        }
    }

    /**
     * 获取对应端约定的加密key
     * @param $data
     */
    private function _getKey($data)
    {
        $urlMsg = explode('/', $data['s']);
        if (empty($urlMsg) || !in_array($urlMsg[3], ['ios', 'android', 'wxapp'])) {
            throwException(301, '请求url有误！');
        }
        $key = '';
        switch ($urlMsg[3]) {
            case 'ios':
                $key = Config('ios_key_md5_sign');
                break;
            case 'android':
                $key = Config('android_key_md5_sign');
                break;
            case 'wxapp':
                $key = Config('wxapp_key_md5_sign');
                break;
            default:
                break;
        }

        if (empty($key)) {
            throwException(301, '没有发现配置加密的key值！');
        }

        return $key;
    }


}
