<?php
/**
 * Name: 请求的专题不存在
 * User: 萧俊介
 * Date: 2020/9/7
 * Time: 2:50 下午
 * Created by SANewOrangePHP制作委员会.
 */

namespace App\Exceptions;


class ThemeExceptions extends BaseExceptions
{
    // HTTP 状态码
    public $code = 404;
    // 自定义的错误码
    public $errorCode = 42001;
    // 错误具体信息
    public $msg = '请求的专题不存在';
    // 附带的内容
    public $data = [];
}
