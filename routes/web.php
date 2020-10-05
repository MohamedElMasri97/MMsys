<?php

use Illuminate\Support\Facades\Route;
use App\Models\Instrument;
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
})->name('Home');

Route::get('Instruments',[\App\Http\Controllers\InstController::class, 'index'])->name('Instrumentsview');
Route::get('new-Instrument', [\App\Http\Controllers\InstController::class, 'viewNewInstrument'])->name('newInst');
Route::post('new-Instrument', [\App\Http\Controllers\InstController::class, 'newinst'])->name('postnewInst');
Route::post('flipconnection/{id}', [\App\Http\Controllers\InstController::class, 'flipconnection'])->name('flipconnection');
Route::get('Instrument/{id}', [\App\Http\Controllers\InstController::class, 'instdetails'])->name('instrumentDetails');
