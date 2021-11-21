<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddBackfillRuntimeToSimRunBatches extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('sim_run_batches', function (Blueprint $table) {
            $table->integer('backfill_runtime')->default(0)->after('allow_autospawn');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('sim_run_batches', function (Blueprint $table) {
            $table->dropColumn('backfill_runtime');
        });
    }
}
