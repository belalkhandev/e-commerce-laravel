<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateMenuChildsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('menu_childs', function (Blueprint $table) {
            $table->id();
			$table->integer('menu_id')->nullable();
			$table->integer('menu_parent_id')->nullable();
			$table->integer('mega_menu_id')->nullable();
			$table->string('menu_type')->nullable();
			$table->integer('item_id')->nullable();
			$table->text('item_label')->nullable();
			$table->text('custom_url')->nullable();
			$table->string('target_window')->nullable();
			$table->string('css_class')->nullable();
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
        Schema::dropIfExists('menu_childs');
    }
}
