<?php

use Illuminate\Http\Request;
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

Route::middleware('auth:api')->get('/user', function (Request $request) {
    return $request->user();
});
Route::get('status/{id}', [\App\Http\Controllers\InstrumentController::class, 'status'])->name('status');
Route::get('getmessages/{id}', [\App\Http\Controllers\InstrumentController::class, 'getmessages'])->name('getmessages');
Route::post('status', [\App\Http\Controllers\InstrumentController::class, 'updatestatus'])->name('updatestatus');
Route::post('show', [\App\Http\Controllers\InstrumentController::class, 'show'])->name('show');
Route::post('resultset', [\App\Http\Controllers\InstrumentController::class, 'resultset'])->name('resultset');
Route::post('pid', [\App\Http\Controllers\InstrumentController::class, 'pid'])->name('pid');

Route::get('test',function(){
    // return (base_path('app\http\controllers\DymindLouncher'));
});
