<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreatePerformancesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('performances', function (Blueprint $table) {
            $table->uuid('id')->primary();
            $table->uuid('event_id')->nullable();
            $table->foreign('event_id')->references('id')->on('events')->onDelete('cascade');
            $table->uuid('seller_id')->nullable();
            $table->datetime('starts_at')->nullable();
            $table->integer('tickets_left')->nullable();
            $table->datetime('faxed_at')->nullable();
            $table->datetime('confirmed_at')->nullable();
            $table->integer('faxes_sent')->nullable();
            $table->boolean('warning_sent')->nullable();
            $table->string('special')->nullable();
            $table->string('location')->nullable();
            $table->integer('tickets_count')->default(0);
            $table->integer('presales_count')->default(0);
            $table->uuid('venue_id')->nullable();
            $table->foreign('venue_id')->references('id')->on('venues')->onDelete('cascade');
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
        Schema::dropIfExists('performances');
    }
}
