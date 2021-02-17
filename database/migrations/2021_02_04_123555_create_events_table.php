<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateEventsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('events', function (Blueprint $table) {
            $table->uuid('id')->primary();
            $table->string('name')->nullable();
            $table->text('description')->nullable();
            $table->uuid('seller_id')->nullable();
            $table->string('image')->nullable();
            $table->float('booking_fee_additional')->nullable();
            $table->string('location_info')->nullable();
            $table->float('timezone')->nullable();
            $table->string('phone')->nullable();
            $table->boolean('active')->nullable();
            $table->float('suggested_donation')->default(0.0);
            $table->string('pricing_model')->default('sell');
            $table->string('booking_info')->nullable();
            $table->string('spoken_name')->nullable();
            $table->float('dramatix_fee')->nullable();
            $table->integer('fax_lead_hours')->default(2);
            $table->text('post_sale_msg')->nullable();
            $table->boolean('feedback_to_seller')->nullable();
            $table->string('ticket_image')->nullable();
            $table->string('ticket_image_type')->nullable();
            $table->boolean('show_on_homepage')->default(1);
            $table->boolean('hidden')->default(0);
            $table->boolean('override_dramatix_fee')->default(0);
            $table->boolean('cc_tix_to_seller')->default(0);
            $table->integer('visits_count')->default(0);
            $table->float('booking_fee_percentage')->default('0.0');
            $table->enum('state',['Australia','ACT','NSW','NT','SA','QLD','TAS','VIC','WA'])->default('Australia');
            $table->enum('kind',['Comedy','Theatre','Music','Dance','Cabaret','Film','Lecture/Workshop','Other','Unassigned'])->default('Unassigned');
            $table->string('sale_message')->nullable();
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
        Schema::dropIfExists('events');
    }
}
