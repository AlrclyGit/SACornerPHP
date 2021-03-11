<?php
/**
 * Name:
 * User: 萧俊介
 * Date: 2020/9/7
 * Time: 10:55 上午
 * Created by SANewOrangePHP制作委员会.
 */

namespace App\Http\Controllers;


use App\Http\Requests\IDMustBePositiveInt;
use App\Service\PayService;
use App\Service\WxNotifyService;

class PayController extends Controller
{

    /*
     * 微信预付费订单接口
     */
    public function getPreOrder(IDMustBePositiveInt $request)
    {
        // 获取过滤过的参数
        $validated = $request->validated();
        // 实例化一个支付类，并传入订单号
        $payS = new PayService($validated['id']);
        // 调用支付方法，并返回
        return $payS->pay();
    }

    /*
     * 微信支付回调接口
     */
    public function receiveNotify()
    {
        //
        $notify = new WxNotifyService();
        $config = new \WxPayConfig();
        $notify->Handle($config);
    }

}
