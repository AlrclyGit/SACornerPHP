<?php

/**
 * Name: 商品属性模型
 * User: 萧俊介
 * Date: 2020/9/07
 * Time: 1:06 下午
 * Created by SANewOrangePHP制作委员会.
 */

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ProductProperty extends Model
{
    //
    protected $hidden = ['id','product_id','created_at', 'updated_at', 'deleted_at'];
}
