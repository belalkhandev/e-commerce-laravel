<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateOrderItemsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('order_items', function (Blueprint $table) {
            $table->id();
			$table->integer('order_master_id')->nullable();
			$table->integer('customer_id')->nullable();
			$table->integer('seller_id')->nullable();
			$table->integer('product_id')->nullable();
			$table->string('variation_size', 100)->nullable();
			$table->string('variation_color', 100)->nullable();
			$table->integer('quantity')->nullable();
			$table->double('price', 8, 2)->nullable();
			$table->double('total_price', 8, 2)->nullable();
			$table->double('tax', 8, 2)->nullable();
			$table->double('discount', 8, 2)->nullable();
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
        Schema::dropIfExists('order_items');
    }
}
