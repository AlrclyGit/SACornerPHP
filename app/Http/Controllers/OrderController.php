<?php
/**
 * Name: 订单控制器
 * User: 萧俊介
 * Date: 2020/9/4
 * Time: 11:41 上午
 * Created by SANewOrangePHP制作委员会.
 */

namespace App\Http\Controllers;

use App\Exceptions\OrderException;
use App\Http\Requests\IDMustBePositiveInt;
use App\Http\Requests\OrderPlace;
use App\Models\Order;
use App\Service\OrderService;
use App\Service\TokenService;
use Illuminate\Http\Request;

class OrderController extends Controller
{


    /*
     * 订单记录详情
     */
    public function getDetail(IDMustBePositiveInt $request)
    {
        // 获取过滤过的参数
        $validated = $request->validated();
        // 获取订单记录详情
        $orderDetail = Order::find($validated['id']);
        // 错误处理与隐藏字段并返回
        if (!$orderDetail) {
            throw new OrderException();
        }
        return $orderDetail;
    }

    /*
     * 生成订单并产生一个订单ID
     */
    public function placeOrder(OrderPlace $request)
    {
        // 获取过滤过的参数
        $validated = $request->validated();
        // 获取用户Uid
        $uid = TokenService::getCurrentUid();
        // 实例化一个订单类
        $orderS = new OrderService();
        // 调用生成订单的方法，并传入 Uid 和 商品信息
        $status = $orderS->place($uid, $validated['products']);
        // 返回
        return saReturn($status);
    }

    /*
     * 获取某个用户的订单
     */
    public function getSummaryByUser(Request $request)
    {
        // 获取过滤过的参数
        $validated = $request->all();
        // 获取用户Uid
        $uid = TokenService::getCurrentUid();
        // 实例化一个订单类
        $orderM = new Order();
        // 调用生成订单的方法，并传入 Uid 和 商品信息
        $status = $orderM->where('user_id', $uid)->orderBy('created_at', 'DESC')->paginate($validated['listRows']);
        // 返回
        return saReturn($status);
    }
}