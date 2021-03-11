<?php
/**
 * Name: 设置支付状态
 * User: 萧俊介
 * Date: 2020/9/7
 * Time: 11:02 上午
 * Created by SANewOrangePHP制作委员会.
 */

namespace App\Enums;


class OrderStatusEnum
{
    // 待支付
    const UNPAID = 1;
    // 已支付
    const PAID = 2;
    // 已发货
    const DELIVERED = 3;
    // 已支付，但库存不足
    const PAID_BUT_OUT_OF = 4;
}
