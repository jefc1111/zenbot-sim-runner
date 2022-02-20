<?php

namespace App\Models\BotManagement;

use Illuminate\Database\Eloquent\Model;
use App\Models\BotManagement\BotSnapshot;

class Bot extends Model
{
    protected $guarded = ['id'];

    public function snapshots()
    {
        return $this->hasMany(BotSnapshot::class);
    }

    public function take_snapshot()
    {
        $client = new \GuzzleHttp\Client();
        
        $res = $client->request('GET', config('zenbot.bot_monitoring.base_url').":$this->zenbot_port/trades");
        
        $bot_state = json_decode(
            $res->getBody()
        );        

        $res = \Arr::except((array) $bot_state, [
            'trades', 'lookback'
        ]);

        $snapshot = BotSnapshot::create([
            'bot_id' => $this->id,
            'asset_amount' => $res['balance']['asset'],
            'currency_amount' => $res['balance']['currency'],
            'profit' => $res['stats']['profit'],
            'buy_hold_profit'=> $res['stats']['buy_hold_profit'],
        ]);
    }
}
/*

[2022-02-18 21:45:08] local.ERROR: Array
(
    [0] => exchange
    [1] => product_id
    [2] => asset
    [3] => currency
    [4] => asset_capital 
    [5] => product
    [6] => balance
    [7] => ctx
    [8] => lookback
    [9] => day_count
    [10] => my_trades
    [11] => my_prev_trades
    [12] => vol_since_last_blink
    [13] => boot_time
    [14] => tz_offset
    [15] => last_trade_id
    [16] => strategy
    [17] => last_day
    [18] => period
    [19] => acted_on_stop
    [20] => signal
    [21] => port
    [22] => url
    [23] => sim_asset
    [24] => quote
    [25] => start_price
    [26] => start_capital
    [27] => real_capital
    [28] => net_currency
    [29] => stats
    [30] => last_signal
    [31] => acted_on_trend
    [32] => api_order
    [33] => action
    [34] => last_buy_price
    [35] => last_trade_worth
    [36] => last_sell_price
)
*/
