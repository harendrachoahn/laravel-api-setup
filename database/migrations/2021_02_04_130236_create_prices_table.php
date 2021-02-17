<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreatePricesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('prices', function (Blueprint $table) {
            $table->uuid('id')->primary();
            $table->uuid('event_id')->nullable();
            $table->foreign('event_id')->references('id')->on('events')->onDelete('cascade'); 
            $table->string('name')->nullable();
            $table->float('amount')->nullable();
            $table->integer('group_size')->nullable();
            $table->boolean('offer')->nullable();
            $table->string('access_code')->nullable();
            $table->uuid('venue_id')->nullable();
            $table->foreign('venue_id')->references('id')->on('venues')->onDelete('cascade');
            $table->uuid('zone_id')->nullable();
            $table->foreign('zone_id')->references('id')->on('zones')->onDelete('cascade');
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
        Schema::dropIfExists('prices');
    }
}
