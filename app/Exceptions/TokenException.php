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
    // HTTP 状态码
    public $code = 401;
    // 自定义的错误码
    public $errorCode = 20000;
    // 错误具体信息
    public $msg = 'Token已过期或无效Token';
    // 附带的内容
    public $data = [];
}
