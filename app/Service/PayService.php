<?php
/**
 * Name: 支付服务
 * User: 萧俊介
 * Date: 2020/9/7
 * Time: 10:57 上午
 * Created by SANewOrangePHP制作委员会.
 */

namespace App\Service;


use App\Enums\OrderStatusEnum;
use App\Exceptions\BaseExceptions;
use App\Models\Order;
use App\Models\User;


class PayService
{
    // 声明变量
    private $orderID;

    /*
     * 构造方法
     */
    function __construct($orderID)
    {
        if (!$orderID) {
            throw new BaseExceptions([
                'errorCode' => 82001,
                'msg' => '订单号不允许为NULL'
            ]);
        }
        $this->orderID = $orderID;
    }

    /*
     * 进行微信支付
     */
    public function pay()
    {
        // 检测支付来源数据的可靠性,并获取订单内容
        $statue = $this->checkOrderValid();
        // 发送预订单请求
        return $this->makeWxPreOrder($statue);
    }

    /*
    * 检测支付来源数据的可靠性
    */
    private function checkOrderValid()
    {
        // 订单号根本不存在
        $order = Order::find($this->orderID);
        if (!$order) {
            throw new BaseExceptions([
                'errorCode' => 82002,
                'msg' => '订单不存在，请检查ID'
            ]);
        }
        // 订单号和当前用户不匹配
        if (!TokenService::isValidOperate($order->user_id)) {
            throw new BaseExceptions([
                'errorCode' => 82002,
                'msg' => '订单用户与订单用户不匹配'
            ]);
        }
        // 订单已支付
        if ($order->status != OrderStatusEnum::UNPAID) {
            throw new BaseExceptions([
                'errorCode' => 82003,
                'msg' => '订单已支付过啦',
            ]);
        }
        return $order;
    }

    /*
     * 发送预订单请求（拼装数组）
     */
    private function makeWxPreOrder($statue)
    {
        // 获取用户Uid
        $uid = TokenService::getCurrentUid();
        // 获取用户信息
        $user = User::find($uid);
        // 判断用户是否存在
        if (!$user['openid']) {
            throw new BaseExceptions([
                'errorCode' => 82004,
                'msg' => '用户OpenID不存在',
            ]);
        }
        // 拼装预订单请求参数
        $wxOrderData = new \WxPayUnifiedOrder();
        $wxOrderData->SetOut_trade_no($statue['order_no']);
        $wxOrderData->SetTrade_type('JSAPI');
        $wxOrderData->SetTotal_fee($statue['orderPrice'] * 100);
        $wxOrderData->SetBody('漫才文创社');
        $wxOrderData->SetOpenid($user['openid']);
        $wxOrderData->SetNotify_url(config('wx.pay_back_url'));
        // 获取微信支付配置
        $wxPayConfig = new \WxPayConfig();
        // 发送预订单请求（请求处理）
        return $this->getPaySignature($wxPayConfig, $wxOrderData);
    }

    /*
     * 发送预订单请求（请求处理）
     */
    private function getPaySignature($config, $wxOrderData)
    {
        // 发送预定请求到微信
        $wxOrder = \WxPayApi::unifiedOrder($config, $wxOrderData);
        // 判定
        if ($wxOrder['return_code'] != 'SUCCESS' || $wxOrder['result_code'] != 'SUCCESS') { //预订单生成失败
//            Log::record($wxOrder, 'error');
//            Log::record('获取预订单失败', 'error');
            return $wxOrder;
        } else { // 预订单生成成功
            // 写入微信回调参数
            $this->recordPreOrder($wxOrder);
            // 制作签名
            return $this->sign($wxOrder);
        }
    }

    /*
     * 签名方法
     */
    private function sign($wxOrder)
    {
        $jsApiPayData = new \WxPayJsApiPay();
        $jsApiPayData->SetAppid(config('wx.app_id'));
        $jsApiPayData->SetTimeStamp((string)time());
        $jsApiPayData->SetNonceStr(getRandChar(4));
        $jsApiPayData->SetPackage('prepay_id=' . $wxOrder['prepay_id']);
        $jsApiPayData->SetSignType('md5');
        $config = new \WxPayConfig();
        $sign = $jsApiPayData->MakeSign($config);
        $rawValues = $jsApiPayData->GetValues();
        $rawValues['paySign'] = $sign;
        unset($rawValues['appId']);
        return $rawValues;
    }

    /*
     * 写入微信回调参数
     */
    private function recordPreOrder($wxOrder)
    {
        $order = Order::find($this->orderID);
        $order->prepay_id = $wxOrder['prepay_id'];
        $order->save();
    }


}
