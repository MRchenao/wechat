<?php

namespace App\Http\Middleware;

use App\Formatters\Format;
use Closure;
use Illuminate\Support\Facades\Cache;
use EasyWeChat\Factory;


class MockUser
{
    use Format;

    protected $cacheTime = 86400;
    const TOKEN_STRING = 'Fiasdjl8F9sajk9ASG23';

    public function handle($request, Closure $next)
    {
        try {
            $token = $request->header('token');
            if (!empty($token) && Cache::get($token)) {
                return $next($request);
            }

            $app = Factory::miniProgram(config('wechat.mini_program.default'));
            $code = $request->header('x-wx-code');
            $user_info = $request->input('userInfo');
            if (!empty($code)) {
                $session = $app->auth->session($code);
                $openid = empty($session['openid']) ? '' : $session['openid'];
                $key = empty($session['session_key']) ? '' : $session['session_key'];
                $user_info['openid'] = $openid;
                $user_info['session_key'] = $key;
                $login_key = md5($openid . $key . self::TOKEN_STRING);
                Cache::put($login_key, $user_info, $this->cacheTime);
            }
            session(['wechat.oauth_user.default' => $user_info]);

            return $next($request);
        } catch (\Exception $e) {
            return response()->json($this->formatException($e));
        }
    }
}
