<?php
/**
 * Name: 商品错误类
 * User: 萧俊介
 * Date: 2020/9/7
 * Time: 2:59 下午
 * Created by SANewOrangePHP制作委员会.
 */

namespace App\Exceptions;


class ProductExceptions extends BaseExceptions
{
    // HTTP 状态码
    public $code = 404;
    // 自定义的错误码
    public $errorCode = 43001;
    // 错误具体信息
    public $msg = '请求的商品不存在';
    // 附带的内容
    public $data = [];
}
