<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateDelayedJobsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('delayed_jobs', function (Blueprint $table) {
            $table->uuid('id')->primary();
            $table->integer('priority')->default(0)->index()->nullable();
            $table->integer('attempts')->default(0)->index()->nullable();
            $table->text('handler')->nullable();
            $table->text('last_error')->nullable();
            $table->datetime('run_at')->nullable();
            $table->datetime('locked_at')->nullable();
            $table->datetime('failed_at')->nullable();
            $table->string('locked_by')->nullable();
            $table->string('queue')->nullable();

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
        Schema::dropIfExists('delayed_jobs');
    }
}
