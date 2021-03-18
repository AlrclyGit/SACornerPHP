<?php
/**
 * Name: 用户信息相关错误
 * User: 萧俊介
 * Date: 2020/10/16
 * Time: 3:04 下午
 * Created by PHP制作委员会.
 */

namespace App\Exceptions;


class UserException extends BaseExceptions
{
    public function __construct($errorCode = 30000, $msg = '用户信息相关错误', $data = [], $code = 200)
    {
        parent::__construct($errorCode, $msg, $data, $code);
    }
}