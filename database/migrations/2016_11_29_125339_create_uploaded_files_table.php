<?php
declare(strict_types = 1);

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateUploadedFilesTable extends Migration {
    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down() {
        Schema::dropIfExists('uploaded_files');
    }

    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up() {
        Schema::create('uploaded_files', function(Blueprint $table) {
            $table->increments('id');

            $table->string('real_path', 42);

            $table->string('logical_path');

            $table->integer('owner_id')->unsigned()->nullable();

            $table->string('owner_type')->nullable();

            $table->unique(['owner_id', 'owner_type']);

            $table->timestamps();
        });
    }
}
