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
            'bots' => Bot::orderBy('active', 'desc')->get()->sort(fn($b) => $b->is_paper())
        ]);  
    }

    public function show($id)
    {
        $bot = Bot::findOrFail($id);

        return view('bots.show.main', [
            'bot' => $bot
        ]);
    }
}
