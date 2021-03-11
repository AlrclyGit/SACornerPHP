<?php
/**
 * Name: 用户地址控制器
 * User: 萧俊介
 * Date: 2020/9/4
 * Time: 10:17 上午
 * Created by SANewOrangePHP制作委员会.
 */

namespace App\Http\Controllers;

use App\Exceptions\UserException;
use App\Http\Requests\AddressNew;
use App\Models\User;
use App\Service\TokenService;


class AddressController extends Controller
{
    /*
     * 更新或者添加用户收货地址
     */
    public function createOrUpdateAddress(AddressNew $request)
    {
        // 参数合理性验证
        $validated = $request->validated();
        // 获取当前用户UID
        $uid = TokenService::getCurrentUid();
        // 从 User 表获取用户
        $user = User::find($uid);
        // 获取关联地址自动入库
        $user->address()->updateOrCreate([],$validated);
        // 返回成功消息
        return saReturn();
    }

    /*
     * 获取用户地址
     */
    public function getUserAddress()
    {
        // 获取当前用户UID
        $uid = TokenService::getCurrentUid();
        // 从 User 表获取用户信息和地址
        $userAddress = User::with('address')->find($uid);
        // 判断用户地址是否存在
        if(!$userAddress['address']){
            throw new UserException([
                'msg' => '用户地址不存在',
                'errorCode' => '30001'
            ]);
        }
        // 返回成功消息
        return saReturn($userAddress);
    }


}
