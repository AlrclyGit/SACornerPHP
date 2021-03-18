<?php
/**
 * Name: 权限相关错误
 * User: 萧俊介
 * Date: 2021/3/18
 * Time: 4:04 下午
 * Created by SACornerPHP制作委员会.
 */

namespace App\Exceptions;


class ForbiddenException extends BaseExceptions
{
    public function __construct($errorCode = 10000, $msg = '权限相关错误', $data = [], $code = 200)
    {
        parent::__construct($errorCode, $msg, $data, $code);
    }
}