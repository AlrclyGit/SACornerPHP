<?php
/**
 * Name:
 * User: 萧俊介
 * Date: 2020/9/4
 * Time: 3:10 下午
 * Created by SANewOrangePHP制作委员会.
 */

namespace App\Http\Middleware;


use App\Exceptions\BaseExceptions;
use App\Service\TokenService;
use Closure;

class VerifyUserAndAdminToken
{
    public function handle($request, Closure $next)
    {
        if (TokenService::needPrimaryScope()) {
            return $next($request);
        }
        throw new BaseExceptions([
            'msg' => 'UserAndAdmin'
        ]);
    }
}
