<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateOfferAdsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('offer_ads', function (Blueprint $table) {
            $table->id();
			$table->string('offer_ad_type')->nullable();
			$table->text('url')->nullable();
			$table->text('image')->nullable();
			$table->text('desc')->nullable();
			$table->integer('is_publish')->nullable();
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
        Schema::dropIfExists('offer_ads');
    }
}
