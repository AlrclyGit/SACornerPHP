<?php
/**
 * Name: 资源异常类
 * User: 萧俊介
 * Date: 2021/3/23
 * Time: 12:33 下午
 * Created by SACornerPHP制作委员会.
 */

namespace App\Exceptions;


class EmptyException extends BaseExceptions
{
    public function __construct($code, $msg, $data = [])
    {
        parent::__construct(404, $code, $msg, $data);
    }
}