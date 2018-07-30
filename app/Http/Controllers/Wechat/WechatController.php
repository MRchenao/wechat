<?php

namespace App\Http\Controllers\Wechat;

use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Log;
use App\User;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\Auth;

class WechatController extends Controller
{
    /**
     * 处理微信的请求消息
     *
     * @return string
     */
    public function serve()
    {
        echo 12312;
        exit;
        Log::info('request arrived.'); # 注意：Log 为 Laravel 组件，所以它记的日志去 Laravel 日志看，而不是 EasyWeChat 日志

        $app = app('wechat.official_account');
        $app->server->push(function ($message) {
            return "欢迎关注 overtrue！";
        });

        return $app->server->serve();
    }

    public function getEasyWechatSession()
    {
        $user = session('wechat.oauth_user.default');
        return $user;
    }

    public function autoLogin()
    {
        $userInfo = $this->getEasyWechatSession();
        $openId = $userInfo['id'];
        //查看对应的openid是否已被注册
        $userModel = User::where('openid', $openId)->first();
        //如果未注册，跳转到注册
        if (!$userModel) {
            return redirect()->route('register');
        } else {
            //如果已被注册，通过openid进行自动认证，
            //认证通过后重定向回原来的路由，这样就实现了自动登陆。
            if (Auth::attempt(['openid' => $openId, 'password' => '123456'])) {
                return redirect()->intended();
            }
        }
    }

    public function autoRegister()
    {
        $userInfo = $this->getEasyWechatSession();
        //根据微信信息注册用户。
        $userData = [
            'password' => bcrypt('123456'),
            'openid' => $userInfo['id'],
            'name' => $userInfo['name'],
            'email' => $userInfo['email'],
        ];
        //注意批量写入需要把相应的字段写入User中的$fillable属性数组中
        User::create($userData);
        return redirect()->route('login');
    }
}
