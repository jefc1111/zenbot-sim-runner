<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateSimTimeOrdersTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('sim_time_orders', function (Blueprint $table) {
            $table->id();
            $table->integer('user_id');
            $table->integer('sim_time_bundle_id');
            $table->string('invoice_id')->nullable();
            $table->string('status')->default('awaiting-invoice');
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
        Schema::dropIfExists('sim_time_orders');
    }
}
