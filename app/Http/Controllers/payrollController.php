<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Employee;
use App\Models\Position;
use App\Models\Divisi;
use App\Models\StatusEmployee;
use App\Models\Attendance;
use App\Models\DataLeave;
use App\Models\Payroll;
use App\Models\TypeLeave;
use Illuminate\Support\Facades\Http;
use DateTime;
use PDF;

class PayrollController extends Controller
{
    public function index() 
    {
        $employee = Employee::where('is_active', true)->get();
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
        
        // Tangal awal dan akhir bulan
        $firstDayOfMonth = date('Y-m-01', strtotime($request->start_date));
        $lastDayOfMonth = date('Y-m-t', strtotime($request->start_date));

        $startIntervalOfMonth = new DateTime($firstDayOfMonth);
        $endIntervalOfMonth = new DateTime($lastDayOfMonth);

        // Jumlah hari dalam bulan
        $intervalDaysOfMonth = $startIntervalOfMonth->diff($endIntervalOfMonth);
        $daysOfMonth = $intervalDaysOfMonth->days + 1;

        // Jumlah hari dalam bulan tanpa hari minggu
        $nonSundayCountOfMonth = 0;
        $currentDateOfMonth = clone $startIntervalOfMonth;

        for ($i = 0; $i < $daysOfMonth; $i++) {
            if ($currentDateOfMonth->format('N') != 7) {
                $nonSundayCountOfMonth++;
            }
            
            $currentDateOfMonth->modify('+1 day');
        }

        // Jumlah hari dalam range start date - end date
        $startIntervalDate = new DateTime($request->start_date);
        $endIntervalDate = new DateTime($request->end_date);
        
        $interval = $startIntervalDate->diff($endIntervalDate);
        $days = $interval->days + 1;
        
        // Jumlah hari dalam range tanpa hari minggu
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
                $errorInfo = "Sorry, not applicable period for employee " . $selectEmployee->nama_karyawan . " - " . 
                $selectEmployee->id_card;
            }
        }

        // Cari semua data attendance berdasarkan selectEmployee (working days)
        $attendance = Attendance::where('employee', $selectEmployee->id_karyawan)
            ->where('attendance_date', '>=', $request->start_date)
            ->where('attendance_date', '<=', $request->end_date)
            ->get();

        // Membuat array untuk menyimpan tanggal-tanggal yang ada dalam hasil kueri
        $existingDates = $attendance->pluck('attendance_date')->toArray();

        // Membuat array untuk menyimpan tanggal-tanggal yang tidak ada
        $missingDates = [];

        $checkStartDate = new DateTime($request->start_date);
        $checkEndDate = new DateTime($request->end_date);

        while ($checkStartDate <= $checkEndDate) {
            $formattedDate = $checkStartDate->format('Y-m-d');
            if (!in_array($formattedDate, $existingDates)) {
                if ($nameStatusEmployee == 'harian') {
                    $missingDates[] = $formattedDate;

                } else {
                    if ($nameDivision == 'security') {
                        $missingDates[] = $formattedDate;

                    } else {
                        if ($checkStartDate->format('N') != 7) {
                            $missingDates[] = $formattedDate;
                        }
                    }

                }
            }
            $checkStartDate->modify('+1 day');
        }

        // Cari semua data attendance yang memiliki id_data_cuti
        $countDataLeave = Attendance::where('employee', $selectEmployee->id_karyawan)
            ->where('attendance_date', '>=', $request->start_date)
            ->where('attendance_date', '<=', $request->end_date)
            ->whereNotNull('id_data_cuti')
            ->get();

            
        // Cari semua data cuti
        $id_data_cuti = $countDataLeave->pluck('id_data_cuti');
        $dataLeave = DataLeave::whereIn('id_data_cuti', $id_data_cuti)->get();

        // Tipe cuti
        $typeleave = TypeLeave::pluck('nama_tipe_cuti', 'id_tipe_cuti');

        // Cari semua data attendance yang telat
        $lateAttendance = Attendance::where('employee', $selectEmployee->id_karyawan)
            ->where('attendance_date', '>=', $request->start_date)
            ->where('attendance_date', '<=', $request->end_date)
            ->whereNotNull('sign_in_late')
            ->get();

        // Jika tidak ada data attendance
        if (count($attendance) == 0) {
            $errorInfo = "Sorry, there is no data for employees " . $selectEmployee->nama_karyawan . " - " . 
            $selectEmployee->id_card . " in that period.";
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
        $absentCuts = 0;
        $lateCuts = 0;

        if (count($countDataLeave) > 0) {
            foreach ($countDataLeave as $leave) {
                if (stripos($leave->information, 'legal') !== false) {
                    $countLegalLeave += 1;
                }

                if (stripos($leave->information, 'sick') !== false) {
                    $countSickLeave += 1;
                }
            }
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

            // Jika kurang atau lebih dari 7 hari
            if ($workingDays < 7 || $workingDays > 7) {
                $errorInfo = "Sorry, the total number of days in the date period must be 7 days for " . 
                $selectEmployee->nama_karyawan . " - " . $selectEmployee->id_card . " in that period.";
            }

            $salary = $basic_salary * count($attendance);
            $salary_cuts = 0;

            if (count($attendance) > 0) {
                $salary_cuts = $countlateTime * ($basic_salary / count($attendance));
            }

            $total_salary = $salary - $salary_cuts;

        } else {
            // Akan diupdate
            if ($nameDivision == 'security') {
                if ($namePosition == 'chief') {
                    $workingDays = $nonSundayCount - $nationalHolidayCount;

                } else {
                    $workingDays = $nonSundayCount - $nationalHolidayCount;
                }

            } else {
                $workingDays = $nonSundayCount - $nationalHolidayCount;
            }

            // Jika jumlah hari range date di bawah jumlah hari dalam bulan tersebut
            if ($workingDays < ($nonSundayCountOfMonth - $nationalHolidayCount)) {
                $errorInfo = "Sorry, total number of days not matching the period number of days in the month  " . 
                 "( " . $selectEmployee->nama_karyawan . " - " . $selectEmployee->id_card . " )" . " in that period.";
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
        $absentCuts = number_format($absentCuts, 0, ',', '.');
        $lateCuts = number_format($lateCuts, 0, ',', '.');
    
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
            'tahun' => $start_year,
        ];

        // Check data payroll sudah terbuat atau belum berdasarkan periode
        $checkPayroll = Payroll::where('periode_gaji', $rangeDate)
            ->where('id_karyawan', $request->id_karyawan)
            ->get();

        // Inisiasi variabel
        $payrollId = null;
        $dataPayroll = null;

        if (count($checkPayroll) > 0) {
            $payrollId = $checkPayroll->first()->id_gaji;
            Payroll::where('id_gaji', $payrollId)->delete();
        }

        if (empty($errorInfo)) {
            $payroll = Payroll::create($payrollData);
            $payrollId = $payroll->id;
            $dataPayroll = Payroll::where('id_gaji', $payrollId)->first();
        }

        return view('/backend/payroll/data_payroll', [
            'checkPayroll' => $checkPayroll,
            'payrollId' => $payrollId,
            'dataPayroll' => $dataPayroll,
            'employee' => $employee,
            'selectEmployee' => $selectEmployee,
            'position' => $position,
            'division' => $division,
            'statusEmployee' => $statusEmployee,
            'rangeDate' => $rangeDate,
            'errorInfo' => $errorInfo,
            'payrollData' => $payrollData,
            'lateAttendance' => $lateAttendance,
            'dataLeave' => $dataLeave,
            'typeleave' => $typeleave,
            'missingDates' => $missingDates,
            'absentCuts' => "Rp " . $absentCuts . ",00",
            'lateCuts' => "Rp " . $lateCuts . ",00"
        ]);
    }

    public function update(Request $request) {
        $payroll = Payroll::where('id_gaji', $request->id)->first();
    
        $payrollData = [
            'potongan' => $request->total_salary_deductions,
            'total_gaji' => $request->total_salary,
            'jumlah_hari_kerja' => $request->working_days,
            'jumlah_hari_sakit' => $request->sick_days,
            'jumlah_hari_tidak_masuk' => $request->absent_days,
            'jumlah_hari_cuti_resmi' => $request->leave_days,
            'catatan' => $request->noted
        ];
    
        // Update data if there are changes
        $changesDetected = false;
    
        foreach ($payrollData as $key => $value) {
            if ($payroll->$key != $value) {
                $changesDetected = true;
                break;
            }
        }
    
        if ($changesDetected) {
            Payroll::where('id_gaji', $request->id)->update($payrollData);
        }
    
        return redirect()->back();
    }

    public function print(Request $request) {
        $payroll = Payroll::where('id_gaji', $request->id_payroll)->first();
        $employee = Employee::where('id_karyawan', $payroll->id_karyawan)->first();

        $position = Position::pluck('nama_jabatan', 'id_jabatan');
        $division = Divisi::pluck('nama_divisi', 'id_divisi');
        $status = StatusEmployee::pluck('nama_status', 'id_status');

        $pdf = PDF::loadView('backend.payroll.pdf_payslip', [
            'payroll' => $payroll,
            'employee' => $employee,
            'position' => $position,
            'division' => $division,
            'status' => $status
        ]);

        $filename = 'Payslip ' . $employee->nama_karyawan . ' - ' . $employee->id_card . ' (' . $payroll->periode_gaji . ')' . '.pdf';
        return $pdf->download($filename);
    }
}
