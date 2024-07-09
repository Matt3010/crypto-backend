<?php

use App\Http\Controllers\ContoTradingController;
use App\Http\Controllers\MovimentiController;
use App\Http\Controllers\PortafoglioAzioniController;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| is assigned the "api" middleware group. Enjoy building your API!
|
*/

Route::prefix('conto-trading')->group(function () {
    Route::get('', [ContoTradingController::class, 'list']);
    Route::post('date-range', [ContoTradingController::class, 'getMovimenti']);
    Route::get('{ContoTradingID}/totali-acquisti-vendite', [ContoTradingController::class, 'totaliAcquistiVendite']);
});

Route::prefix('movimenti')->group(function () {
    Route::post('vendi', [MovimentiController::class, 'vendi']);
    Route::post('acquista', [MovimentiController::class, 'acquista']);
});

Route::prefix('portafoglio')->group(function () {
    Route::post('value', [PortafoglioAzioniController::class, 'getPortafoglioValueOfConto']);
 });

