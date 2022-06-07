<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateBankInformationsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('bank_informations', function (Blueprint $table) {
            $table->id();
			$table->integer('seller_id')->nullable();
			$table->string('bank_name')->nullable();
			$table->string('bank_code')->nullable();
			$table->string('account_number')->nullable();
			$table->string('account_holder')->nullable();
			$table->string('paypal_id')->nullable();
			$table->text('description')->nullable();
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
        Schema::dropIfExists('bank_informations');
    }
}
