<?php
/**
 * Name: 商品控制器
 * User: 萧俊介
 * Date: 2020/8/31
 * Time: 10:18 上午
 * Created by SANewOrangePHP制作委员会.
 */

namespace App\Http\Controllers;

use App\Exceptions\ProductExceptions;
use App\Http\Requests\Count;
use App\Http\Requests\IDMustBePositiveInt;
use App\Models\Product;

class ProductController extends Controller
{

    /*
     * 获取最新商品
     */
    public function getRecent(Count $request)
    {
        // 获取过滤过的参数
        $validated = $request->validated();
        // 设置默认的 count 值
        if(empty($validated['count'])){
            $validated['count'] = 15;
        }
        // 获取指定条数的最新商品
        $products = Product::getMostRecent($validated['count']);
        // 错误处理与隐藏字段并返回
        if ($products->isEmpty()) {
            throw new ProductExceptions();
        }
        $products = $products->makeHidden(['summary']);
        return $products;
    }

    /*
     * 获取某分类下的所有商品
     */
    public function getAllInCategory(IDMustBePositiveInt $request)
    {
        // 获取过滤过的参数
        $validated = $request->validated();
        // 通过 分类ID 获取其下的所有商品
        $products = Product::getProductsByCategoryID($validated['id']);
        // 错误处理与隐藏字段并返回
        if ($products->isEmpty()) {
            throw new ProductExceptions();
        }
        $products = $products->makeHidden(['summary']);
        return $products;
    }

    /*
     * 获取某一个商品的详情
     */
    public function getOne(IDMustBePositiveInt $request)
    {
        // 获取过滤过的参数
        $validated = $request->validated();
        // 获取某一个商品的详情
        $product = Product::getProductDetail($validated['id']);
        // 错误处理与隐藏字段并返回
        if (!$product) {
            throw new ProductExceptions();
        }
        return $product;
    }

}
