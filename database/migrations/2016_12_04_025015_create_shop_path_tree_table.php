<?php

use App\Models\Shop\Category;
use App\Models\Shop\Path;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateShopPathTreeTable extends Migration {
    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down() {
        Schema::dropIfExists('shop_pathtree');
    }

    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up() {
        Schema::create('shop_pathtree', function(Blueprint $table) {
            $table->increments('id');

            $table->string('pathname', 20);

            $table->integer('parent_id')->unsigned()->nullable()->index();

            $table->integer('component_id')->unsigned();

            $table->string('component_type');

            $table->index(['component_id', 'component_type']);

            $table->timestamps();
        });

        // Add root path for a category.
        $category = Category::create(['name' => '']);
        Path::forceCreate([
            'pathname'       => '',
            'component_id'   => $category->getId(),
            'component_type' => Category::class,
        ]);
    }
}
