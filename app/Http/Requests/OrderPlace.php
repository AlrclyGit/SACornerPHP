<?php
/**
 * Name:
 * User: 萧俊介
 * Date: 2020/9/4
 * Time: 11:43 上午
 * Created by SANewOrangePHP制作委员会.
 */

namespace App\Http\Requests;


use App\Rules\IsPositiveInteger;

class OrderPlace extends BaseRequests
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
            'products' => ['array', 'required'],
            'products.*.product_id' => ['required', new IsPositiveInteger()],
            'products.*.count' => ['required', new IsPositiveInteger()],
        ];
    }

    /**
     * 自定义字段名
     */
    public function attributes()
    {
        return [
            'products' => '商品列表'
        ];
    }

    /**
     * 错误消息
     */
    public function messages()
    {
        return [
            'products.array' => '商品列表必须为数组',
            'products.required' => '商品列表不能为空',
            'products.*.product_id.required' => 'product_id不能为空',
            'products.*.count.required' => 'count不能为空',
        ];
    }
}
