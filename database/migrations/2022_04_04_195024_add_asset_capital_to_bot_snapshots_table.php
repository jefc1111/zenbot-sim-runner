<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddAssetCapitalToBotSnapshotsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('bot_snapshots', function (Blueprint $table) {
            $table->float('asset_capital', $precision = 16, $scale = 8)->after('asset_amount');
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
            $table->dropColumn('asset_capital');
        });
    }
}
