<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Models\Employee;
use App\Models\Divisi;
use App\Models\Attendance;
use Illuminate\Support\Facades\Schema;

class AttendanceController extends Controller
{
    public function index() {
        $employee = Employee::where('is_active', true)->get();
        $id_employee = $employee->pluck('id_card');

        if (Schema::hasTable('tbl_absensi')) {
            $tbl_absensi = DB::table('tbl_absensi')->whereIn('cardNo', $id_employee)->orderBy('authDateTime', 'asc')->get();

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

                // Inisialisasi sign in dan sign out ke default null
                $sign_in = null;
                $sign_out = null;

                // Set normally scheduled
                $sign_in = ($timeHour >= 4 && $timeHour <= 12) ? $timeFormat : null;
                // $sign_out = ($timeHour >= 17 && $timeHour <= 23) ? $timeFormat : null;

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

                // Inisialisasi information ke default null
                $information = null;

                // Jika divisi keuangan, personalia / admin dan marketing
                if ($dayOfWeek == 6 && $divisi && in_array($nameDivisi, ['keuangan', 'personalia / admin', 'marketing'])) {
                    $sign_out = ($timeHour >= 12 && $timeHour <= 23) ? $timeFormat : null;

                } elseif ($divisi && $nameDivisi === 'security') {
                    // Inisialisasi late ke default null
                    $late = null;
                
                    if (($timeHour >= 6 && $timeHour <= 12)) {
                        // Shift pagi (sign in)
                        $limitJam = 8;
                        $limitMenit = 0;
                        $limitDetik = 0;
                        
                        if ($timeHour > $limitJam || ($timeHour == $limitJam && $timeMinute > $limitMenit) || 
                            ($timeHour == $limitJam && $timeMinute == $limitMenit && $timeSecond > $limitDetik)) {
                            $sign_in_late = ($timeHour - $limitJam) * 3600 + ($timeMinute - $limitMenit) * 60 + ($timeSecond - $limitDetik);
                            $late = gmdate("H:i:s", $sign_in_late);
                        }
                
                        $sign_in = $timeFormat;
                        $information = 'Shift Pagi';

                    } elseif ($timeHour >= 16 && $timeHour <= 23) {
                        // Shift malam (sign in)
                        $limitJam = 20;
                        $limitMenit = 0;
                        $limitDetik = 0;
                        
                        if ($timeHour > $limitJam || ($timeHour == $limitJam && $timeMinute > $limitMenit) || 
                            ($timeHour == $limitJam && $timeMinute == $limitMenit && $timeSecond > $limitDetik)) {
                            $sign_in_late = ($timeHour - $limitJam) * 3600 + ($timeMinute - $limitMenit) * 60 + ($timeSecond - $limitDetik);
                            $late = gmdate("H:i:s", $sign_in_late);
                        }
                
                        $sign_in = $timeFormat;
                        $information = 'Shift Malam';
                    }
                
                    // Cek apakah sign_in_late berisi data
                    if (!$late) {
                        $late = null;
                    }

                } elseif ($divisi && $nameDivisi === 'cargo') {
                    // Inisialisasi late ke default null
                    $late = null;

                    if ($timeHour >= 22 || $timeHour <= 2) {
                        if ($timeHour <= 2) {
                            $timeHour+=24;
                        }

                        // Shift malam (sign in)
                        $limitJam = 23;
                        $limitMenit = 0;
                        $limitDetik = 0;

                        if (
                            $timeHour > $limitJam || ($timeHour == $limitJam && $timeMinute > $limitMenit) ||
                            ($timeHour == $limitJam && $timeMinute == $limitMenit && $timeSecond > $limitDetik)) {
                            $sign_in_late = ($timeHour - $limitJam) * 3600 + ($timeMinute - $limitMenit) * 60 + ($timeSecond - $limitDetik);
                            $late = gmdate("H:i:s", $sign_in_late);
                        }

                        $sign_in = $timeFormat;
                        $information = 'Shift Malam';
                    }

                    // Cek apakah sign_in_late berisi data
                    if (!$late) {
                        $late = null;
                    }

                } else {
                    $sign_out = ($timeHour >= 17 && $timeHour <= 23) ? $timeFormat : null;
                }
        
                // Memeriksa apakah sudah ada data
                $existingRecord = Attendance::where('id_card', $data->cardNo)
                ->whereDate('attendance_date', $data->authDate)
                ->first();

                if ($existingRecord) {
                    $updateData = [];
                
                    if ($divisi && $nameDivisi === 'security') {
                        if ($existingRecord->information == 'Shift Pagi') {
                            $sign_out = ($timeHour >= 20 && $timeHour <= 23) ? $timeFormat : null;
                
                            if (empty($existingRecord->sign_out)) {
                                $updateData['sign_out'] = $sign_out;
                            }
                        } 
                        
                    } else {
                        if (empty($existingRecord->sign_in)) {
                            $updateData['sign_in'] = $sign_in;
                        }
            
                        if (empty($existingRecord->sign_out)) {
                            $updateData['sign_out'] = $sign_out;
                        }
            
                        if (empty($existingRecord->information)) {
                            $updateData['information'] = $information;
                        }
                    }
                
                    // Memeriksa apakah ada data yang perlu diperbarui
                    if (!empty($updateData)) {
                        Attendance::where('id_card', $existingRecord->id_card)
                            ->whereDate('attendance_date', $existingRecord->attendance_date)
                            ->update($updateData);
                    }

                } else {
                    if ($divisi && $nameDivisi === 'security') {
                        $dateAttendanceOneAgo = date("Y-m-d", strtotime($data->authDate . " -1 day"));
                        $existingRecordSecurity = Attendance::where('id_card', $data->cardNo)
                            ->whereDate('attendance_date', $dateAttendanceOneAgo)
                            ->first();
                    
                        // Periksa apakah data sudah ada dan tidak perlu diupdate
                        if ($existingRecordSecurity) {
                            // Periksa apakah ada data "sign_out"
                            if (empty($existingRecordSecurity->sign_out)) {
                                $sign_out = ($timeHour >= 8 && $timeHour <= 12) ? $timeFormat : null;
                                $updateData = [];
                    
                                if (empty($existingRecordSecurity->sign_out)) {
                                    $updateData['sign_out'] = $sign_out;
                                }
                    
                                if (!empty($updateData)) {
                                    // Update data jika diperlukan
                                    Attendance::where('id_card', $existingRecordSecurity->id_card)
                                        ->whereDate('attendance_date', $existingRecordSecurity->attendance_date)
                                        ->update($updateData);
                                }

                            } elseif ($timeHour >= 18 && $timeHour <= 23) {
                                // Buat data baru jika waktu masuk antara jam 18.00 - 23.00
                                Attendance::insert([
                                    'employee' => $dataEmployee->id_karyawan,
                                    'id_card' => $data->cardNo,
                                    'attendance_date' => $data->authDate,
                                    'sign_in' => $sign_in,
                                    'sign_out' => $sign_out,
                                    'sign_in_late' => $late,
                                    'information' => $information
                                ]);
                            }

                        } elseif (!$existingRecordSecurity) {
                            // Insert data jika data tidak ada
                            Attendance::insert([
                                'employee' => $dataEmployee->id_karyawan,
                                'id_card' => $data->cardNo,
                                'attendance_date' => $data->authDate,
                                'sign_in' => $sign_in,
                                'sign_out' => $sign_out,
                                'sign_in_late' => $late,
                                'information' => $information
                            ]);
                        }

                    } elseif ($divisi && $nameDivisi === 'cargo') {
                        // Menambahkan logika untuk tidak menampilkan data sign_out
                        $dateAttendanceOneAgo = date("Y-m-d", strtotime($data->authDate . " -1 day"));
                        $existingRecordCargo = Attendance::where('id_card', $data->cardNo)
                            ->whereDate('attendance_date', $dateAttendanceOneAgo)
                            ->first();

                        // Periksa apakah data sudah ada dan tidak perlu diupdate
                        if ($existingRecordCargo) {
                            // Periksa apakah ada data "sign_out"
                            if (empty($existingRecordCargo->sign_out)) {
                                $sign_out = ($timeHour >= 7 && $timeHour <= 12) ? $timeFormat : null;
                                $updateData = [];

                                if (empty($existingRecordCargo->sign_out)) {
                                    $updateData['sign_out'] = $sign_out;
                                }

                                if (!empty($updateData)) {
                                    // Update data jika diperlukan
                                    Attendance::where('id_card', $existingRecordCargo->id_card)
                                        ->whereDate('attendance_date', $existingRecordCargo->attendance_date)
                                        ->update($updateData);
                                }
                            } elseif ($timeHour >= 22 || $timeHour <= 2) {
                                // Buat data baru jika waktu masuk antara jam 22.00 - 02.00
                                Attendance::insert([
                                    'employee' => $dataEmployee->id_karyawan,
                                    'id_card' => $data->cardNo,
                                    'attendance_date' => $data->authDate,
                                    'sign_in' => $sign_in,
                                    'sign_out' => $sign_out,
                                    'sign_in_late' => $late,
                                    'information' => $information
                                ]);
                            }
                        } elseif (!$existingRecordCargo) {
                            // Insert data jika data tidak ada
                            Attendance::insert([
                                'employee' => $dataEmployee->id_karyawan,
                                'id_card' => $data->cardNo,
                                'attendance_date' => $data->authDate,
                                'sign_in' => $sign_in,
                                'sign_out' => $sign_out,
                                'sign_in_late' => $late,
                                'information' => $information
                            ]);
                        }

                    } else {
                        Attendance::insert([
                            'employee' => $dataEmployee->id_karyawan,
                            'id_card' => $data->cardNo,
                            'attendance_date' => $data->authDate,
                            'sign_in' => $sign_in,
                            'sign_out' => $sign_out,
                            'sign_in_late' => $late,
                            'information' => $information
                        ]);
                    }

                }
            }
        }
    
