<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Imports\DailyReportImport;
use Maatwebsite\Excel\Facades\Excel;
use App\Models\Employee;
use App\Models\Divisi;

class ImportDailyReport extends Controller
{
    public function ImportDailyReport(Request $request) {
        $request->validate([
            'file' => 'required|mimes:xlsx|max:2048',
        ]);

        $file = $request->file('file');
        if ($file){
            $import = new DailyReportImport;
            
            try {
                Excel::import($import, $file);
                $logErrors = $import->getLogErrors();

                if ($logErrors) {
                    $dailyReport = '';
                    $errorInfo = '';
                    $divisiIds = Divisi::where('is_daily_report', true)->pluck('id_divisi');   
                    $employee = Employee::whereIn('id_divisi', $divisiIds)
                        ->where('is_active', true)
                        ->get();

                    return view('/backend/daily_report/form_daily_report', [
                        'employee' => $employee,
                        'logErrors' => $logErrors,
                        'errorInfo' => $errorInfo,
                        'dailyReport' => $dailyReport,
                    ]);

                } else {
                    return redirect('/list-daily-report');
                }
                
            } catch (\Exception $e) {
                $sqlErrors = $e->getMessage();

                if (!empty($sqlErrors)){
                    $logErrors = $sqlErrors;
                }

                // Inisiasi variabel
                $errorInfo = '';
                $dailyReport = '';

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
        }
    }
}
