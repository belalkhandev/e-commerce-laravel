<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateMenuParentsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('menu_parents', function (Blueprint $table) {
            $table->id();
			$table->integer('menu_id')->nullable();
			$table->string('menu_type')->nullable();
			$table->string('child_menu_type')->nullable();
			$table->integer('item_id')->nullable();
			$table->text('item_label')->nullable();
			$table->text('custom_url')->nullable();
			$table->string('target_window')->nullable();
			$table->string('css_class')->nullable();
			$table->integer('column')->nullable();
			$table->string('width_type')->nullable();
			$table->integer('width')->nullable();
			$table->string('lan')->nullable();
			$table->integer('sort_order')->nullable();
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
        Schema::dropIfExists('menu_parents');
    }
}
