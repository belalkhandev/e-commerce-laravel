<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateOrderMastersTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('order_masters', function (Blueprint $table) {
            $table->id();
			$table->string('order_no', 100)->nullable();
			$table->string('transaction_no', 100)->nullable();
			$table->integer('customer_id')->nullable();
			$table->integer('seller_id')->nullable();
			$table->integer('payment_method_id')->nullable();
			$table->integer('payment_status_id')->nullable();
			$table->integer('order_status_id')->nullable();
			$table->integer('total_qty')->nullable();
			$table->double('total_price', 8, 2)->nullable();
			$table->double('discount', 8, 2)->nullable();
			$table->double('tax', 8, 2)->nullable();
			$table->double('subtotal', 8, 2)->nullable();
			$table->double('total_amount', 8, 2)->nullable();
			$table->text('shipping_title')->nullable();
			$table->double('shipping_fee', 8, 2)->nullable();
			$table->string('name')->nullable();
			$table->string('email')->nullable();
			$table->string('phone')->nullable();
			$table->string('country')->nullable();
			$table->string('state')->nullable();
			$table->string('zip_code')->nullable();
			$table->string('city')->nullable();
			$table->text('address')->nullable();
			$table->text('comments')->nullable();
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
        Schema::dropIfExists('order_masters');
    }
}
