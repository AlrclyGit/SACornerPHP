<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Category extends Model
{
    // 隐藏字段
    protected $hidden = ['topic_img_id','created_at','updated_at','deleted_at'];

    /*
     * 关联img
     */
    public function img()
    {
        return $this->belongsTo(Image::class,'topic_img_id','id');
    }

}
