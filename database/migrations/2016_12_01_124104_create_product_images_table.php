<?php
declare(strict_types = 1);

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateProductImagesTable extends Migration {
    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down() {
        Schema::dropIfExists('shop_productimages');
    }

    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up() {
        Schema::create('shop_productimages', function(Blueprint $table) {
            $table->increments('id');

            $table->integer('file_id')->unsigned()->index();

            $table->timestamps();
        });
    }
}
