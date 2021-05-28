<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\StrategyController;
use App\Http\Controllers\ExchangeController;
use App\Http\Controllers\StrategyOptionController;
use App\Http\Controllers\SimRunBatchController;
use App\Http\Controllers\SimRunController;
use App\Http\Controllers\ImportFromZenbotController;
use App\Http\Controllers\Auth\LogoutController;
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

Route::group(['middleware' => ['auth']], function () { 

    Route::get('/', function () {
        return view('home');
    });
    
    Route::get('logout', [LogoutController::class, 'index']);
    
    Route::get('import-all', [ImportFromZenbotController::class, 'import_all']);
    
    Route::get('import-strategies', [StrategyController::class, 'import_strategies']);
    Route::resource('strategies', StrategyController::class);
    
    Route::get('import-exchanges', [ExchangeController::class, 'import_exchanges']);
    Route::resource('exchanges', ExchangeController::class);
    
    Route::resource('strategy-options', StrategyOptionController::class);
    
    // Sim run batch creation
    Route::post('sim-run-batches/create/select-strategies', [SimRunBatchController::class, 'select_strategies']);
    Route::post('sim-run-batches/create/refine-strategies', [SimRunBatchController::class, 'refine_strategies']);
    Route::post('sim-run-batches/create/confirm', [SimRunBatchController::class, 'confirm']);
    
    Route::get('sim-run-batches/run/{id}', [SimRunBatchController::class, 'run']);
    Route::get('sim-run-batches/copy/{id}', [SimRunBatchController::class, 'copy']);
    Route::get('sim-run-batches/spawn-child-from/{id}', [SimRunBatchController::class, 'spawn_child_from']);
    Route::resource('sim-run-batches', SimRunBatchController::class);
    
    Route::get('sim-runs/run/{id}', [SimRunController::class, 'run']);
    Route::resource('sim-runs', SimRunController::class);

    Route::get('/home', [App\Http\Controllers\HomeController::class, 'index'])->name('home');
});


Auth::routes();


