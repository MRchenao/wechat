<?php
/**
 * Created by PhpStorm.
 * User: chendongdong
 * Date: 2018/7/29
 * Time: 0:36
 */

Route::any('/', 'Wechat\WechatController@serve');

Route::group(['middleware' => ['web', 'wechat.oauth']], function () {
    Route::get('/user', function () {
        $user = session('wechat.oauth_user'); // 拿到授权用户资料

        dd($user);
    });
});