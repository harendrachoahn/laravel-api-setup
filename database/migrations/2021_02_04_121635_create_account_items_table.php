<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateAccountItemsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('account_items', function (Blueprint $table) {
            $table->uuid('id')->primary();
            $table->uuid('seller_id')->nullable();
            $table->uuid('buyer_id')->nullable();
            $table->string('reference')->nullable();
            $table->datetime('created_on')->nullable();
            $table->boolean('succeeded')->nullable();
            $table->uuid('transaction_id')->nullable();
            $table->string('authorisation_code')->nullable();
            $table->uuid('statement_id')->nullable();
            $table->uuid('performance_id')->nullable();
            $table->float('buyer_amount')->nullable();
            $table->float('seller_amount')->nullable();
            $table->string('url_auth_code')->nullable();
            $table->string('bank_result')->nullable();
            $table->string('bank_response_text')->nullable();
            $table->string('bank_response_code')->nullable();
            $table->string('bank_error')->nullable();
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
        Schema::dropIfExists('account_items');
    }
}
