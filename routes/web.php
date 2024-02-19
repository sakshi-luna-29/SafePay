<?php

use App\Http\Controllers\mapController;
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

Route::get('/', [mapController::class, 'dashboard']);

Route::get('map', [mapController::class, 'index']);
Route::get('fileInfo', [mapController::class, 'countMaleFemale']);
Route::post('/filter-location', [mapController::class, 'filterPeople'])->name('filter.people');
Route::post('/search', [mapController::class, 'searchName']);
