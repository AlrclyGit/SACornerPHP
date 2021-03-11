<?php
/**
 * Name:
 * User: 萧俊介
 * Date: 2020/9/4
 * Time: 2:37 下午
 * Created by SANewOrangePHP制作委员会.
 */

namespace App\Exceptions;


class ForbiddenException extends BaseExceptions
{
    public $code = 403;
    public $errorCode = 10000;
    public $msg = '权限不够';
}
