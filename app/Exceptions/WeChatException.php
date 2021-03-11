<?php
/**
 * Name:
 * User: 萧俊介
 * Date: 2020/9/1
 * Time: 2:46 下午
 * Created by SANewOrangePHP制作委员会.
 */

namespace App\Exceptions;


class WeChatException extends BaseExceptions
{
    // HTTP 状态码
    public $code = 400;
    // 自定义的错误码
    public $errorCode = 91000;
    // 错误具体信息
    public $msg = '微信服务器接口调用失败';
    // 附带的内容
    public $data = [];
}
