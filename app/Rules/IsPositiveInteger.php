<?php
/**
 * Name: 值是否为正整数
 * User: 萧俊介
 * Date: 2020/8/25
 * Time: 11:34 上午
 * Created by SANewOrangePHP制作委员会.
 */

namespace App\Rules;


use Illuminate\Contracts\Validation\Rule;

class IsPositiveInteger implements Rule
{

    /**
     * 验证通过条件定义
     */
    public function passes($attribute, $value)
    {
        if (is_numeric($value) && is_int($value + 0) && ($value + 0) > 0) {
            return true;
        } else {
            return false;
        }
    }

    /**
     * 错误消息
     */
    public function message()
    {
        return ':attribute必须为正整数';
    }
}