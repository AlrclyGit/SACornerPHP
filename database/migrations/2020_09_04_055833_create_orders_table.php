<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateOrdersTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('orders', function (Blueprint $table) {
            $table->id();
            $table->string('order_no')->comment('订单号');
            $table->string('user_id')->comment('用户ID');
            $table->string('total_price')->comment('价格');
            $table->string('status')->comment('订单状态');
            $table->string('total_count')->comment('商品总数');
            $table->string('snap_img')->comment('缓存首商品图片');
            $table->string('snap_name')->comment('缓存首商品名');
            $table->string('snap_items')->comment('缓存商品详情');
            $table->string('snap_address')->comment('缓存下单地址');
            $table->string('prepay_id')->comment('通知编号');
            $table->timestamps();
            $table->softDeletes();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('orders');
    }
}
