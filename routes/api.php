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
Route::get('status/{id}', [\App\Http\Controllers\InstController::class, 'status'])->name('status');
Route::get('getmessages/{id}', [\App\Http\Controllers\InstController::class, 'getmessages'])->name('getmessages');
Route::post('status', [\App\Http\Controllers\InstController::class, 'updatestatus'])->name('updatestatus');
Route::post('show', [\App\Http\Controllers\InstController::class, 'show'])->name('show');
Route::post('ResultSet', [\App\Http\Controllers\ResultController::class, 'resultset'])->name('resultset');
Route::post('pid', [\App\Http\Controllers\InstController::class, 'pid'])->name('pid');

Route::get('test',function(){
});
