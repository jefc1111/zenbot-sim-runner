<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\StrategyController;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/

Route::get('/', function () {
    return view('welcome');
});

Route::get('import-strategies', [StrategyController::class, 'import_strategies']);

Route::resource('strategies', StrategyController::class);