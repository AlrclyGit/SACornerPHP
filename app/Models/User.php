<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class User extends Model
{
    //
    protected $hidden = ['id','openid','session_key','created_at','updated_at','deleted_at'];

    /*
     * 关联用户的收货地址表
     */
    public function address()
    {
        return $this->hasOne(UserAddress::class, 'user_id', 'id');
    }
}
