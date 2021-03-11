<?php
/**
 * Name: IDs必须为整型
 * User: 萧俊介
 * Date: 2020/8/27
 * Time: 1:06 上午
 * Created by SANewOrangePHP制作委员会.
 */

namespace App\Http\Requests;


use App\Rules\CheckIDs;

class IDCollection extends BaseRequests
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
            'ids' => ['required', new CheckIDs()]
        ];
    }

    /**
     * 错误消息
     */
    public function messages()
    {
        return [
            'ids.required' => 'ids不能为空'
        ];
    }

}
