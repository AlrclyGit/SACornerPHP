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
    // HTTP 状态码
    public $code = 400;
    // 自定义的错误码
    public $errorCode = 30000;
    // 错误具体信息
    public $msg = '用户信息相关错误';
    // 附带的内容
    public $data = [];
}