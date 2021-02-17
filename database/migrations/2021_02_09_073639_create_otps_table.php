<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateOtpsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('otps', function (Blueprint $table) {
            $table->uuid('id')->primary();
            $table->string('country_code')->nullable();
            $table->string('phone')->nullable();
            $table->string('otp')->unique()->nullable();
            $table->datetime('expired')->nullable();
            $table->enum('sms_sent',['0', '1'])->default(0);
            $table->softDeletes()->nullable();           
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
        Schema::dropIfExists('otps');
    }
}
