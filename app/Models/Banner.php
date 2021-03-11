<?php

namespace App\Models;

class Banner extends BaseModel
{

    // 隐藏字段
    protected $hidden = ['created_at','updated_at','deleted_at'];

    /*
     * 关联BannerItem
     */
    public function items()
    {
        return $this->hasMany(BannerItem::class);
    }

    /*
     * 通过ID获取Banner
     */
    static public function getBannerByID($id)
    {
        return self::with('items.img')
            ->where('id', '=', $id)
            ->first();
    }
}
