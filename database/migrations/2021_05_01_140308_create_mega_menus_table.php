<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateMegaMenusTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('mega_menus', function (Blueprint $table) {
            $table->id();
			$table->integer('menu_id')->nullable();
			$table->integer('menu_parent_id')->nullable();
			$table->text('mega_menu_title')->nullable();
			$table->integer('is_title')->nullable();
			$table->integer('is_image')->nullable();
			$table->text('image')->nullable();
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
        Schema::dropIfExists('mega_menus');
    }
}
