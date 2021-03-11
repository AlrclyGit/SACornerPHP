<?php

use Illuminate\Support\Facades\Route;

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

Route::get('banner/{id}', 'BannerController@getBanner');

Route::get('theme', 'ThemeController@getSimpleList');
Route::get('theme/{id}', 'ThemeController@getComplexOne');

Route::get('product/recent','ProductController@getRecent');
Route::get('product/by_category','ProductController@getAllInCategory');
Route::get('product/{id}','ProductController@getOne');

Route::get('category/all', 'CategoryController@getAllCategories');

Route::post('token/user', 'TokenController@getToken');
Route::post('token/verify', 'TokenController@verifyToken');

Route::get('user_info', 'UserInfoController@getUserInfo');


// 仅用户可访问 权限
Route::middleware('userToken')->group(function () {
    Route::post('order', 'OrderController@placeOrder');
    Route::post('pay/pre_order', 'PayController@getPreOrder');
    Route::post('user_info', 'UserInfoController@setUserInfo');
});

// 用户和管理员可访问 权限
Route::middleware('userAndAdminToken')->group(function () {
    Route::post('address', 'AddressController@createOrUpdateAddress');
    Route::get('address', 'AddressController@getUserAddress');
    Route::post('order/{id}', 'OrderController@getDetail');
    Route::get('order/by_user', 'OrderController@getSummaryByUser');
});


