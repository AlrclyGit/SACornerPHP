<?php

namespace App\Models;

class Image extends BaseModel
{

    // 隐藏字段
    protected $hidden = ['id', 'from', 'created_at', 'updated_at', 'deleted_at'];

    /*
     * url访问器
     */
    public function getUrlAttribute($url)
    {
        return $this->prefixImgUrl($url, $this->from);
    }

}
