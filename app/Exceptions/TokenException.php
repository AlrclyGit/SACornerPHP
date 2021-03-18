<?php
/**
 * Name:
 * User: 萧俊介
 * Date: 2020/9/3
 * Time: 5:21 下午
 * Created by SANewOrangePHP制作委员会.
 */

namespace App\Exceptions;


class TokenException extends BaseExceptions
{
    public function __construct($errorCode = 20000, $msg = 'Token相关错误', $data = [], $code = 401)
    {
        parent::__construct($errorCode, $msg, $data, $code);
    }

}
