<?php
/**
 * Name: 用户身份中间件
 * User: 萧俊介
 * Date: 2020/9/4
 * Time: 2:58 下午
 * Created by SANewOrangePHP制作委员会.
 */

namespace App\Http\Middleware;

use App\Exceptions\BaseExceptions;
use App\Service\TokenService;
use Closure;

class VerifyUserToken
{
    public function handle($request, Closure $next)
    {
        if (TokenService::needExclusiveScope()) {
            return $next($request);
        }
        throw new BaseExceptions([
            'msg' => 'User'
        ]);
    }
}
