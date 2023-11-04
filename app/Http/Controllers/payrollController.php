<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Employee;
use App\Models\Position;
use App\Models\Divisi;
use App\Models\StatusEmployee;
use App\Models\Attendance;
use App\Models\Payroll;
use Illuminate\Support\Facades\Http;
use Ramsey\Uuid\Type\Integer;
use DateTime;

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
        $start_date = date("j F Y", strtotime($request->start_date));
        $end_date = date("j F Y", strtotime($request->end_date));

        $start_month = date("m", strtotime($request->start_date));
        $start_year = date("Y", strtotime($request->start_date));

        $end_month = date("m", strtotime($request->end_date));

        $rangeDate = "Period {$start_date} - {$end_date}";
        $errorInfo = '';
        
        // Jumlah hari dalam bulan
        $countDaysMonth = date('t', strtotime($request->start_date));

        // Jumlah hari dalam range start date - end date
        $startIntervalDate = new DateTime($request->start_date);
        $endIntervalDate = new DateTime($request->end_date);
        
        $interval = $startIntervalDate->diff($endIntervalDate);
        $days = $interval->days + 1;
        
        // Jumlah hari tanpa hari minggu
        $nonSundayCount = 0;
        $currentDate = clone $startIntervalDate;

        for ($i = 0; $i < $days; $i++) {
            if ($currentDate->format('N') != 7) {
                $nonSundayCount++;
            }
            
            $currentDate->modify('+1 day');
        }

        // Hitung hari libur nasional
        $url = "https://api-harilibur.vercel.app/api?month={$start_month}";
        $response = Http::get($url);
        $nationalHolidayCount = 0;

        if ($response->successful()) {
            $data = $response->json();

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

        $employee = Employee::all();

        // Cari employee berdasarkan employee_id
        $selectEmployee = Employee::where('id_karyawan', $request->id_karyawan)->first();

        // Cari data position berdasarkan selectEmployee
        $position = Position::where('id_jabatan', $selectEmployee->id_jabatan)->first();
        $namePosition = strtolower($position->nama_jabatan);

        // Cari data division berdasarkan selectEmployee
        $division = Divisi::where('id_divisi', $selectEmployee->id_divisi)->first();
        $nameDivision = strtolower($division->nama_divisi);

        // Cari data status karyawan berdasarkan selectEmployee
        $statusEmployee = StatusEmployee::where('id_status', $selectEmployee->id_status)->first();
        $nameStatusEmployee = strtolower($statusEmployee->nama_status);

        // Cek validasi range date
        if ($start_month != $end_month) {
            if ($nameStatusEmployee != 'harian') {
                $errorInfo = "Sorry, not applicable period for employee " . $selectEmployee->nama_karyawan . " - " . $selectEmployee->id_card;
            }
        }

        // Cari semua data attendance berdasarkan selectEmployee (working days)
        $attendance = Attendance::where('employee', $selectEmployee->id_karyawan)
            ->where('attendance_date', '>=', $request->start_date)
            ->where('attendance_date', '<=', $request->end_date)
            ->get();

        // Cari semua data cuti
        $countDataLeave = Attendance::where('employee', $selectEmployee->id_karyawan)
            ->where('attendance_date', '>=', $request->start_date)
            ->where('attendance_date', '<=', $request->end_date)
            ->whereNotNull('id_data_cuti')
            ->get();

        // Cari semua data attendance yang telat
        $lateAttendance = Attendance::where('employee', $selectEmployee->id_karyawan)
            ->where('attendance_date', '>=', $request->start_date)
            ->where('attendance_date', '<=', $request->end_date)
            ->whereNotNull('sign_in_late')
            ->get();

        // Jika tidak ada data attendance
        if (count($attendance) == 0) {
            $errorInfo = "Sorry, there is no data for employees " . $selectEmployee->nama_karyawan . " - " . $selectEmployee->id_card . " in that period.";
        }

        // Cari jumlah waktu telat
        $countlateTime = 0;

        if ($lateAttendance) {
            foreach ($lateAttendance as $late) {
                $lateTimeParts = explode(':', $late->sign_in_late);
                $hours = (int)$lateTimeParts[0];
                $minutes = (int)$lateTimeParts[1];

                if ($hours > 0) {
                    if ($minutes > 0) {
                        $countlateTime += $hours + 1;

                    } else {
                        $countlateTime += $hours;
                    }

                } else {
                    if ($minutes >= 15) {
                        $countlateTime += 1;
                    }
                }
            }
        }

        // Inisiasi variabel
        $countLegalLeave = 0;
        $countSickLeave = 0;
        $basic_salary = 0;
        $positionAllowance = 0;

        if (count($countDataLeave) > 0) {
            $countLegalLeave = $countDataLeave->where('information', 'like', '%legal%')->count();
            $countSickLeave = $countDataLeave->where('information', 'like', '%sick%')->count();
        }

        // Gaji Pokok
        if ($selectEmployee->gaji_pokok) {
            $basic_salary = preg_replace("/[^0-9]/", "", explode(",", $selectEmployee->gaji_pokok)[0]);
        }

        // Tunjangan Jabatan
        if ($position->tunjangan_jabatan) {
            $positionAllowance = preg_replace("/[^0-9]/", "", explode(",", $position->tunjangan_jabatan)[0]);
        }

        // Hitung jumlah hari kerja
        if ($nameStatusEmployee == 'harian') {
            $workingDays = $days;
            $salary = $basic_salary * count($attendance);
            $salary_cuts = 0;

            if (count($attendance) > 0) {
                $salary_cuts = $countlateTime * ($basic_salary / count($attendance));
            }

            $total_salary = $salary - $salary_cuts;

        } else {
            if ($nameDivision == 'security') {
                if ($namePosition == 'chief') {
                    $workingDays = $nonSundayCount - $nationalHolidayCount;

                } else {
                    $workingDays = $nonSundayCount - $nationalHolidayCount;
                }

            } else {
                $workingDays = $nonSundayCount - $nationalHolidayCount;
            }

            $countAbsent = $workingDays - count($attendance);
            $absentCuts =  $countAbsent * ($basic_salary / 26);
            $lateCuts = $countlateTime * (($basic_salary / 26) / 8);

            $salary = $basic_salary;
            $salary_cuts = $absentCuts + $lateCuts;
            $total_salary = ($salary - $salary_cuts) + $positionAllowance;
        }

        // Mengubah ke format rupiah
        $basic_salary = number_format($basic_salary, 0, ',', '.');
        $positionAllowance = number_format($positionAllowance, 0, ',', '.');
        $salary_cuts = number_format($salary_cuts, 0, ',', '.');
        $total_salary = number_format($total_salary, 0, ',', '.');
    
        $payrollData = [
            'id_karyawan' => $request->id_karyawan,
            'periode_gaji' => $rangeDate,
            'gaji_pokok' => "Rp " . $basic_salary . ",00",
            'tunjangan_jabatan' => "Rp " . $positionAllowance . ",00",
            'potongan' => "Rp " . $salary_cuts . ",00",
            'total_gaji' => "Rp " . $total_salary . ",00",
            'jumlah_hari_kerja' => count($attendance) - ($countLegalLeave + $countSickLeave),
            'jumlah_hari_sakit' => $countSickLeave,
            'jumlah_hari_tidak_masuk' => $workingDays - count($attendance),
            'jumlah_hari_cuti_resmi' => $countLegalLeave,
            'jumlah_hari_telat' => count($lateAttendance),
            'bulan' => date("F", strtotime($request->start_date)),
            'tahun' => $start_year
        ];

        // Check data payroll sudah terbuat atau belum berdasarkan periode
        $checkPayroll = Payroll::where('periode_gaji', $rangeDate)
            ->where('id_karyawan', $request->id_karyawan)
            ->get();

        if ($checkPayroll->isEmpty()) {
            Payroll::insert($payrollData);
        }

        return view('/backend/payroll/data_payroll', [
            'employee' => $employee,
            'selectEmployee' => $selectEmployee,
            'position' => $position,
            'division' => $division,
            'statusEmployee' => $statusEmployee,
            'rangeDate' => $rangeDate,
            'errorInfo' => $errorInfo
        ]);
    }
}
