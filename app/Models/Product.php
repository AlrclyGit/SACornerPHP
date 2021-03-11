<?php

/**
 * Name: 商品模型
 * User: 萧俊介
 * Date: 2020/9/07
 * Time: 1:06 下午
 * Created by SANewOrangePHP制作委员会.
 */

namespace App\Models;

class Product extends BaseModel
{
    // 隐藏字段
    protected $hidden = ['img_id','topic_img_id', 'head_img_id', 'main_img_id', 'pivot', 'from', 'category_id', 'created_at', 'updated_at', 'deleted_at'];

    /*
     * url访问器
     */
    public function getMainImgUrlAttribute($url)
    {
        return $this->prefixImgUrl($url, $this->from);
    }

    /*
     * 关联图片模型
     */
    public function images()
    {
        return $this->hasMany(ProductImage::class, 'product_id', 'id')
            ->orderBy('order', 'asc');
    }

    /*
     * 关联商品属性模型
     */
    public function properties()
    {
        return $this->hasMany(ProductProperty::class, 'product_id', 'id');
    }


    /*
     * 获取指定条数的最新商品
     */
    public static function getMostRecent($count)
    {
        return self::orderBy('created_at', 'desc')
            ->limit($count)
            ->get();
    }

    /*
     * 通过 分类ID 获取其下的所有商品
     */
    public static function getProductsByCategoryID($categoryID)
    {
        return self::where('category_id', $categoryID)
            ->get();
    }

    /*
     * 获取某一个商品的详情
     */
    public static function getProductDetail($id)
    {
        return self::with(['images.img','properties'])->find($id);
    }
}
