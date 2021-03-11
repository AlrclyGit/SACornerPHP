<?php
/**
 * Name:
 * User: 萧俊介
 * Date: 2020/9/7
 * Time: 11:01 上午
 * Created by SANewOrangePHP制作委员会.
 */

namespace App\Exceptions;


class OrderException extends BaseExceptions
{
    public $code = 404;
    public $msg = '订单不存在，请检查ID';
    public $errorCode = 80001;
}
