<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\divisionController;
use App\Http\Controllers\positionController;
use App\Http\Controllers\companyController;
use App\Http\Controllers\employeestatusController;
use App\Http\Controllers\categoryController;
use App\Http\Controllers\subcategoryController;
use App\Http\Controllers\leavetypeController;

use App\Http\Controllers\assetmanagementController;
use App\Http\Controllers\employeemanagementController;
use App\Http\Controllers\leavemanagementController;

use App\Http\Controllers\importEmployeeexcel;
use App\Http\Controllers\importAssetexcel;

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

Route::get('/category', [categoryController::class, 'index']);
Route::post('category-add', [categoryController::class, 'store']);
Route::get('category-delete/{id_kategori}', [categoryController::class, 'delete']);
Route::post('category-update', [categoryController::class, 'update']);

Route::post('sub-category-add', [subcategoryController::class, 'store']);
Route::get('sub-category-delete/{id_sub_kategori}', [subcategoryController::class, 'delete']);
Route::post('sub-category-update', [subcategoryController::class, 'update']);

Route::get('/type-leave', [leavetypeController::class, 'index']);
Route::post('type-leave-add', [leavetypeController::class, 'store']);
Route::get('type-leave-delete/{id_tipe_cuti}', [leavetypeController::class, 'delete']);
Route::post('type-leave-update', [leavetypeController::class, 'update']);

// management - employee
Route::get('/list-employee', [employeemanagementController::class, 'index']);
Route::get('/form-employee', [employeemanagementController::class, 'create']);
Route::post('form-employee-add', [employeemanagementController::class, 'store']);
Route::get('list-employee-delete/{id_karyawan}', [employeemanagementController::class, 'delete']);
Route::get('form-employee-edit/{id_karyawan}', [employeemanagementController::class, 'edit']);
Route::post('form-employee-update', [employeemanagementController::class, 'update']);
Route::get('list-employee-search', [employeemanagementController::class, 'search']);
Route::post('list-employee-print', [employeemanagementController::class, 'print']);

// management - asset
Route::get('/list-asset', [assetmanagementController::class, 'index']);
Route::get('/form-asset', [assetmanagementController::class, 'create']);
Route::post('form-asset-add', [assetmanagementController::class, 'store']);
Route::get('list-asset-delete/{id_asset}', [assetmanagementController::class, 'delete']);
Route::get('form-asset-edit/{id_asset}', [assetmanagementController::class, 'edit']);
Route::post('form-asset-update', [assetmanagementController::class, 'update']);
Route::get('list-asset-search', [assetmanagementController::class, 'search']);
// mengambil data sub category berdasarkan category yang dipilih
Route::get('/get-sub-categories/{categoryId}', [assetmanagementController::class, 'getSubCategories']);

// management - attendance

// attendance - leave
Route::get('/leave-request', [leavemanagementController::class, 'create']);
Route::get('/leaves-summary', [leavemanagementController::class, 'index']);
Route::get('/allocation-request', [leavemanagementController::class, 'allocation']);
Route::post('leave-request-add', [leavemanagementController::class, 'store']);
Route::get('leave-request-edit/{id_data_cuti}', [leavemanagementController::class, 'edit']);
Route::post('leave-request-update', [leavemanagementController::class, 'update']);
Route::get('leave-request-delete/{id_data_cuti}', [leavemanagementController::class, 'delete']);
Route::post('leave-request-print', [leavemanagementController::class, 'print']);
Route::post('leave-request-upload', [leavemanagementController::class, 'upload']);

// import excel
Route::post('import-excel-employee', [importEmployeeexcel::class, 'importExcel']);
Route::post('import-excel-asset', [importAssetexcel::class, 'importExcel']);