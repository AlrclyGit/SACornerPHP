<?php
/**
 * Name: 商品分类控制器
 * User: 萧俊介
 * Date: 2020/9/1
 * Time: 10:54 上午
 * Created by SANewOrangePHP制作委员会.
 */

namespace App\Http\Controllers;

use App\Exceptions\BaseExceptions;
use App\Models\Category;

class CategoryController extends Controller
{
    /*
     * 获取商品分类
     */
    public function getAllCategories()
    {
        // 获取商品分类
        $categories = Category::with('img')->get();
        // 错误处理与返回
        if($categories->isEmpty()){
            throw new BaseExceptions([
                'code' => 404,
                'errorCode' => 44001,
                'msg' => '请求的分类不存在',
            ]);
        }
        return $categories;
    }
}
