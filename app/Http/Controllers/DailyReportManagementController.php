<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Employee;
use App\Models\Divisi;
use App\Models\DailyReport;
use Illuminate\Support\Facades\Crypt;

class DailyReportManagementController extends Controller
{
    public function index() {
        $dailyReport = DailyReport::all();
        $nameEmployee = Employee::pluck('nama_karyawan', 'id_karyawan');
        $idCard = Employee::pluck('id_card', 'id_karyawan');

        // Ambil semua data divisi yang memiliki is_daily_report bernilai true
        $divisiIds = Divisi::where('is_daily_report', true)->pluck('id_divisi');   
        $employee = Employee::whereIn('id_divisi', $divisiIds)
            ->where('is_active', true)
            ->get();

        return view('/backend/daily_report/list_daily_report', [
            'employee' => $employee,
            'dailyReport' => $dailyReport,
            'nameEmployee' => $nameEmployee,
            'idCard' => $idCard
        ]);
    }

    public function create() {
        // Inisiasi variabel
        $errorInfo = '';
        $dailyReport = '';
        $logErrors = '';

        // Ambil semua data divisi yang memiliki is_daily_report bernilai true
        $divisiIds = Divisi::where('is_daily_report', true)->pluck('id_divisi');   
        $employee = Employee::whereIn('id_divisi', $divisiIds)
            ->where('is_active', true)
            ->get();

        return view('/backend/daily_report/form_daily_report', [
            'employee' => $employee,
            'errorInfo' => $errorInfo,
            'dailyReport' => $dailyReport,
            'logErrors' => $logErrors
        ]);
    }

    public function store(Request $request) {
        // Inisiasi variabel
        $errorInfo = '';
        $dailyReport = '';
        $logErrors = '';

        $employeeData = Employee::where('id_karyawan', $request->id_karyawan)->first();
        $date = date("j F Y", strtotime($request->input_work_diary_date));

        $checkDailyReport = DailyReport::where('id_karyawan', $request->id_karyawan)
            ->where('tanggal_catatan_harian', $request->input_work_diary_date)
            ->get();
            
        $divisiIds = Divisi::where('is_daily_report', true)->pluck('id_divisi');   
        $employee = Employee::whereIn('id_divisi', $divisiIds)
            ->where('is_active', true)
            ->get();
            
        if (count($checkDailyReport) > 0 ) {
            $errorInfo = "Sorry, there is already data made for the employee " . 
                $employeeData->nama_karyawan . " - " . $employeeData->id_card . " date: " . $date;
        }
        
        if (empty($errorInfo)) {
            DailyReport::insert([
                'id_karyawan' => $request->id_karyawan,
                'tanggal_catatan_harian' => $request->input_work_diary_date,
                'keterangan' => $request->input_information,
                'dibuat_oleh' => $request->user
            ]);

            return redirect('/list-daily-report');

        } else {
            return view('/backend/daily_report/form_daily_report', [
                'employee' => $employee,
                'errorInfo' => $errorInfo,
                'dailyReport' => $dailyReport,
                'logErrors' => $logErrors
            ]);
        }
    }

    public function edit($id) {
        // Inisiasi variabel
        $errorInfo = '';
        $logErrors = '';

        // Dekripsi ID
        $id = Crypt::decrypt($id);

        $dailyReport = DailyReport::where('id_catatan_harian', $id)->first();
        $employee = Employee::where('id_karyawan', $dailyReport->id_karyawan)->get();

        return view('/backend/daily_report/form_daily_report', [
            'employee' => $employee,
            'errorInfo' => $errorInfo,
            'dailyReport' => $dailyReport,
            'logErrors' => $logErrors
        ]);
    }

    public function delete($id) {
        DailyReport::where('id_catatan_harian', $id)->delete();
        return redirect('/list-daily-report');
    }

    public function update(Request $request) {
        $dailyReportData = [
            'id_karyawan' => $request->id_karyawan,
            'tanggal_catatan_harian' => $request->input_work_diary_date,
            'keterangan' => $request->input_information,
            'diperbaharui_oleh' => $request->user
        ];

        // Inisiasi variabel
        $errorInfo = '';
        $logErrors = '';

        $employeeData = Employee::where('id_karyawan', $request->id_karyawan)->first();
        $date = date("j F Y", strtotime($request->input_work_diary_date));

        // Cek data inputan
        $dailyReport = DailyReport::where('id_catatan_harian', $request->id)->first();
        $employee = Employee::where('id_karyawan', $dailyReport->id_karyawan)->get();
        
        if ($dailyReport->tanggal_catatan_harian != $request->input_work_diary_date) {
            $checkDailyReport = DailyReport::where('id_karyawan', $request->id_karyawan)
                ->where('tanggal_catatan_harian', $request->input_work_diary_date)
                ->get();

            if (count($checkDailyReport) > 0 ) {
                $errorInfo = "Sorry, there is already data made for the employee " . 
                    $employeeData->nama_karyawan . " - " . $employeeData->id_card . " date: " . $date;
            }
        }
        
        if (empty($errorInfo)) {
            DailyReport::where('id_catatan_harian', $request->id)->update($dailyReportData);
            return redirect('/list-daily-report');

        } else {

            return view('/backend/daily_report/form_daily_report', [
                'employee' => $employee,
                'errorInfo' => $errorInfo,
                'dailyReport' => $dailyReport,
                'logErrors' => $logErrors
            ]);
        }
    }

    public function search(Request $request) {
        $id_karyawan = $request->id_karyawan;
        $start_date_range = $request->start_date;
        $end_date_range = $request->end_date;

        $query = DailyReport::query();

        if ($id_karyawan) {
            $query->where('id_karyawan', $id_karyawan);
        }

        if ($start_date_range && $end_date_range) {
            $query->whereBetween('tanggal_catatan_harian', [$start_date_range, $end_date_range]);
        }

        if (!$id_karyawan && (!$start_date_range || !$end_date_range)) {
            return redirect('/list-daily-report');

        } else {
            $dailyReport = $query->get();
        }

        $nameEmployee = Employee::pluck('nama_karyawan', 'id_karyawan');
        $idCard = Employee::pluck('id_card', 'id_karyawan');

        // Ambil semua data divisi yang memiliki is_daily_report bernilai true
        $divisiIds = Divisi::where('is_daily_report', true)->pluck('id_divisi');   
        $employee = Employee::whereIn('id_divisi', $divisiIds)
            ->where('is_active', true)
            ->get();
        
        if (count($dailyReport) == 0) {
            return redirect('/list-daily-report');

        } else {
            return view('/backend/daily_report/list_daily_report', [
                'dailyReport' => $dailyReport,
                'nameEmployee' => $nameEmployee,
                'idCard' => $idCard,
                'employee' => $employee
            ]);
        }

    }
}
