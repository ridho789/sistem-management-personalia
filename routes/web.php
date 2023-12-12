<?php

use Illuminate\Support\Facades\Route;

use App\Http\Controllers\DashboardController;
use App\Http\Controllers\DivisionController;
use App\Http\Controllers\PositionController;
use App\Http\Controllers\CompanyController;
use App\Http\Controllers\EmployeeStatusController;
use App\Http\Controllers\CategoryController;
use App\Http\Controllers\SubCategoryController;
use App\Http\Controllers\LeaveTypeController;

use App\Http\Controllers\AssetManagementController;
use App\Http\Controllers\EmployeeManagementController;
use App\Http\Controllers\LeaveManagementController;
use App\Http\Controllers\AttendanceController;
use App\Http\Controllers\PayrollController;
use App\Http\Controllers\DailyReportManagementController;

use App\Http\Controllers\LoginController;
use App\Http\Controllers\RegisterController;

use App\Http\Controllers\ImportEmployeeexcel;
use App\Http\Controllers\ImportAssetexcel;
use App\Http\Controllers\ImportDailyReport;

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
    return view('/backend/login/login');
});

// login
Route::get('/login', [LoginController::class, 'index'])->name('login');
Route::get('login-auth', [LoginController::class, 'authenticate']);
Route::get('logout', [LoginController::class, 'logout']);

// register
Route::get('/register', [RegisterController::class, 'index']);
Route::post('register-add', [RegisterController::class, 'store']);

// dashboard
Route::get('/dashboard', [DashboardController::class, 'index'])->middleware('auth');

Route::group(['middleware' => ['auth', 'check.role.user:1']], function () 
{
    // master data
    Route::get('/devision', [DivisionController::class, 'index'])->middleware('auth');
    Route::post('division-add', [DivisionController::class, 'store']);
    Route::get('division-delete/{id_divisi}', [DivisionController::class, 'delete']);
    Route::post('division-update', [DivisionController::class, 'update']);

    Route::get('/position', [PositionController::class, 'index'])->middleware('auth');
    Route::post('position-add', [PositionController::class, 'store']);
    Route::get('position-delete/{id_divisi}', [PositionController::class, 'delete']);
    Route::post('position-update', [PositionController::class, 'update']);

    Route::get('/company', [CompanyController::class, 'index'])->middleware('auth');
    Route::post('company-add', [CompanyController::class, 'store']);
    Route::get('company-delete/{id_perusahaan}', [CompanyController::class, 'delete']);
    Route::post('company-update', [CompanyController::class, 'update']);

    Route::get('/employee-status', [EmployeeStatusController::class, 'index'])->middleware('auth');
    Route::post('employee-status-add', [EmployeeStatusController::class, 'store']);
    Route::get('employee-status-delete/{id_perusahaan}', [EmployeeStatusController::class, 'delete']);
    Route::post('employee-status-update', [EmployeeStatusController::class, 'update']);

    Route::get('/category', [CategoryController::class, 'index']);
    Route::post('category-add', [CategoryController::class, 'store']);
    Route::get('category-delete/{id_kategori}', [CategoryController::class, 'delete']);
    Route::post('category-update', [CategoryController::class, 'update']);

    Route::post('sub-category-add', [SubCategoryController::class, 'store']);
    Route::get('sub-category-delete/{id_sub_kategori}', [SubCategoryController::class, 'delete']);
    Route::post('sub-category-update', [SubCategoryController::class, 'update']);

    Route::get('/type-leave', [LeaveTypeController::class, 'index'])->middleware('auth');
    Route::post('type-leave-add', [LeaveTypeController::class, 'store']);
    Route::get('type-leave-delete/{id_tipe_cuti}', [LeaveTypeController::class, 'delete']);
    Route::post('type-leave-update', [LeaveTypeController::class, 'update']);

    // management - employee
    Route::get('/list-employee', [EmployeeManagementController::class, 'index'])->middleware('auth');
    Route::get('/form-employee', [EmployeeManagementController::class, 'create'])->middleware('auth');
    Route::post('form-employee-add', [EmployeeManagementController::class, 'store']);
    Route::get('list-employee-delete/{id_karyawan}', [EmployeeManagementController::class, 'delete']);
    Route::get('form-employee-edit/{id_karyawan}', [EmployeeManagementController::class, 'edit'])->middleware('auth');
    Route::post('form-employee-update', [EmployeeManagementController::class, 'update']);
    Route::get('list-employee-search', [EmployeeManagementController::class, 'search']);
    Route::post('list-employee-print', [EmployeeManagementController::class, 'print']);

    // management employee inactive
    Route::get('/list-inactive-employee', [EmployeeManagementController::class, 'index_inactive'])->middleware('auth');
    Route::get('list-inactive-employee-search', [EmployeeManagementController::class, 'search_inactive']);

    // management - asset
    Route::get('/list-asset', [AssetManagementController::class, 'index']);
    Route::get('/form-asset', [AssetManagementController::class, 'create']);
    Route::post('form-asset-add', [AssetManagementController::class, 'store']);
    Route::get('list-asset-delete/{id_asset}', [AssetManagementController::class, 'delete']);
    Route::get('form-asset-edit/{id_asset}', [AssetManagementController::class, 'edit']);
    Route::post('form-asset-update', [AssetManagementController::class, 'update']);
    Route::get('list-asset-search', [AssetManagementController::class, 'search']);
    // mengambil data sub category berdasarkan category yang dipilih
    Route::get('/get-sub-categories/{categoryId}', [AssetManagementController::class, 'getSubCategories']);

    // management - attendance
    Route::get('/list-attendance', [AttendanceController::class, 'index'])->middleware('auth');
    Route::get('list-attendance-search', [AttendanceController::class, 'search']);

    // attendance - leave
    Route::get('/leave-request', [LeaveManagementController::class, 'create'])->middleware('auth');
    Route::get('/leaves-summary', [LeaveManagementController::class, 'index'])->middleware('auth');
    Route::get('leaves-summary-search', [LeaveManagementController::class, 'leave_summary_search']);
    Route::get('/allocation-request', [LeaveManagementController::class, 'allocation'])->middleware('auth');
    Route::get('allocation-request-search', [LeaveManagementController::class, 'allocation_search']);
    Route::post('allocation-request-status', [LeaveManagementController::class, 'allocation_status']);
    Route::post('leave-request-add', [LeaveManagementController::class, 'store']);
    Route::get('leave-request-edit/{id_data_cuti}', [LeaveManagementController::class, 'edit'])->middleware('auth');
    Route::post('leave-request-update', [LeaveManagementController::class, 'update']);
    Route::get('leave-request-delete/{id_data_cuti}', [LeaveManagementController::class, 'delete']);
    Route::post('leave-request-cancel', [LeaveManagementController::class, 'cancel']);
    Route::post('leave-request-print', [LeaveManagementController::class, 'print']);
    Route::post('leave-request-upload', [LeaveManagementController::class, 'upload']);

    Route::get('/collective-leave', [LeaveManagementController::class, 'collective'])->middleware('auth');
    Route::get('collective-leave-search', [LeaveManagementController::class, 'collective_search'])->middleware('auth');
    Route::post('collective-leave-add', [LeaveManagementController::class, 'collective_store'])->middleware('auth');

    // management - payroll
    Route::get('/data-payroll', [PayrollController::class, 'index'])->middleware('auth');
    Route::get('/form-check-payroll', [PayrollController::class, 'check']);
    Route::post('form-payroll-update', [PayrollController::class, 'update']);
    Route::post('form-payroll-print', [PayrollController::class, 'print']);

    // management - daily report
    Route::get('/list-daily-report', [DailyReportManagementController::class, 'index'])->middleware('auth');
    Route::get('/form-daily-report', [DailyReportManagementController::class, 'create'])->middleware('auth');
    Route::post('daily-report-add', [DailyReportManagementController::class, 'store']);
    Route::get('daily-report-edit/{id_daily_report}', [DailyReportManagementController::class, 'edit']);
    Route::get('daily-report-delete/{id_daily_report}', [DailyReportManagementController::class, 'delete']);
    Route::post('daily-report-update', [DailyReportManagementController::class, 'update']);
    Route::get('daily-report-search', [DailyReportManagementController::class, 'search']);

    // import excel
    Route::post('import-excel-employee', [ImportEmployeeexcel::class, 'importExcel']);
    Route::post('import-excel-asset', [ImportAssetexcel::class, 'importExcel']);
    Route::post('import-excel-daily-report', [ImportDailyReport::class, 'ImportDailyReport']);
});

