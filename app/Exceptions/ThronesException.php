<?php
/**
 * Name: 权利异常类
 * User: 萧俊介
 * Date: 2021/3/23
 * Time: 1:36 下午
 * Created by SACornerPHP制作委员会.
 */

namespace App\Exceptions;


class ThronesException extends BaseExceptions
{
    public function __construct($code, $msg, $data = [])
    {
        parent::__construct(403, $code, $msg, $data);
    }
}