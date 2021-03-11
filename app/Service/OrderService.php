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
     * 集合数据
     */
    public function place($uid, $oProducts)
    {
        // 用户传递过来的数据
        $this->oProducts = $oProducts;
        // 获取数据库里的数据
        $this->products = $this->getProductsByOrder($oProducts);
        // 当前用户UID
        $this->uid = $uid;
        // 获取数据对比后的数据集合
        $status = $this->getOrderStatus();
        if (!$status['pass']) {
            $status['order_id'] = -1;
            return $status;
        }
        // 生成订单快照
        $orderSnap = $this->snapOrder($status);
        // 添加一个订单
        $order = $this->createOrder($orderSnap);
        $order['pass'] = true;
        // 返回
        return $order;
    }

    /*
    * 添加一个订单
    */
    private function createOrder($snap)
    {
        DB::beginTransaction();
        try {
            //
            $orderNo = self::makeOrderNo();
            $order = new Order();
            $order->user_id = $this->uid;
            $order->order_no = $orderNo;
            $order->total_price = $snap['orderPrice'];
            $order->total_count = $snap['totalCount'];
            $order->snap_img = $snap['snapImg'];
            $order->snap_name = $snap['snapName'];
            $order->snap_address = $snap['snapAddress'];
            $order->snap_items = json_encode($snap['pStatus']);
            $order->status = 1;
            $order->save();

            //
            $orderId = $order->id;
            $created_at = $order->created_at;

            //
            foreach ($this->oProducts as $p) {
                $p['order_id'] = $orderId;
                OrderProduct::create($p);
            }
            DB::commit();
            //
            return [
                'order_no' => $orderNo,
                'order_id' => $orderId,
                'created_at' => $created_at
            ];
        } catch (\Exception $e) {
            DB::rollBack();
            throw $e;
        }
    }

    /*
     * 生成订单号
     */
    public static function makeOrderNo()
    {
        $yCode = ['A', 'B', 'C', 'D', 'E', 'F', 'G', 'H', 'I', 'J'];
        $orderSn = $yCode[intval(date('Y')) - 2020] . strtoupper(dechex(date('m'))) . date('d') . substr(time(), -5) . substr(microtime(), 2, 5) . sprintf('%02d', rand(0, 99));
        return $orderSn;
    }

    /*
     * 生成订单快照
     */
    private
    function snapOrder($status)
    {
        $snap = [
            'orderPrice' => 0,
            'totalCount' => 0,
            'pStatus' => [],
            'snapAddress' => null,
            'snapName' => '',
            'snapImg' => ''
        ];

        $snap['orderPrice'] = $status['orderPrice'];
        $snap['totalCount'] = $status['totalCount'];
        $snap['pStatus'] = $status['pStatusArray'];
        $snap['snapAddress'] = json_encode($this->getUserAddress());
        $snap['snapName'] = $this->products[0]['name'];
        $snap['snapImg'] = $this->products[0]['main_img_url'];
        if (count($this->products)) {
            $snap['snapName'] .= '等';
        }
        return $snap;
    }

    /*
     * 获取当前用户地址
     */
    private
    function getUserAddress()
    {
        $userAddress = UserAddress::where('user_id', '=', $this->uid)->first();
        if (!$userAddress) {
            throw new BaseExceptions([
                'msg' => '用户收货地址不存在，下单失败',
                'errorCode' => 60001
            ]);
        }
        return $userAddress;
    }

    /*
     * 获取数据对比后的数据集合
     */
    private
    function getOrderStatus()
    {
        $status = [
            'pass' => true,
            'orderPrice' => 0,
            'totalCount' => 0,
            'pStatusArray' => []
        ];

        foreach ($this->oProducts as $oProduct) {
            $pStatus = $this->getProductStatus($oProduct['product_id'], $oProduct['count'], $this->products);
            if (!$pStatus['haveStock']) {
                $status['pass'] = false;
            }
            $status['orderPrice'] += $pStatus['totalPrice'];
            $status['totalCount'] += $pStatus['counts'];
            $status['pStatusArray'][] = $pStatus;
        }
        return $status;
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

    /*
     * 传过来的数据与数据库里的数据对比
     */
    private
    function getProductStatus($oPID, $oCount, $products)
    {
        $pIndex = -1;

        $pStatue = [
            'id' => null,
            'haveStock' => false,
            'counts' => 0,
            'price' => 0,
            'name' => '',
            'totalPrice' => 0,
            'main_img_url' => null
        ];

        for ($i = 0; $i < count($products); $i++) {
            if ($oPID == $products[$i]['id']) {
                $pIndex = $i;
            }
        }

        if ($pIndex == -1) {
            throw new BaseExceptions([
                'msg' => 'ID为' . $oPID . '商品不存在，创建订单失败'
            ]);
        } else {
            $product = $products[$pIndex];
            $pStatue['id'] = $product['id'];
            $pStatue['name'] = $product['name'];
            $pStatue['counts'] = $oCount;
            $pStatue['price'] = $product['price'];
            $pStatue['main_img_url'] = $product['main_img_url'];
            $pStatue['totalPrice'] = $product['price'] * $oCount;
            if ($product['stock'] - $oCount >= 0) {
                $pStatue['haveStock'] = true;
            }
        }
        return $pStatue;
    }

    /*
    * 获取数据库里的数据
    */
    private function getProductsByOrder($oProducts)
    {
        $oPIDs = [];
        foreach ($oProducts as $item) {
            array_push($oPIDs, $item['product_id']);
        }
        $products = Product::whereIn('id', $oPIDs)->get();
        return $products;
    }

}
