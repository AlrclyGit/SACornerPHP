<?php
/**
 * Name: ID是否为正整数
 * User: 萧俊介
 * Date: 2020/8/25
 * Time: 11:28 上午
 * Created by SANewOrangePHP制作委员会.
 */

namespace App\Http\Requests;


use App\Rules\IsPositiveInteger;

class IDMustBePositiveInt extends BaseRequests
{

    protected $routeData = ['id'];

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
            'id' => ['required', new IsPositiveInteger]
        ];
    }

    /**
     * 自定义字段名
     */
    public function attributes()
    {
        return [
            'id' => 'ID'
        ];
    }

    /**
     * 错误消息
     */
    public function messages()
    {
        return [
            'id.required' => 'ID不能为空'
        ];
    }

}
