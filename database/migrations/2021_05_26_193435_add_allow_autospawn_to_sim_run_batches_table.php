<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddAllowAutospawnToSimRunBatchesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('sim_run_batches', function (Blueprint $table) {
            $table->boolean('allow_autospawn')->after('sell_pct')->default(0);
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
            $table->dropColumn('allow_autospawn');
        });
    }
}
