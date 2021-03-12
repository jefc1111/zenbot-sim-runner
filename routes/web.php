<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\StrategyController;
use App\Http\Controllers\ExchangeController;
use App\Http\Controllers\StrategyOptionController;
use App\Http\Controllers\SimRunBatchController;
use App\Http\Controllers\ImportFromZenbotController;
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
    return view('home');
});

Route::get('import-all', [ImportFromZenbotController::class, 'import_all']);

Route::get('import-strategies', [StrategyController::class, 'import_strategies']);
Route::resource('strategies', StrategyController::class);

Route::get('import-exchanges', [ExchangeController::class, 'import_exchanges']);
Route::resource('exchanges', ExchangeController::class);

Route::resource('strategy-options', StrategyOptionController::class);

// Sim run batch creation
Route::get('sim-run-batch/select-strategies', [SimRunBatchController::class, 'select_strategies']);
Route::post('sim-run-batch/refine-strategies', [SimRunBatchController::class, 'refine_strategies']);
Route::post('sim-run-batch/confirm', [SimRunBatchController::class, 'confirm']);
Route::resource('sim-run-batch', SimRunBatchController::class);