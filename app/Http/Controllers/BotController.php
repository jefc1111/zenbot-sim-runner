<?php

namespace App\Http\Controllers;
use App\Models\BotManagement\Bot;

//use App\Jobs\BotStateGetter;

use Illuminate\Http\Request;

class BotController extends Controller
{
    public function index()
    {
        return view('bots.list', [
            'bots' => Bot::orderBy('active')->get()
        ]);

        //new (App\Models\BotManagement\Pm2ConfigParser)->get_running_pm2_processes();
        
        //BotStateGetter::dispatch();     
    }

    public function show($id)
    {
        $bot = Bot::findOrFail($id);

        return view('bots.show', [
            'bot' => $bot
        ]);
    }
}
