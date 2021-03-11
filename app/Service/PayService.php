<?php
/**
 * Name:
 * User: 萧俊介
 * Date: 2020/9/7
 * Time: 10:57 上午
 * Created by SANewOrangePHP制作委员会.
 */

namespace App\Service;


use App\Enums\OrderStatusEnum;
use App\Exceptions\BaseExceptions;
use App\Exceptions\OrderException;
use App\Exceptions\TokenException;
use App\Models\Order;
use App\Models\User;


class PayService
{
    //
    private $orderID;
    private $orderNO;

    /*
     * 构造方法
     */
    function __construct($orderID)
    {
        if (!$orderID) {
            throw new BaseExceptions([
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
        // 检测支付来源数据的可靠性
        $this->checkOrderValid();
        // 检测库存信息
        $orderService = new OrderService();
        $statue = $orderService->checkOrderStock($this->orderID);
        if (!$statue['pass']) {
            return $statue;
        }
        // 发送预订单请求
        return $this->makeWxPreOrder($statue['orderPrice']);
    }

    /*
     * 发送预订单请求（拼装数组）
     */
    private function makeWxPreOrder($totalPrice)
    {
        // openid
        $uid = TokenService::getCurrentUid();
        $user = User::find($uid);
        $openid = $user['openid'];
        if (!$openid) {
            throw new TokenException();
        }

        // 发送预订单请求
        $wxOrderData = new \WxPayUnifiedOrder();
        $wxOrderData->SetOut_trade_no($this->orderNO);
        $wxOrderData->SetTrade_type('JSAPI');
        $wxOrderData->SetTotal_fee($totalPrice * 100);
        $wxOrderData->SetBody('漫才文创社');
        $wxOrderData->SetOpenid($openid);
        $wxOrderData->SetNotify_url(config('secure.pay_back_url'));
        //
        $config = new \WxPayConfig();
        //
        return $this->getPaySignature($config, $wxOrderData);
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
            // 写入通知参数
            $this->recordPreOrder($wxOrder);
            // 制作签名
            $signature = $this->sign($wxOrder);
            // 返回
            return $signature;
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
     * 写入通知参数
     */
    private function recordPreOrder($wxOrder)
    {
        $data = [
            'prepay_id' => $wxOrder['prepay_id']
        ];
        $where = [
            'id' => $this->orderID
        ];
        Order::update($data, $where);
    }

    /*
     * 检测支付来源数据的可靠性
     */
    private function checkOrderValid()
    {
        // 订单号根本不存在
        $order = Order::find($this->orderID);
        if (!$order) {
            throw new OrderException();
        }
        // 订单号和当前用户不匹配
        if (!TokenService::isValidOperate($order->user_id)) {
            throw new TokenException([
                'msg' => '订单用户与订单用户不匹配',
                'errorCode' => 80002
            ]);
        }
        // 订单已支付
        if ($order->status != OrderStatusEnum::UNPAID) {
            throw new OrderException([
                'code' => 400,
                'msg' => '订单已支付过啦',
                'errorCode' => 80003,
            ]);
        }
        $this->orderNO = $order->order_no;
        return true;
    }

}
