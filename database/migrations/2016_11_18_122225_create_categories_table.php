<?php

use App\Models\Shop\Category;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateCategoriesTable extends Migration {
    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down() {
        Schema::dropIfExists('shop_categories');
    }

    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up() {
        Schema::create('shop_categories', function(Blueprint $table) {
            $table->increments('id');

            $table->string('name')->index();

            $table->text('description');

            $table->integer('parent_id')->unsigned()->nullable()->index();

            $table->timestamps();
        });
    }
}
