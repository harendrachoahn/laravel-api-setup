<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateUsersTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('admin_users', function (Blueprint $table) {
            $table->uuid('id')->primary();
            $table->string('email')->unique()->index();
            $table->string('encrypted_password')->nullable();
            $table->string('reset_password_token')->nullable();
            $table->datetime('reset_password_sent_at')->unique()->index()->nullable();
            $table->datetime('remember_created_at')->nullable();
            $table->integer('sign_in_count')->default(0);
            $table->datetime('current_sign_in_at')->nullable();
            $table->datetime('last_sign_in_at')->nullable();
            $table->string('current_sign_in_ip')->nullable();
            $table->string('last_sign_in_ip')->nullable();
            //$table->rememberToken();
            
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
        Schema::dropIfExists('users');
    }
}
