<?php
/**
 * Name: 验证Code
 * User: 萧俊介
 * Date: 2020/9/1
 * Time: 11:12 上午
 * Created by SANewOrangePHP制作委员会.
 */

namespace App\Http\Requests;


class TokenGet extends BaseRequests
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
            'code' => ['filled','required']
        ];
    }

    /**
     * 错误消息
     */
    public function messages()
    {
        return [
            'code.required' => '没有Code还想获取Token，想的美～',
            'code.filled' => '传我一个空的Code干嘛？'
        ];
    }
}
