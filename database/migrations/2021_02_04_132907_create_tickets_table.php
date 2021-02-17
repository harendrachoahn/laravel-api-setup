<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateTicketsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('tickets', function (Blueprint $table) {
            $table->uuid('id')->primary();
            $table->uuid('performance_id')->nullable();
            $table->uuid('price_id')->nullable();
            $table->uuid('buyer_id')->nullable();
            $table->string('code')->nullable();
            $table->datetime('created_on')->nullable();
            $table->integer('serial')->nullable();
            $table->uuid('account_item_id')->nullable();
            $table->string('image')->nullable();
            $table->float('amount_paid')->nullable();
            $table->float('booking_fee_paid')->nullable();
            $table->float('dramatix_fee_paid')->nullable();
            $table->string('precode')->nullable();
            $table->uuid('seat_id')->nullable();
            $table->uuid('promo_code_id')->nullable();
            $table->timestamps();

            $table->foreign('performance_id')->references('id')->on('performances')->onDelete('cascade');
            $table->foreign('price_id')->references('id')->on('prices')->onDelete('cascade');
            $table->foreign('buyer_id')->references('id')->on('buyers')->onDelete('cascade');
            $table->foreign('account_item_id')->references('id')->on('account_items')->onDelete('cascade');
            $table->foreign('seat_id')->references('id')->on('seats')->onDelete('cascade');
            $table->foreign('promo_code_id')->references('id')->on('promo_codes')->onDelete('cascade');

        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('tickets');
    }
}
