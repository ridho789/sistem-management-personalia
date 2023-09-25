<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\divisionController;

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
    return view('/frontend/dashboard');
});

Route::get('/devision', [divisionController::class, 'index']);
Route::post('division-add', [divisionController::class, 'store']);
Route::get('division-delete/{id_divisi}', [divisionController::class, 'delete']);
Route::get('division-select-edit/{id_divisi}', [divisionController::class, 'select']);