<?php
/**
 * Name: 管理员身份中间件
 * User: 萧俊介
 * Date: 2020/9/4
 * Time: 3:10 下午
 * Created by SANewOrangePHP制作委员会.
 */

namespace App\Http\Middleware;

use App\Service\TokenService;
use Closure;

class VerifyUserAndAdminToken
{
    public function handle($request, Closure $next)
    {
        TokenService::needPrimaryScope();
        return $next($request);
    }
}
