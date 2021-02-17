<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreatePresalesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('presales', function (Blueprint $table) {
            $table->uuid('id')->primary();
            $table->uuid('performance_id')->nullable();
            $table->foreign('performance_id')->references('id')->on('performances')->onDelete('cascade');
            $table->uuid('price_id')->nullable();
            $table->uuid('buyer_id')->nullable();
            $table->foreign('buyer_id')->references('id')->on('buyers')->onDelete('cascade');            
            $table->datetime('created_on')->nullable();
            $table->uuid('account_item_id')->nullable();
            $table->foreign('account_item_id')->references('id')->on('account_items')->onDelete('cascade');    
            $table->float('amount_paid')->nullable();
            $table->float('booking_fee_paid')->nullable();
            $table->float('dramatix_fee_paid')->nullable();
            $table->datetime('processed_on')->nullable();
            $table->uuid('ticket_id')->nullable(); 
            $table->uuid('promo_code_id')->nullable();
            $table->foreign('promo_code_id')->references('id')->on('promo_codes')->onDelete('cascade'); 
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
        Schema::dropIfExists('presales');
    }
}
