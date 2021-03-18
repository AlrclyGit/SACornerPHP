<?php
/**
 * Name: 异常处理
 * User: 萧俊介
 * Date: 2020/8/26
 * Time: 12:00 下午
 * Created by SANewOrangePHP制作委员会.
 */

namespace App\Exceptions;

use RuntimeException;

class BaseExceptions extends RuntimeException
{

    // 自定义的错误码
    public $errorCode;
    // 错误具体信息
    public $msg;
    // 附带的内容
    public $data;
    // 状态码
    public $code;

    /*
     *
     */
    public function __construct($errorCode, $msg, $data, $code)
    {
        parent::__construct();
        $this->errorCode = $errorCode;
        $this->msg = $msg;
        $this->data = $data;
        $this->code = $code;
    }


}
