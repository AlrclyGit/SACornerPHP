<?php
/**
 * Name: IDs必须为以逗号分隔的整型
 * User: 萧俊介
 * Date: 2020/8/27
 * Time: 1:07 上午
 * Created by SANewOrangePHP制作委员会.
 */

namespace App\Rules;


use Illuminate\Contracts\Validation\Rule;

class CheckIDs implements Rule
{
    /**
     * 验证通过条件定义
     */
    public function passes($attribute, $value)
    {
        $values = explode(',', $value);
        if (empty($values)) {
            return false;
        }
        foreach ($values as $id) {
            if (!(new IsPositiveInteger)->passes($attribute, $id)) {
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
        return ':attribute参数必须是以逗号分隔的多个正整数';
    }
}
