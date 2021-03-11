<?php
/**
 * Name:
 * User: 萧俊介
 * Date: 2020/9/7
 * Time: 11:54 上午
 * Created by SANewOrangePHP制作委员会.
 */

namespace App\Service;


use App\Enums\OrderStatusEnum;
use App\Models\Order;
use App\Models\Product;
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
                    // 检测商品库存状态
                    $service = new OrderService();
                    $stockStatus = $service->checkOrderStock($order->id);
                    if ($stockStatus['pass']) { // 通过库存检测
                        // 更新商品状态
                        $this->updateOrderStatus($order->id, true);
                        // 更新库存数量
                        $this->reduceStock($stockStatus);
                    } else { // 未通过库存检测
                        // 更新商品状态
                        $this->updateOrderStatus($order->id, false);
                    }
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

    /*
     * 更新商品状态
     */
    private function updateOrderStatus($orderID, $success)
    {
        $statue = $success ? OrderStatusEnum::PAID : OrderStatusEnum::PAID_BUT_OUT_OF;
        $data = [
            'status' => $statue
        ];
        $where = [
            'id' => $orderID
        ];
        Order::update($data, $where);
    }

    /*
     * 更新库存数量
     */
    private function reduceStock($stockStatus)
    {
        foreach ($stockStatus['pStatusArray'] as $singlePStatus) {
            Product::where('id', $singlePStatus['id'])->decrement('stock', $singlePStatus['count']);
        }
    }
}
