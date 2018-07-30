<?php
/**
 * Created by PhpStorm.
 * User: chendongdong
 * Date: 2018/7/29
 * Time: 0:36
 */

Route::any('/qrcode', 'Wechat\WechatController@qrcode');

//这个是公众号的登录注册验证接口，小程序不适用
Route::group(['middleware' => 'mock.user'], function () {//这个中间件可以先忽略，我们稍后再说
    Route::middleware('wechat.oauth:snsapi_base')->group(function () {
        Route::get('/login', 'Wechat\WechatController@autoLogin')->name('login');
    });
    Route::middleware('wechat.oauth:snsapi_userinfo')->group(function () {
        Route::get('/register', 'Wechat\WechatController@autoRegister')->name('register');
    });
});

Route::group(['middleware' => ['web', 'wechat.oauth']], function () {
    Route::get('/user', function () {
        $user = session('wechat.oauth_user'); // 拿到授权用户资料

        dd($user);
    });
});