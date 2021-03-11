<?php

namespace App\Models;

class BannerItem extends BaseModel
{

    // 隐藏字段
    protected $hidden = ['id','image_id','banner_id','created_at','updated_at','deleted_at'];


    /*
     * 关联img
     */
    public function img()
    {
        return $this->belongsTo(Image::class);
    }
}
