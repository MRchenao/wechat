<?php
/**
 * 公共函数
 * Created by PhpStorm.
 * User: Gilbert.Ho
 * Date: 30/03/2018
 * Time: 4:09 PM
 * FILENAME:functions.php
 */

/**
 * 数组 转 对象
 *
 * @param array $arr 数组
 *
 * @return object
 */
function array_to_object($arr)
{
    if (gettype($arr) != 'array') {
        return;
    }
    foreach ($arr as $k => $v) {
        if (gettype($v) == 'array' || getType($v) == 'object') {
            $arr[$k] = (object)array_to_object($v);
        }
    }

    return (object)$arr;
}

/**
 * 对象 转 数组
 *
 * @param object $obj 对象
 *
 * @return array
 */
function object_to_array($obj)
{
    $obj = (array)$obj;
    foreach ($obj as $k => $v) {
        if (gettype($v) == 'resource') {
            return;
        }
        if (gettype($v) == 'object' || gettype($v) == 'array') {
            $obj[$k] = (array)object_to_array($v);
        }
    }

    return $obj;
}

/**
 * 获取真实客户端请求ip
 * @return null|string
 */
function get_client_ip()
{
    static $realip = null;

    if ($realip !== null) {
        return $realip;
    }

    if (!empty($_SERVER['HTTP_X_FORWARDED_FOR'])) {
        $tmp = explode(',', $_SERVER['HTTP_X_FORWARDED_FOR']);

        foreach ($tmp as $ip) {
            $ip = trim($ip);

            if (filter_var($ip, FILTER_VALIDATE_IP, FILTER_FLAG_NO_PRIV_RANGE)) // 外网加上，内网不能增加
            {
                $realip = $ip;
                break;
            }
        }
    } elseif (isset($_SERVER['HTTP_X-Real-IP'])) {
        $realip = trim($_SERVER['HTTP_X-Real-IP']);
    } elseif (isset($_SERVER['REMOTE_ADDR'])) {
        $realip = trim($_SERVER['REMOTE_ADDR']);
    }

    if (filter_var($realip, FILTER_VALIDATE_IP)) {
        return $realip;
    } else {
        return '0.0.0.0';
    }

    return $ip;
}


/**
 * 生成随机字符串
 *
 * @param int $length 字符串长度
 * @param bool $isNmber 是否为数字
 *
 * @return string
 */
function make_rand($length = 4, $isNmber = true)
{
    if ($isNmber) {
        $str = str_shuffle(strtotime("now") . rand(1000, 9999)); //随机生成一串数字
    } else {
        $str = str_shuffle("ACDEFGHJKMNPQRSTUVWXYZacdefghkmnprstuvwxyz123465789"); /*打乱字符串的顺序*/
    }

    $result = "";

    for ($i = 0; $i < $length; $i++) {
        $num[$i] = rand(0, strlen($str) - 1);
        $result .= $str[$num[$i]];
    }

    return $result;
}

/**
 * 生成签名
 * @return 签名
 * @param $key 签名串
 */
function getSign($params, $key)
{
    //签名步骤一：按字典序排序数组参数
    ksort($params);
    $string = getToUrlParams($params);
    //签名步骤二：在string后加入KEY
    $string = $key . $string;
    //签名步骤三：MD5加密
    $string = md5($string);

    return $string;
}

/**
 * 将参数拼接为url: key=value&key=value
 * @param   $params
 * @return  string
 */
function getToUrlParams($params)
{
    $string = '';
    if (!empty($params)) {
        $array = array();
        foreach ($params as $key => $value) {
            $array[] = $key . '=' . $value;
        }
        $string = implode("&", $array);
    }
    return $string;
}

/**
 * 抛出异常信息
 * @param $code 异常信息代码
 * @param $message 异常信息提示
 * @throws Exception
 */
function throwException($code, $message)
{
    throw new \Exception($message, $code);
}



