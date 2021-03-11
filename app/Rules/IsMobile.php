<?php
/**
 * Name:
 * User: 萧俊介
 * Date: 2020/9/4
 * Time: 10:25 上午
 * Created by SANewOrangePHP制作委员会.
 */

namespace App\Rules;


use Illuminate\Contracts\Validation\Rule;

class IsMobile implements Rule
{
    /**
     * 验证通过条件定义
     */
    public function passes($attribute, $value)
    {
        $rule = '^1(3|4|5|7|8)[0-9]\d{8}$^';
        $result = preg_match($rule, $value);
        if ($result) {
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
        return ':attribute参数的电话格式错误';
    }
}
