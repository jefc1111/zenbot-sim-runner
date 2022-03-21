<?php
namespace App\Models\BotManagement;

use Illuminate\Support\Arr;
use App\Models\BotManagement\Bot;

class Pm2ConfigParser
{
    public static function update_bots()
    {
        $client = new \GuzzleHttp\Client();
        
        $res = $client->request('GET', config('zenbot.bot_monitoring.manager_url'));
        
        $process_configs = collect(
            json_decode(
                $res->getBody()
            )
        )
        ->filter(fn($item) => property_exists($item, 'zenbot_port'))
        ->values();

        $active_bots = collect([]);

        foreach ($process_configs as $process_config) {
            $arg_str = implode(' ', $process_config->args);

            $existing_bot = Bot::where('args', '=', $arg_str)
                ->where('name', '=', $process_config->name)
                ->where('zenbot_port', '=', $process_config->zenbot_port)
                ->first();
            
            if ($existing_bot) {
                $active_bots->push($existing_bot);
            } else {
                $active_bots->push(
                    Bot::create([
                        'name' => $process_config->name,
                        'args' => $arg_str,
                        'discord_username' => $process_config->discord_username,
                        'zenbot_port' => $process_config->zenbot_port,
                        'active' => 1
                    ])
                );
            }            
        }
        
        Bot::whereNotIn('id', $active_bots->pluck('id'))->update(['active' => 0]);
    }
    /*
      +"name": "BTC/BUSD-ti_stock_bollinger"
      +"pid": 1944913
      +"monit": {#593 ▶}
      +"args": array:22 [▶]
      +"discord_username": "paper 17000"
      +"zenbot_port": 17000
    */

    public function get_running_pm2_processes() 
    {
        $client = new \GuzzleHttp\Client();
        
        $res = $client->request('GET', config('zenbot.bot_monitoring.manager_url'));
        
        $process_configs = collect(
            json_decode(
                $res->getBody()
            )
        )
        ->filter(fn($item) => property_exists($item, 'zenbot_port'))
        ->values();

        dd($process_configs);

        $res = $client->request('GET', config('zenbot.bot_monitoring.base_url').":17000/trades");

        $trades_output = $res->getBody()->getContents();
dd(array_keys((array) json_decode($trades_output)));
//dd(Arr::except(json_decode($trades_output, true), 'trades')['strategy']['phenotypes']);
//dd(Arr::except(json_decode($trades_output, true), 'trades'));
        return $process_configs."<br><br><br>".Arr::except(json_decode($trades_output, true), 'trades');
    }

}
