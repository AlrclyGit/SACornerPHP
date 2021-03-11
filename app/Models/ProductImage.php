<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ProductImage extends Model
{

    //
    protected $hidden = ['img_id','product_id', 'id', 'created_at', 'updated_at', 'deleted_at'];

    /*
     * 关联img
     */
    public function img()
    {
        return $this->belongsTo(Image::class);
    }
}
