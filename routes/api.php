<?php

use Illuminate\Http\Request;
use Illuminate\Routing\Router;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| is assigned the "api" middleware group. Enjoy building your API!
|
*/
//api/api_version/(ios|android|web)/client_version/模块名/类名/方法名
//api/1.0/ios/1.1/user//类名/方法名

Route::middleware('auth:api')->get('/user', function (Request $request) {
    return $request->user();
});

$where = [
    'api_version' => '[0-9.]+', 'client' => '(ios|android|wxapp)', 'client_version' => '[0-9.]+',
];

//api
Route::group(['prefix' => '', 'middleware' => ['md5StringCheck'],], function (Router $router) use ($where) {
    $prefix = '{api_version}/{client}/{client_version}/web/';
    $pre_dir = 'Api\V1\Web' . '\\';

    //收货地址/地址列表
    $router->get($prefix . 'address/index', $pre_dir . 'AddressController@index')->where($where);
    //添加收货地址
    $router->post($prefix . 'address/create', $pre_dir . 'AddressController@create')->where($where);
    //编辑收货地址
    $router->post($prefix . 'address/update', $pre_dir . 'AddressController@update')->where($where);
    //删除收货地址
    $router->post($prefix . 'address/delete', $pre_dir . 'AddressController@delete')->where($where);


});

//test
Route::group(['prefix' => 'test',], function (Router $router) {
    $router->get('test', 'Api\V1\Web\TestController@test');
});


