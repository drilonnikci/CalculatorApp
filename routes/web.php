<?php

use App\Http\Controllers\CalculatorController;
use App\Http\Controllers\ResultsHistory;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "web" middleware group. Make something great!
|
*/

Route::get('/', function () {
    return view('welcome');
});

Route::post('/calculate', [CalculatorController::class, 'calculate']);

Route::get('/history', [ResultsHistory::class, 'show']);
Route::delete('/history/delete', [ResultsHistory::class, 'delete']);
