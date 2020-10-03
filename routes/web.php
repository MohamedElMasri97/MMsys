<?php

use Illuminate\Support\Facades\Route;

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

Route::get('Instruments', [\App\Http\Controllers\InstrumentController::class, 'index'])->name('Instrumentsview');
Route::get('new-Instrument',[\App\Http\Controllers\InstrumentController::class, 'viewNewInstrument'])->name('newInst');
Route::post('new-Instrument', [\App\Http\Controllers\InstrumentController::class, 'newinst'])->name('postnewInst');
Route::post('flipconnection/{id}', [\App\Http\Controllers\InstrumentController::class, 'flipconnection'])->name('flipconnection');
Route::get('Instrument/{id}', [\App\Http\Controllers\InstrumentController::class, 'instdetails'])->name('instrumentDetails');
