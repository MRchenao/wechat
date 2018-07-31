<?php

namespace App\Http\Middleware;

use Closure;
use Overtrue\Socialite\User as SocialiteUser;
use EasyWeChat\Factory;


class MockUser
{
    public function handle($request, Closure $next)
    {
        $app = Factory::miniProgram(config('wechat.mini_program.default'));
        $code = $request->header('x-wx-code');
        $session = $app->auth->session($code);
        return $session;
        var_dump($session);exit;

        session(['wechat.oauth_user.default' => $session]);
        return $next($request);
    }
}
