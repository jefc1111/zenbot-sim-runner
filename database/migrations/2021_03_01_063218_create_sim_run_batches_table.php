<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateSimRunBatchesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('sim_run_batches', function (Blueprint $table) {
            $table->id();
            $table->string('notes')->default('');
            $table->integer('exchange_id');
            $table->integer('product_id');
            $table->integer('days');
            $table->dateTime('start');
            $table->dateTime('end');
            $table->integer('buy_pct');
            $table->integer('sell_pct');
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
        Schema::dropIfExists('sim_run_batches');
    }
}
