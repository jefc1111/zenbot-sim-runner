<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\StrategyController;
use App\Http\Controllers\StrategyOptionController;
use App\Http\Controllers\SimRunBatchController;

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

Route::get('import-strategies', [StrategyController::class, 'import_strategies']);

Route::resource('strategies', StrategyController::class);
Route::resource('strategy-options', StrategyOptionController::class);


Route::get('sim-run-batch/select-strategies', [SimRunBatchController::class, 'select_strategies']);
Route::post('sim-run-batch/refine-strategies', [SimRunBatchController::class, 'refine_strategies']);
Route::post('sim-run-batch/confirm', [SimRunBatchController::class, 'confirm']);
Route::resource('sim-run-batch', SimRunBatchController::class);