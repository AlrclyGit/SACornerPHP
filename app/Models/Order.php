<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Order extends Model
{
    // 隐藏字段
    protected $hidden = ['updated_at','deleted_at'];

    /*
     *
     */
    public function getSnapItemsAttribute($value)
    {
        if(empty($value)){
            return null;
        }
        return json_decode($value);
    }

    /*
     *
     */
    public function getSnapAddressAttribute($value)
    {
        if(empty($value)){
            return null;
        }
        return json_decode($value);
    }
}
