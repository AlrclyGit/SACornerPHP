<?php
/**
 * Name: Token控制器
 * User: 萧俊介
 * Date: 2020/9/1
 * Time: 11:11 上午
 * Created by SANewOrangePHP制作委员会.
 */

namespace App\Http\Controllers;


use App\Http\Requests\TokenGet;
use App\Service\UserTokenService;

class TokenController extends Controller
{

    /*
     *
     */
    public function getToken(TokenGet $request)
    {
        // 获取过滤过的参数
        $validated = $request->validated();
        // 实例化一个 Token 类，并传入 Token
        $userTokenS = new UserTokenService($validated['code']);
        // 调用获取 Token 的方法
        $token = $userTokenS->get();
        // 返回
        return saReturn($token);
    }
}
