<?php

use App\Models\Product\Category;
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
        Schema::dropIfExists('categories');
    }

    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up() {
        Schema::create('categories', function(Blueprint $table) {
            $table->increments('id');

            $table->string('name')->index();

            $table->string('keyword')->index();

            // Nullable so we can add root category.
            $table->integer('parent_id')->unsigned()->nullable()
                  ->index()->foreign('parent_id')->references('id')->on('categories');

            $table->timestamps();
        });

        // Add root category.
        $category = Category::create(['name' => Category::KEYWORD_ROOT, 'keyword' => Category::KEYWORD_ROOT]);
        $category->parent()->associate($category);
        $category->save();

        // Root category added, remove nullable attribute from parent.
        Schema::table('categories', function(Blueprint $table) {
            $table->integer('parent_id')->unsigned()->nullable(false)->change();
        });
    }
}
