<?php
declare(strict_types = 1);

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateProductImagesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('shop_productimages', function (Blueprint $table) {
            $table->increments('id');

            $table->integer('product_id')->unsigned()
                  ->index()->foreign()->references('id')->on('products');

            $table->integer('file_id')->unsigned()
                  ->index()->foreign()->references('id')->on('uploaded_files');

            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('shop_productimages');
    }
}
