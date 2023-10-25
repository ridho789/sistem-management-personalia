<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Models\Employee;
use App\Models\Divisi;
use App\Models\Attendance;
use Illuminate\Support\Facades\Schema;

class attendanceController extends Controller
{
    public function index() {
        $employee = Employee::all();
        $id_employee = $employee->pluck('id_card');

        if (Schema::hasTable('tbl_absensi')) {
            $tbl_absensi = DB::table('tbl_absensi')->whereIn('cardNo', $id_employee)->orderBy('authTime', 'asc')->get();

            if ($tbl_absensi->isEmpty()) {
                $attendance = null;

            } else {
                $attendance = $tbl_absensi;
            }

        } else {
            $attendance = null;
        }
        
        if ($attendance) {
            foreach ($attendance as $data) {
                $timeString = $data->authTime;
                list($timeFormat) = explode('.', $timeString);
                list($timeHour, $timeMinute, $timeSecond) = explode(':', $timeFormat);
        
                // Cek hari
                $dateAttendance = $data->authDate;
                $dayOfWeek = date('w', strtotime($dateAttendance));
                
                // Cari data karyawan berdasarkan id card
                $dataEmployee = Employee::where('id_card', $data->cardNo)->first();

                // Cari divisi
                $divisi = Divisi::where('id_divisi', $dataEmployee->id_divisi)->first();
                $nameDivisi = strtolower($divisi->nama_divisi);

                $sign_in = ($timeHour >= 6 && $timeHour <= 9) ? $timeFormat : null;

                // Menghitung sign_in_late jika melebihi jam 08:00
                $limitJam = 8;
                $limitMenit = 0;
                $limitDetik = 0;

                $sign_in_late = '';
                if ($sign_in) {
                    if ($timeHour > $limitJam || ($timeHour == $limitJam && $timeMinute > $limitMenit) || 
                        ($timeHour == $limitJam && $timeMinute == $limitMenit && $timeSecond > $limitDetik)) {
                        $sign_in_late = ($timeHour - $limitJam) * 3600 + ($timeMinute - $limitMenit) * 60 + ($timeSecond - $limitDetik);
                    }
                }
                
                // Cek apakah sign_in_late berisi data
                if ($sign_in_late) {
                    $late = gmdate("H:i:s", $sign_in_late);

                } else {
                    $late = null;
                }

                // Jika divisi keuangan, personalia / admin dan marketing
                if ($dayOfWeek == 6 && $divisi && in_array($nameDivisi, ['keuangan', 'personalia / admin', 'marketing'])) {
                    $sign_out = ($timeHour >= 12 && $timeHour <= 22) ? $timeFormat : null;
                } else {
                    $sign_out = ($timeHour >= 17 && $timeHour <= 22) ? $timeFormat : null;
                }
        
                // Memeriksa apakah sudah ada data
                $existingRecord = Attendance::where('id_card', $data->cardNo)
                ->whereDate('attendance_date', $data->authDate)
                ->first();

                if ($existingRecord) {
                    $updateData = [];

                    if (empty($existingRecord->sign_in)) {
                        $updateData['sign_in'] = $sign_in;
                    }

                    if (empty($existingRecord->sign_out)) {
                        $updateData['sign_out'] = $sign_out;
                    }

                    if (!empty($updateData)) {
                        Attendance::where('id_card', $existingRecord->id_card)
                            ->whereDate('attendance_date', $existingRecord->attendance_date)
                            ->update($updateData);
                    }

                } else {
                    Attendance::insert([
                        'employee' => $dataEmployee->id_karyawan,
                        'id_card' => $data->cardNo,
                        'attendance_date' => $data->authDate,
                        'sign_in' => $sign_in,
                        'sign_out' => $sign_out,
                        'sign_in_late' => $late
                    ]);
                }
            }
        }
    
        $allattendance = Attendance::orderBy('attendance_date', 'asc')->get();
        $nameEmployee = Employee::pluck('nama_karyawan', 'id_karyawan');

        return view('/backend/attendance/list_attendance', [
            'allattendance' => $allattendance,
            'nameEmployee' => $nameEmployee 
        ]);
    }
    
}
