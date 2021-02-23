<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateStrategyOptionsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('strategy_options', function (Blueprint $table) {
            $table->id();
            $table->integer('strategy_id');
            $table->string('name');
            $table->string('description')->default('');
            $table->string('default')->default('');
            $table->string('unit')->default('');
            $table->string('step')->default('');
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
        Schema::dropIfExists('strategy_options');
    }
}
