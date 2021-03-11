<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class UserAddress extends Model
{
    //
    protected $hidden = ['id', 'created_at','updated_at','deleted_at'];
    //
    protected $fillable =['userName','telNumber','provinceName','cityName','countyName','detailInfo'];
}
