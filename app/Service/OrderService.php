<?php
/**
 * Name: 订单服务层
 * User: 萧俊介
 * Date: 2020/9/4
 * Time: 1:30 下午
 * Created by SANewOrangePHP制作委员会.
 */

namespace App\Service;


use App\Exceptions\BaseExceptions;
use App\Models\Order;
use App\Models\OrderProduct;
use App\Models\Product;
use App\Models\UserAddress;
use Illuminate\Support\Facades\DB;

class OrderService
{

    // 订单的商品列表，客户传递过来的
    protected $oProducts;
    // 订单的商品列表，数据库查询出来的
    protected $products;
    // Uid
    protected $uid;

    /*
     * 添加订单流程流程方法
     */
    public function place($uid, $oProducts)
    {
        // 用户传递过来的数据
        $this->oProducts = $oProducts;
        // 获取数据库里的数据
        $this->products = $this->getProductsByOrder($oProducts);
        // 设置当前用户UID
        $this->uid = $uid;
        // 获取数据对比后的数据集合（商品是否存在，库存是否足够）
        $status = $this->getOrderStatus();
        // 生成订单快照
        $orderSnap = $this->snapOrder($status);
        // 添加一个订单
        $order = $this->createOrder($orderSnap);
        // 返回
        return $order;
    }

    /*
     * 获取数据库里的数据
     */
    private function getProductsByOrder($oProducts)
    {
        foreach ($oProducts as $item) {
            array_push($oPIDs, $item['product_id']);
        }
        return Product::whereIn('id', $oPIDs)->get();
    }

    /*
     * 获取数据对比后的数据集合
     */
    private function getOrderStatus()
    {
        // 订单内商品数量超过库存标记
        $status['pass'] = true;
        // 循环订单取出数据
        foreach ($this->oProducts as $oProduct) {
            // 传过来的数据与数据库里的数据对比,单个商品(商品ID，数量，数据库数据)
            $pStatus = $this->getProductStatus($oProduct['product_id'], $oProduct['count'], $this->products);
            // 判断订单内某商品数量是否超过库存标记
            if (!$pStatus['haveStock']) {
                // 商品数量超过库存，标记一下
                $status['pass'] = false;
            }
            // 将单个商品结果添加到数组
            $status['orderPrice'] += $pStatus['totalPrice']; // 订单商品总价
            $status['totalCount'] += $pStatus['counts']; // 订单商品总数量
            $status['pStatusArray'][] = $pStatus; // 订单内商品详情（包含所有数据）
        }
        // 判断订单内是否有某商品数量是否超过库存
        if (!$status['pass']) {
            throw new BaseExceptions([
                'errorCode' => 81003,
                'msg' => '某商品库存不足',
                'data' => $status
            ]);
        }
        return $status;
    }

    /*
     * 传过来的数据与数据库里的数据对比
     */
    private function getProductStatus($oPID, $oCount, $products)
    {
        //判断商品是否存在标记，默认-1不存在
        $pIndex = -1;
        // 判断商品是否存在
        for ($i = 0; $i < count($products); $i++) {
            if ($oPID == $products[$i]['id']) {
                $pIndex = $i;
            }
        }
        // 判断是否存在
        if ($pIndex == -1) {
            // 商品不存在
            throw new BaseExceptions([
                'errorCode' => 81001,
                'msg' => 'ID为' . $oPID . '商品不存在，创建订单失败'
            ]);
        }
        // 商品存在
        $product = $products[$pIndex]; // 商品索引
        $pStatue['id'] = $product['id']; // 商品ID
        $pStatue['name'] = $product['name']; // 商品名称
        $pStatue['counts'] = $oCount; // 商品数量
        $pStatue['price'] = $product['price']; // 商品单格
        $pStatue['main_img_url'] = $product['main_img_url']; // 商品图片
        $pStatue['totalPrice'] = $product['price'] * $oCount; // 商品总价
        // 判断商品是否足够
        if ($product['stock'] - $oCount >= 0) {
            $pStatue['haveStock'] = true;
        }
        return $pStatue;
    }

    /*
     * 生成订单快照
     */
    private function snapOrder($status)
    {
        // 订单添加地址
        $status['snapAddress'] = json_encode($this->getUserAddress());
        // 订单添加默认展示商品
        $status['snapName'] = $this->products[0]['name'];
        if (count($this->products) > 1) {
            $status['snapName'] .= '等';
        }
        $status['snapImg'] = $this->products[0]['main_img_url'];
        // 返回
        return $status;
    }

    /*
     * 获取当前用户地址
     */
    private function getUserAddress()
    {
        $userAddress = UserAddress::where('user_id', '=', $this->uid)->first();
        if (!$userAddress) {
            throw new BaseExceptions([
                'msg' => '用户收货地址不存在，下单失败',
                'errorCode' => 81002
            ]);
        }
        return $userAddress;
    }

    /*
    * 添加一个订单
    */
    private function createOrder($snap)
    {
        // 开启事务
        DB::beginTransaction();
        try {
            //
            $orderNo = date('Y') . strtoupper(dechex(date('m'))) . date('d') . substr(time(), -5) . sprintf('%02d', rand(0, 999)); // 生成订单号
            $order = new Order(); // 订单模型
            $order->user_id = $this->uid; // 用户ID
            $order->order_no = $orderNo; // 订单号
            $order->total_price = $snap['orderPrice']; // 订单总价格
            $order->total_count = $snap['totalCount']; // 订单商品数量
            $order->snap_img = $snap['snapImg']; // 首商品图
            $order->snap_name = $snap['snapName']; // 首商品名
            $order->snap_address = $snap['snapAddress']; // 地址快照
            $order->snap_items = json_encode($snap['pStatus']); // 商品快照
            $order->status = 1; // 订单状态
            $order->save(); // 保存订单
            // 获取订单ID和订单生成时间
            $orderId = $order->id;
            $created_at = $order->created_at;
            // 添加商品到副表
            foreach ($this->oProducts as $p) {
                $p['order_id'] = $orderId;
                OrderProduct::create($p);
            }
            // 提交事物
            DB::commit();
            //
            return [
                'order_no' => $orderNo,
                'order_id' => $orderId,
                'created_at' => $created_at,
            ];
        } catch (\Exception $e) {
            // 错误处理，回滚
            DB::rollBack();
            throw $e;
        }
    }


    /*
     * 通过用户提交的数据，获取数据库数据
     */
    public function checkOrderStock($orderID)
    {
        $oProducts = OrderProduct::where('order_id', $orderID)->get();
        $this->oProducts = $oProducts;
        $this->products = $this->getProductsByOrder($oProducts);
        $status = $this->getOrderStatus();
        return $status;
    }


}
