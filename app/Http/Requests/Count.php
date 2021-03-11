<?php
/**
 * Name:
 * User: 萧俊介
 * Date: 2020/8/31
 * Time: 2:59 下午
 * Created by SANewOrangePHP制作委员会.
 */

namespace App\Http\Requests;


use App\Rules\IsPositiveInteger;

class Count extends BaseRequests
{

    /**
     * 权限判断
     */
    public function authorize()
    {
        return true;
    }

    /**
     * 过滤规则
     */
    public function rules()
    {
        return [
            'count' => [new IsPositiveInteger(),'between:1,15']
        ];
    }

    /**
     * 错误消息
     */
    public function messages()
    {
        return [
            'count.between' => 'count不能大于15'
        ];
    }
}
