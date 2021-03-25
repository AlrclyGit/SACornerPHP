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
    public $code;
    // 错误具体信息
    public $msg;
    // 附带的内容
    public $data;
    // 状态码
    public $status;

    /*
     *
     */
    public function __construct($status,...$info)
    {
        parent::__construct();
        $this->code = $info[0];
        $this->msg = $info[1];
        $this->data = $info[2];
        $this->status = $status;
    }


}