        $allattendance = Attendance::orderBy('attendance_date', 'asc')
            ->whereHas('employee', function ($query) {
                $query->where('is_active', true);
            })->get();

        $nameEmployee = Employee::pluck('nama_karyawan', 'id_karyawan');

        return view('/backend/attendance/list_attendance', [
            'allattendance' => $allattendance,
            'nameEmployee' => $nameEmployee,
            'employee' => $employee
        ]);
    }

    public function search(Request $request) {

        $id_karyawan = $request->id_karyawan;
        $start_date_range = $request->start_date;
        $end_date_range = $request->end_date;

        $query = Attendance::query()->orderBy('attendance_date', 'asc');

        if ($id_karyawan) {
            $query->where('employee', $id_karyawan);
        }

        if ($start_date_range && $end_date_range) {
            $query->whereBetween('attendance_date', [$start_date_range, $end_date_range]);
        }

        if (!$id_karyawan && (!$start_date_range || !$end_date_range)) {
            // Jika tidak ada filter yang diterapkan, tampilkan semua data
            $allattendance = $query->get();
        } else {
            $allattendance = $query->get();
        }

        $employee = Employee::where('is_active', true)->get();
        $nameEmployee = Employee::pluck('nama_karyawan', 'id_karyawan');

        return view('/backend/attendance/list_attendance', [
            'allattendance' => $allattendance,
            'nameEmployee' => $nameEmployee,
            'employee' => $employee
        ]);
    }
    
}
