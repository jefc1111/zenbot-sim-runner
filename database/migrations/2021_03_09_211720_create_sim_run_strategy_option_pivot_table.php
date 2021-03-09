<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;

class CreateSimRunStrategyOptionPivotTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('sim_run_strategy_option', function (Blueprint $table) {
            $table->unsignedBigInteger('sim_run_id')->index();
            $table->foreign('sim_run_id')->references('id')->on('sim_runs')->onDelete('cascade');
            $table->unsignedBigInteger('strategy_option_id')->index();
            $table->foreign('strategy_option_id')->references('id')->on('strategy_options')->onDelete('cascade');
            $table->primary(['sim_run_id', 'strategy_option_id']);
            $table->string('value');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('sim_run_strategy_option');
    }
}
