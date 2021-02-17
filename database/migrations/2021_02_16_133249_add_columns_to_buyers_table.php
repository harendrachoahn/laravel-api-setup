<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddColumnsToBuyersTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('buyers', function (Blueprint $table) {
            $table->date('dob')->after('card_cvc')->nullable();
            $table->enum('gender',['Male','Female'])->after('card_cvc')->nullable();
            $table->enum('married',['Yes','No'])->after('card_cvc')->nullable();
            //
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('buyers', function (Blueprint $table) {
            $table->dropColumn('dob');
            $table->dropColumn('gender');
            $table->dropColumn('married');
        });
    }
}
