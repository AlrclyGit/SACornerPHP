<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateUserAddressesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('user_addresses', function (Blueprint $table) {
            $table->id();
            $table->string('name')->comment('姓名');
            $table->string('mobile')->comment('电话');
            $table->string('province')->comment('省份');
            $table->string('city')->comment('城市');
            $table->string('country')->comment('地区');
            $table->string('detail')->comment('详细地址');
            $table->string('user_id')->comment('用户UID');
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
        Schema::dropIfExists('user_addresses');
    }
}
