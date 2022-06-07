<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateMediaOptionsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('media_options', function (Blueprint $table) {
            $table->id();
			$table->text('title')->nullable();
			$table->text('alt_title')->nullable();
			$table->text('thumbnail')->nullable();
			$table->text('large_image')->nullable();
			$table->longText('option_value')->nullable();
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
        Schema::dropIfExists('media_options');
    }
}
