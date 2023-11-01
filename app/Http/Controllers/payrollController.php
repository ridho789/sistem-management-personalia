<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Employee;
use App\Models\Position;
use App\Models\Divisi;
use Illuminate\Support\Facades\Http;
use Ramsey\Uuid\Type\Integer;

class payrollController extends Controller
{
    public function index() 
    {
        $employee = Employee::all();
        $selectEmployee = null;

        return view('/backend/payroll/data_payroll', [
            'employee' => $employee,
            'selectEmployee' => $selectEmployee
        ]);
    }

    public function check(Request $request)
    {   
        $employee = Employee::all();
        $start_date = date("j F Y", strtotime($request->start_date));
        $end_date = date("j F Y", strtotime($request->end_date));

        $start_month = date("m", strtotime($request->start_date));
        $end_month = date("m", strtotime($request->end_date));
        
        $rangeDate = "Period {$start_date} - {$end_date}";
        $NotValidPeriod = '';

        if ($start_month == $end_month) {
            $url = "https://api-harilibur.vercel.app/api?month={$start_month}";

        } else {
            $NotValidPeriod = "Not Applicable Period";
            $url = "https://api-harilibur.vercel.app/api?month={$start_month}";
        }
        
        // Hitung selisih hari range start date - end date
        $interval = abs(strtotime($request->start_date) - strtotime($request->end_date));
        $days = floor($interval / (60 * 60 * 24));
        
        // Hitung hari libur nasional
        $response = Http::get($url);
        if ($response->successful()) {
            $data = $response->json();
            $nationalHolidayCount = 0;

            foreach ($data as $holiday) {
                if ($holiday['is_national_holiday'] == true) {
                    $holidayDate = strtotime($holiday['holiday_date']);
                    $dayOfWeek = date('N', $holidayDate);

                    // Periksa apakah hari libur nasional bukan hari Minggu (kode 7 adalah hari Minggu)
                    if ($dayOfWeek != 7) {
                        $nationalHolidayCount++;
                    }
                }
            }
        } 

        // Cari employee berdasarkan employee_id
        $selectEmployee = Employee::where('id_karyawan', $request->id_karyawan)->first();

        // Cari data position berdasarkan selectEmployee
        $position = Position::where('id_jabatan', $selectEmployee->id_jabatan)->first();

        // Cari data division berdasarkan selectEmployee
        $division = Divisi::where('id_divisi', $selectEmployee->id_divisi)->first();
        
        return view('/backend/payroll/data_payroll', [
            'employee' => $employee,
            'selectEmployee' => $selectEmployee,
            'position' => $position,
            'division' => $division,
            'rangeDate' => $rangeDate,
            'NotValidPeriod' => $NotValidPeriod
        ]);
    }
}
