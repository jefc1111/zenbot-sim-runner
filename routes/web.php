<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\StrategyController;
use App\Http\Controllers\ExchangeController;
use App\Http\Controllers\StrategyOptionController;
use App\Http\Controllers\SimRunBatchController;
use App\Http\Controllers\SimRunController;
use App\Http\Controllers\ShopController;
use App\Http\Controllers\UserApprovalController;
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

Route::webhooks('shop/payment-webhook');

Route::group(['middleware' => ['auth', 'verified']], function () { 
    Route::get('awaiting-approval', [UserApprovalController::class, 'awaiting_approval'])->name('awaiting-approval');

    Route::get('logout', [LogoutController::class, 'index']);
});

Route::group(['middleware' => ['auth', 'verified', 'approved']], function () { 
    Route::get('/', function () {
        return view('home');
    });    
    
    Route::resource('strategies', StrategyController::class);
    
    Route::resource('exchanges', ExchangeController::class);
    
    Route::resource('strategy-options', StrategyOptionController::class);
    
    // Sim run batch creation
    Route::post('sim-run-batches/create/select-strategies', [SimRunBatchController::class, 'select_strategies']);
    Route::post('sim-run-batches/create/refine-strategies', [SimRunBatchController::class, 'refine_strategies']);
    Route::post('sim-run-batches/create/confirm', [SimRunBatchController::class, 'confirm']);
    
    Route::get('sim-run-batches/run/{sim_run_batch}', [SimRunBatchController::class, 'run'])->can('view', 'sim_run_batch');
    Route::get('sim-run-batches/cancel/{sim_run_batch}', [SimRunBatchController::class, 'cancel'])->can('view', 'sim_run_batch');
    Route::get('sim-run-batches/copy/{sim_run_batch}', [SimRunBatchController::class, 'copy'])->can('view', 'sim_run_batch');
    Route::get('sim-run-batches/prune/{sim_run_batch}', [SimRunBatchController::class, 'prune'])->can('view', 'sim_run_batch');
    Route::get('sim-run-batches/reset/{sim_run_batch}', [SimRunBatchController::class, 'reset'])->can('view', 'sim_run_batch');
    Route::get('sim-run-batches/backfill-log/{sim_run_batch}', [SimRunBatchController::class, 'get_backfill_log'])->can('view', 'sim_run_batch');
    Route::get('sim-run-batches/spawn-child-from/{sim_run_batch}', [SimRunBatchController::class, 'spawn_child_from'])->can('view', 'sim_run_batch');
    Route::get('sim-run-batches/status/{sim_run_batch}', [SimRunBatchController::class, 'get_status']);
    Route::resource('sim-run-batches', SimRunBatchController::class);
    
    Route::get('sim-runs/run/{sim_run}', [SimRunController::class, 'run'])->can('view', 'sim_run');
    Route::get('sim-runs/log/{sim_run}', [SimRunController::class, 'get_log'])->can('view', 'sim_run');
    Route::resource('sim-runs', SimRunController::class);

    Route::get('/home', [App\Http\Controllers\HomeController::class, 'index'])->name('home');

    Route::get('/shop', [App\Http\Controllers\ShopController::class, 'index'])->name('shop');
    Route::get('/shop/buy-sim-time-bundle/{id}', [App\Http\Controllers\ShopController::class, 'buy_sim_time_bundle']);    
});

Route::group(['middleware' => ['auth', 'verified', 'approved', 'is-admin']], function () { 
    Route::get('import-all', [ImportFromZenbotController::class, 'import_all']);
    Route::get('import-strategies', [StrategyController::class, 'import_strategies']);
    Route::get('import-exchanges', [ExchangeController::class, 'import_exchanges']);
});

Auth::routes(
    ['verify' => true]
);

Route::group(['prefix' => 'admin'], function () {
    Voyager::routes();
});
