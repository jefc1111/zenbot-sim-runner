<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\StrategyOption;


class StrategyOptionController extends Controller
{
    public function index()
    {
        return view('list_strategy_options', ['strategy_options' => StrategyOption::all()]);
    }

    /*
    public function show($id)
    {
        return view('strategy_detail', ['strategy' => Strategy::findOrFail($id)]);
    }
    */
}
