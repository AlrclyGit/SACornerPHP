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
    public $errorCode = 2233;
    // 错误具体信息
    public $msg = '默认消息';
    // 附带的内容
    public $data = [];
    // HTTP 状态码
    public $code = 500;

    /*
     *
     */
    public function __construct($params = [])
    {
        parent::__construct();
        if (!is_array($params)) {
            return false;
        } else {
            if (array_key_exists('code', $params)) {
                $this->code = $params['code'];
            }
            if (array_key_exists('errorCode', $params)) {
                $this->errorCode = $params['errorCode'];
            }
            if (array_key_exists('msg', $params)) {
                $this->msg = $params['msg'];
            }
            if (array_key_exists('data', $params)) {
                $this->data = $params['data'];
            }
        }
    }

}
