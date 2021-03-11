<?php
/**
 * Name:
 * User: 萧俊介
 * Date: 2020/9/4
 * Time: 11:52 上午
 * Created by SANewOrangePHP制作委员会.
 */

namespace App\Rules;

use Illuminate\Contracts\Validation\Rule;

class CheckProduct implements Rule
{

    /**
     * 验证通过条件定义
     */
    public function passes($attribute, $value)
    {
        foreach ($value as $v) {
            foreach ($v as $data)
                if (!(new IsPositiveInteger)->passes($attribute, $data)) {
                    return false;
                }
        }
        return true;
    }

    /**
     * 错误消息
     */
    public function message()
    {
        return ':attribute参数错误';
    }
}
