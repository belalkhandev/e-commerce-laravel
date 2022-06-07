<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateProImagesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('pro_images', function (Blueprint $table) {
            $table->id();
			$table->integer('product_id')->nullable();
			$table->text('thumbnail')->nullable();
			$table->text('large_image')->nullable();
			$table->text('desc')->nullable();
			
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
        Schema::dropIfExists('pro_images');
    }
}
