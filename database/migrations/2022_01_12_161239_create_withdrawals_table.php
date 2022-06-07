<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateWithdrawalsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('withdrawals', function (Blueprint $table) {
            $table->id();
			$table->integer('seller_id')->nullable();
			$table->double('amount', 8, 2)->nullable();
			$table->double('fee_amount', 8, 2)->nullable();
			$table->string('payment_method')->nullable();
			$table->string('transaction_id')->nullable();
			$table->text('description')->nullable();
			$table->integer('status_id')->nullable();
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
        Schema::dropIfExists('withdrawals');
    }
}
