<?php
/**
 * Name: Token异常类
 * User: 萧俊介
 * Date: 2020/9/3
 * Time: 5:21 下午
 * Created by SANewOrangePHP制作委员会.
 */

namespace App\Exceptions;


class TokenException extends BaseExceptions
{
    public function __construct($code, $msg, $data = [])
    {
        parent::__construct(401, $code, $msg, $data);
    }
}
