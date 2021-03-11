<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateProductsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('products', function (Blueprint $table) {
            $table->id();
            $table->string('name')->comment('商品名');
            $table->string('price')->comment('商品价格');
            $table->string('stock')->comment('商品数量');
            $table->string('category_id')->comment('商品分类');
            $table->string('main_img_url')->comment('商品图');
            $table->string('from')->comment('商品类型');
            $table->string('summary')->comment('');
            $table->string('img_id')->comment('商品图ID');
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
        Schema::dropIfExists('products');
    }
}
