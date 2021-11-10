<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateSimTimeBundlesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('sim_time_bundles', function (Blueprint $table) {
            $table->id();
            $table->integer('cost');
            $table->string('currency')->default('USD');
            $table->string('currency_symbol')->default('$');
            $table->integer('qty_hours');
            $table->boolean('base_option')->default(0);
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
        Schema::dropIfExists('sim_time_bundles');
    }
}