// management - employee
Route::get('/list-employee', [EmployeeManagementController::class, 'index'])->middleware('auth');
Route::get('/form-employee', [EmployeeManagementController::class, 'create'])->middleware('auth');
Route::post('form-employee-add', [EmployeeManagementController::class, 'store']);
Route::get('list-employee-delete/{id_karyawan}', [EmployeeManagementController::class, 'delete']);
Route::get('form-employee-edit/{id_karyawan}', [EmployeeManagementController::class, 'edit'])->middleware('auth');
Route::post('form-employee-update', [EmployeeManagementController::class, 'update']);
Route::get('list-employee-search', [EmployeeManagementController::class, 'search']);
Route::post('list-employee-print', [EmployeeManagementController::class, 'print']);

// management employee inactive
Route::get('/list-inactive-employee', [EmployeeManagementController::class, 'index_inactive'])->middleware('auth');
Route::get('list-inactive-employee-search', [EmployeeManagementController::class, 'search_inactive']);

// attendance - leave
Route::get('/leave-request', [LeaveManagementController::class, 'create'])->middleware('auth');
Route::get('/leaves-summary', [LeaveManagementController::class, 'index'])->middleware('auth');
Route::get('leaves-summary-search', [LeaveManagementController::class, 'leave_summary_search']);
Route::get('/allocation-request', [LeaveManagementController::class, 'allocation'])->middleware('auth');
Route::get('allocation-request-search', [LeaveManagementController::class, 'allocation_search']);
Route::post('leave-request-add', [LeaveManagementController::class, 'store']);
Route::get('leave-request-edit/{id_data_cuti}', [LeaveManagementController::class, 'edit'])->middleware('auth');
Route::post('leave-request-update', [LeaveManagementController::class, 'update']);
Route::get('leave-request-delete/{id_data_cuti}', [LeaveManagementController::class, 'delete']);
Route::post('leave-request-print', [LeaveManagementController::class, 'print']);
Route::post('leave-request-upload', [LeaveManagementController::class, 'upload']);