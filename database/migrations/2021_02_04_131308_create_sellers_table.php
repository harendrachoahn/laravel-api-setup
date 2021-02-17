<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateSellersTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('sellers', function (Blueprint $table) {
            $table->uuid('id')->primary();
            $table->string('email')->nullable();
            $table->string('password')->nullable();
            $table->string('phone')->nullable();
            $table->string('contact_name')->nullable();
            $table->boolean('is_admin')->nullable();
            $table->string('account_bsb')->nullable();
            $table->string('account_number')->nullable();
            $table->string('fax')->nullable();
            $table->string('name')->nullable();
            $table->string('account_name')->nullable();
            $table->string('web_name')->nullable();
            $table->string('abn')->nullable();
            $table->boolean('apply_gst')->nullable();
            $table->string('contract')->nullable();
            $table->datetime('contract_valid_until')->nullable();
            $table->string('stage')->nullable();
            $table->text('notes')->nullable();
            $table->datetime('last_login')->nullable();
            $table->string('image')->nullable();
            $table->text('description')->nullable();
            $table->boolean('clear_instantly')->default(0);
            $table->decimal('balance',$precision = 10, $scale = 4)->default(0.0);
            $table->float('commissions')->default(0.0);
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
        Schema::dropIfExists('sellers');
    }
}
