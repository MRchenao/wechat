<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Support\Facades\Cache;
use Overtrue\Socialite\User as SocialiteUser;
use EasyWeChat\Factory;


class MockUser
{
    protected $cacheTime = 86400;
    protected $string = 'Fiasdjl8F9sajk9ASG23';

    public function handle($request, Closure $next)
    {
        $token = $request->header('token');
        if (!Cache::get($token)) {
            $app = Factory::miniProgram(config('wechat.mini_program.default'));
            $code = $request->header('x-wx-code');
            $session = $app->auth->session($code);
            $user_info = $request->input('userInfo');
            $openid = $session['openid'];
            $key = $session['session_key'];
            $user_info['openid'] = $openid;
            $user_info['session_key'] = $key;
            $login_key = md5($openid . $key . $this->string);
            Cache::put($login_key, $user_info, $this->cacheTime);
            session(['wechat.oauth_user.default' => $user_info]);
        }
        return $next($request);
    }
}
