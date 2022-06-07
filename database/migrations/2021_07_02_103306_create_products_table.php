<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateProductsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('products', function (Blueprint $table) {
            $table->id();
			$table->text('title')->nullable();
			$table->string('slug')->unique();
			$table->text('f_thumbnail')->nullable();
			$table->text('short_desc')->nullable();
			$table->text('description')->nullable();
			$table->text('extra_desc')->nullable();
			$table->double('cost_price', 8, 2)->nullable();
			$table->double('sale_price', 8, 2)->nullable();
			$table->double('old_price', 8, 2)->nullable();
			$table->date('start_date')->nullable();
			$table->date('end_date')->nullable();
			$table->integer('is_stock')->nullable();
			$table->string('sku')->nullable();
			$table->integer('stock_status_id')->nullable();
			$table->integer('stock_qty')->nullable();
			$table->integer('u_stock_qty')->nullable();
			$table->string('category_ids')->nullable();
			$table->integer('cat_id')->nullable();
			$table->integer('brand_id')->nullable();
			$table->integer('collection_id')->nullable();
			$table->integer('label_id')->nullable();
			$table->text('variation_color')->nullable();
			$table->text('variation_size')->nullable();
			$table->integer('tax_id')->nullable();
			$table->integer('is_featured')->nullable();
			$table->integer('is_publish')->nullable();
			$table->integer('user_id')->nullable();
			$table->string('lan', 100)->nullable();
			$table->text('og_title')->nullable();
			$table->text('og_image')->nullable();
			$table->text('og_description')->nullable();
			$table->text('og_keywords')->nullable();
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
        Schema::dropIfExists('products');
    }
}
