<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateBotSnapshotsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('bot_snapshots', function (Blueprint $table) {
            $table->id();    
            $table->integer('bot_id');
            $table->integer('qty_trades');
            $table->float('asset_amount', $precision = 16, $scale = 8); // balance.asset
            $table->float('currency_amount', $precision = 16, $scale = 8); // balance.currency
            $table->decimal('profit', $precision = 5, $scale = 2); // stats.profit
            $table->decimal('buy_hold_profit', $precision = 5, $scale = 2); // stats.buy_hold_profit
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
        Schema::dropIfExists('bot_snapshots');
    }
}

/*

asset_capital 
balance.asset
balance.currency
my_trades <- as json
stats.profit
stats.buy_hold_profit

    [acted_on_stop] => 
    [signal] => 
    [port] => 17001
    [url] => 192.168.0.56:17001/
    [sim_asset] => 0.00453375
    [quote] => stdClass Object
        (
            [bid] => 39994.83
            [ask] => 39994.84
        )

    [start_price] => 39827.08
    [start_capital] => 1000.995677
    [real_capital] => 1000.995677
    [net_currency] => 819.8445309469
    [stats] => stdClass Object
        (
            [profit] => -0.02%
            [tmp_balance] => 1000.87303876
            [buy_hold] => 1005.36690301
            [buy_hold_profit] => 0.43%
            [day_count] => 1
            [trade_per_day] => 3.00
            [win] => 1
            [losses] => 0
            [error_rate] => 0.00%
        )

    [last_signal] => buy
    [acted_on_trend] => 1
    [api_order] => stdClass Object
        (
            [id] => 1007
            [status] => done
            [price] => 40058.84000000
            [size] => 0.00226312
            [orig_size] => 0.00226312
            [remaining_size] => 0
            [post_only] => 1
            [filled_size] => 0.00226312
            [ordertype] => maker
            [tradetype] => buy
            [orig_time] => 1645216681140
            [time] => 1645216681140
            [created_at] => 1645216681140
            [done_at] => 1645216686237
        )

    [action] => bought
    [last_buy_price] => 40058.84000000
    [last_trade_worth] => -0.0014438760583181
    [last_sell_price] => 40298.52000000
*/
