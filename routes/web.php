<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\divisionController;
use App\Http\Controllers\positionController;
use App\Http\Controllers\companyController;
use App\Http\Controllers\employeestatusController;
use App\Http\Controllers\employeemanagementController;
use App\Http\Controllers\importEmployeeexcel;

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

// master data
Route::get('/devision', [divisionController::class, 'index']);
Route::post('division-add', [divisionController::class, 'store']);
Route::get('division-delete/{id_divisi}', [divisionController::class, 'delete']);
Route::post('division-update', [divisionController::class, 'update']);

Route::get('/position', [positionController::class, 'index']);
Route::post('position-add', [positionController::class, 'store']);
Route::get('position-delete/{id_divisi}', [positionController::class, 'delete']);
Route::post('position-update', [positionController::class, 'update']);

Route::get('/company', [companyController::class, 'index']);
Route::post('company-add', [companyController::class, 'store']);
Route::get('company-delete/{id_perusahaan}', [companyController::class, 'delete']);
Route::post('company-update', [companyController::class, 'update']);

Route::get('/employee-status', [employeestatusController::class, 'index']);
Route::post('employee-status-add', [employeestatusController::class, 'store']);
Route::get('employee-status-delete/{id_perusahaan}', [employeestatusController::class, 'delete']);
Route::post('employee-status-update', [employeestatusController::class, 'update']);

// management - employee
Route::get('/list-employee', [employeemanagementController::class, 'index']);
Route::get('/form-employee', [employeemanagementController::class, 'create']);
Route::post('form-employee-add', [employeemanagementController::class, 'store']);
Route::get('list-employee-delete/{id_karyawan}', [employeemanagementController::class, 'delete']);
Route::get('form-employee-edit/{id_karyawan}', [employeemanagementController::class, 'edit']);
Route::post('form-employee-update', [employeemanagementController::class, 'update']);

// import excel
Route::post('import-excel-employee', [importEmployeeexcel::class, 'importExcel']);