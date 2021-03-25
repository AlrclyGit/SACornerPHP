<?php
/**
 * Name: 正常
 * User: 萧俊介
 * Date: 2021/3/25
 * Time: 12:34 下午
 * Created by SACornerPHP制作委员会.
 */

namespace App\Exceptions;


class ReturnException extends BaseExceptions
{
    public function __construct($code, $msg, $data = [])
    {
        parent::__construct(200, $code, $msg, $data);
    }
}