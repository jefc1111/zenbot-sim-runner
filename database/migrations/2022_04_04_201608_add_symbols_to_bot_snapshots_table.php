<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddSymbolsToBotSnapshotsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('bot_snapshots', function (Blueprint $table) {
            $table->string('asset')->after('asset_amount')->nullable();
            $table->string('currency')->after('currency_amount')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('bot_snapshots', function (Blueprint $table) {
            $table->dropColumn('asset');
            $table->dropColumn('currency');
        });
    }
}
