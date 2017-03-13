<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateCartProductsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('cart_products', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('user_id', false, true)->unsigned()->index();
            $table->integer('product_id', false, true)->unsigned()->index();
            $table->integer('quantity')->unsigned();
            $table->timestamps();
            $table->unique(['user_id', 'product_id']);

            $table->foreign('user_id')->references('id')->on('users');
            $table->foreign('product_id')->references('id')->on('shop_products');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('cart_products');
    }
}
