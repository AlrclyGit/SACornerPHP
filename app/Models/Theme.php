<?php

/**
 * Name: 分类模型
 * User: 萧俊介
 * Date: 2020/9/07
 * Time: 1:06 下午
 * Created by SANewOrangePHP制作委员会.
 */

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Theme extends Model
{
    // 隐藏字段
    protected $hidden = ['topic_img_id', 'head_img_id', 'created_at', 'updated_at', 'deleted_at'];


    /*
     *
     */
    public function topicImg()
    {
        return $this->belongsTo(Image::class, 'topic_img_id', 'id');
    }

    /*
     *
     */
    public function headImg()
    {
        return $this->belongsTo(Image::class, 'head_img_id', 'id');
    }

    /*
    *
    */
    public function products()
    {
        return $this->belongsToMany(Product::class, 'theme_product', 'product_id', 'theme_id');
    }

    /*
     * 通过ID组获取对应的专题
     */
    static function getThemeByIds($ids)
    {
        return self::with(['topicImg', 'headImg'])
            ->whereIn('id', $ids)
            ->get();
    }

    /*
     * 通过ID获取对应专题详细产品
     */
    static public function getThemeWithProducts($id)
    {
        return self::with(['products', 'headImg', 'topicImg'])
            ->where('id', '=', $id)
            ->first();
    }


}
