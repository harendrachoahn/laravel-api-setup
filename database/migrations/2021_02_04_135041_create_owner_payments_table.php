<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateOwnerPaymentsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('owner_payments', function (Blueprint $table) {
            $table->uuid('id')->primary();
            $table->float('amount')->nullable();
            $table->string('description')->nullable();
            $table->string('name')->nullable();
            $table->datetime('created_on')->nullable();
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
        Schema::dropIfExists('owner_payments');
    }
}
