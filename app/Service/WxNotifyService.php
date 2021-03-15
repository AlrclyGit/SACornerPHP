<?php
/**
 * Name: 支付成功回调控制器
 * User: 萧俊介
 * Date: 2020/9/7
 * Time: 11:54 上午
 * Created by SANewOrangePHP制作委员会.
 */

namespace App\Service;


use App\Enums\OrderStatusEnum;
use App\Models\Order;
use Illuminate\Support\Facades\DB;

class WxNotifyService extends \WxPayNotify
{

    /*
     * 支付成功回调方法
     */
    public function NotifyProcess($objData, $config, &$msg)
    {
        if ($objData['result_code'] == 'SUCCESS') { // 支付成功
            // 获取订单号
            $orderNo = $objData['out_trade_no'];
            DB::beginTransaction();
            try {
                // 通过订单号查询商品
                $order = Order::where('order_no', $orderNo)->first();
                if ($order->status == 1) { // 商品状态为未支付
                    // 更新商品状态
                    $order = Order::find($order->id);
                    $order->status = OrderStatusEnum::PAID;
                    $order->save();
                }
                DB::commit();
                return true;
            } catch (\Exception $ex) {
                DB::rollBack();
//                Log::error($ex);
                return false;
            }
        } else {
            return true;
        }
    }

}
