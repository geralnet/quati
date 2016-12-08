<?php
declare(strict_types = 1);

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateProductsTable extends Migration {
    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down() {
        Schema::dropIfExists('shop_products');
    }

    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up() {
        Schema::create('shop_products', function(Blueprint $table) {
            $table->increments('id');

            $table->string('name')->index();

            $table->string('keyword')->index();

            $table->text('description');

            $table->decimal('price');

            $table->integer('category_id')->unsigned()->nullable()->index(); // FIXME remove nullable

            $table->timestamps();
        });
    }
}
