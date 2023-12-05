<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Employee;
use App\Models\Position;
use App\Models\Divisi;
use App\Models\DataLeave;
use App\Models\TypeLeave;
use App\Models\StatusEmployee;
use App\Models\AllocationRequest;
use App\Models\Attendance; 
use Illuminate\Support\Facades\Crypt;
use PDF;
use Carbon\Carbon;
use Ramsey\Uuid\Type\Integer;
use Illuminate\Support\Str;

class LeaveManagementController extends Controller
{
    public function index() {
        $dataleave = DataLeave::whereHas('employee', function ($query) {
            $query->where('is_active', true);
        })->orderBy('mulai_cuti', 'asc')->get();
        
        $employee = Employee::pluck('nama_karyawan', 'id_karyawan');
        $idcard = Employee::pluck('id_card', 'id_karyawan');
        $typeleave = TypeLeave::pluck('nama_tipe_cuti', 'id_tipe_cuti');

        $statusEmployee = StatusEmployee::whereRaw("LOWER(nama_status) = LOWER('harian')")->value('id_status');
        $dataEmployee = Employee::where('is_active', true)
            ->where('id_status', '!=', $statusEmployee)
            ->get();

        return view('/backend/leave/leaves_summary', [
            'dataleave' => $dataleave,
            'dataEmployee' => $dataEmployee,
            'employee' => $employee,
            'idcard' => $idcard,
            'typeleave' => $typeleave
        ]);
    }

    public function leave_summary_search(Request $request) {
        $id_karyawan = $request->id_karyawan;
        $startDate = $request->start_date;
        $endDate = $request->end_date;

        $employee = Employee::pluck('nama_karyawan', 'id_karyawan');
        $idcard = Employee::pluck('id_card', 'id_karyawan');
        $typeleave = TypeLeave::pluck('nama_tipe_cuti', 'id_tipe_cuti');

        $statusEmployee = StatusEmployee::whereRaw("LOWER(nama_status) = LOWER('harian')")->value('id_status');
        $dataEmployee = Employee::where('is_active', true)
            ->where('id_status', '!=', $statusEmployee)
            ->get();

        $query = DataLeave::query();

        if ($id_karyawan) {
            $query->where('id_karyawan', $id_karyawan);
        }

        if ($startDate && $endDate) {
            $query->whereDate('mulai_cuti', '>=', $startDate)
                ->whereDate('mulai_cuti', '<=', $endDate);
        }

        if (!$id_karyawan && (!$startDate || !$endDate)) {
            return redirect('/leaves-summary');

        } else {
            $dataleave = $query->get();
        }

        if (count($dataleave) == 0) {
            return redirect('/leaves-summary');

        } else {
            return view('/backend/leave/leaves_summary', [
                'dataleave' => $dataleave,
                'dataEmployee' => $dataEmployee,
                'employee' => $employee,
                'idcard' => $idcard,
                'typeleave' => $typeleave
            ]);
        }
    }

    public function create() {
        // Inisiasi variabel
        $dataleave = '';
        $errorInfo = '';

        // filter hanya karyawan selain status harian
        $statusEmployee = StatusEmployee::whereRaw("LOWER(nama_status) = LOWER('harian')")->value('id_status');
        $employee = Employee::where('id_status', '!=', $statusEmployee)
            ->where('is_active', true)
            ->get();
            
        $position = Position::pluck('nama_jabatan', 'id_jabatan');
        $division = Divisi::pluck('nama_divisi', 'id_divisi');
        $typeLeave = TypeLeave::all();
        return view('/backend/leave/leave_request', [
            'dataleave' => $dataleave,
            'employee' => $employee,
            'position' => $position,
            'division' => $division,
            'typeLeave' => $typeLeave,
            'errorInfo' => $errorInfo
        ]);
    }

    public function store(Request $request) {
        // Inisiasi variabel
        $errorInfo = '';
        $dataleave = '';
        $resultIntervalDate = [];

        $request->validate([
            'file' => 'image|mimes:jpeg,png,jpg,gif,svg|max:2048',
        ]);

        $file = $request->file('file');
        $filePath = null;

        if ($file){
            $fileName = $file->getClientOriginalName();
            $filePath = $file->storeAs('images/leave', $fileName);
        }

        $date_start = strtotime($request->datetimestart);
        $date_end = strtotime($request->datetimeend);

        while ($date_start <= $date_end) {
            $resultIntervalDate[] = date('Y-m-d', $date_start);
            $date_start = strtotime('+1 day', $date_start);
        }

        $formatresultIntervalDate = implode(", ", $resultIntervalDate);

        // Check data cuti
        $employeeData = Employee::where('id_karyawan', $request->id_karyawan)->first();
        $checkDataCuti = DataLeave::where('id_karyawan', $request->id_karyawan)
            ->where(function ($query) use ($date_start, $date_end) {
                $query->where(function ($subQuery) use ($date_start) {
                    $subQuery->whereRaw('? BETWEEN SUBSTRING_INDEX(rentang_tanggal_cuti, ", ", 1) 
                            AND SUBSTRING_INDEX(rentang_tanggal_cuti, ", ", -1)', [date('Y-m-d', $date_start)])
                            ->where('status_cuti', '!=', 'Cancelled');;
                })
                ->orWhere(function ($subQuery) use ($date_end) {
                    $subQuery->whereRaw('? BETWEEN SUBSTRING_INDEX(rentang_tanggal_cuti, ", ", 1) 
                            AND SUBSTRING_INDEX(rentang_tanggal_cuti, ", ", -1)', [date('Y-m-d', $date_end)])
                            ->where('status_cuti', '!=', 'Cancelled');;
                });
            })
            ->get();


        $startLeave = Carbon::parse($request->datetimestart);
        $endLeave = Carbon::parse($request->datetimeend);

        // Check attendance
        $checkAttendance = Attendance::where('employee', $request->id_karyawan)
            ->whereBetween('attendance_date', [$startLeave->toDateString(), $endLeave->toDateString()])
            ->get();

            
        if (count($checkAttendance) > 0) {
            $listDateAttendance = $checkAttendance->pluck('attendance_date');
            $resultDateAttendance = [];
            
            foreach ($listDateAttendance as $dateAttendance) {
                $dateAttendanceObj = strtotime($dateAttendance);
                $formatDateAttendance = date("d F Y", $dateAttendanceObj);
                $resultDateAttendance[] = $formatDateAttendance;
            }
    
            $formatResultDateAttendance = implode(", ", $resultDateAttendance);

            $errorInfo .= "Sorry, there is employee " . $employeeData->nama_karyawan . " - " . $employeeData->id_card . 
                " attendance data made on the following date: " . $formatResultDateAttendance . "<br>";
        }

        if (count($checkDataCuti) > 0) {
            $dateLeaveStart = date("j F Y", strtotime($request->datetimestart));
            $dateLeaveEnd = date("j F Y", strtotime($request->datetimeend));

            $range_date = $dateLeaveStart . ' - ' . $dateLeaveEnd;
            if ($dateLeaveStart == $dateLeaveEnd) {
                $range_date = $dateLeaveStart;
            }

            $errorInfo .= "Sorry, there is already data leave made for the employee " . 
                $employeeData->nama_karyawan . " - " . $employeeData->id_card . " date: " . $range_date . "<br>";
        }

        if (empty($errorInfo)) {
            DataLeave::insert([
                'id_karyawan'=> $request->id_karyawan,
                'id_penangung_jawab'=> $request->id_penangung_jawab,
                'deskripsi'=> $request->deskripsi,
                'id_tipe_cuti'=> $request->id_tipe_cuti,
                'mulai_cuti'=> $request->datetimestart,
                'rentang_tanggal_cuti' => $formatresultIntervalDate,
                'selesai_cuti'=> $request->datetimeend,
                'durasi_cuti'=> $request->duration,
                'file'=> $filePath,
                'status_cuti' => 'To Approved',
                'file_approved' => null
            ]);

            return redirect('/leaves-summary');

        } else {
            // filter hanya karyawan selain status harian
            $statusEmployee = StatusEmployee::whereRaw("LOWER(nama_status) = LOWER('harian')")->value('id_status');
            $employee = Employee::where('id_status', '!=', $statusEmployee)
                ->where('is_active', true)
                ->get();
                
            $position = Position::pluck('nama_jabatan', 'id_jabatan');
            $division = Divisi::pluck('nama_divisi', 'id_divisi');
            $typeLeave = TypeLeave::all();

            return view('/backend/leave/leave_request', [
                'dataleave' => $dataleave,
                'employee' => $employee,
                'position' => $position,
                'division' => $division,
                'typeLeave' => $typeLeave,
                'errorInfo' => $errorInfo
            ]);
        }

    }

    public function edit($id) {
        // Inisiasi variabel
        $errorInfo = '';

        // Dekripsi ID
        $id = Crypt::decrypt($id);

        $dataleave = DataLeave::where('id_data_cuti', $id)->first();
        $employee = Employee::all();
        $position = Position::pluck('nama_jabatan', 'id_jabatan');
        $division = Divisi::pluck('nama_divisi', 'id_divisi');
        $typeLeave = TypeLeave::all();
        return view('/backend/leave/leave_request', [
            'dataleave' => $dataleave,
            'employee' => $employee,
            'position' => $position,
            'division' => $division,
            'typeLeave' => $typeLeave,
            'errorInfo' => $errorInfo
        ]);
    }

    public function update(Request $request) {
        // Inisiasi variabel
        $errorInfo = '';
        $resultIntervalDate = [];
        $checkDataCuti = [];

        $request->validate([
            'file' => 'image|mimes:jpeg,png,jpg,gif,svg|max:2048',
        ]);

        $date_start = strtotime($request->datetimestart);
        $date_end = strtotime($request->datetimeend);

        while ($date_start <= $date_end) {
            $resultIntervalDate[] = date('Y-m-d', $date_start);
            $date_start = strtotime('+1 day', $date_start);
        }

        $formatresultIntervalDate = implode(", ", $resultIntervalDate);

        // Check data cuti
        $employeeData = Employee::where('id_karyawan', $request->id_karyawan)->first();
        $exitingDataLeave = DataLeave::where('id_data_cuti', $request->id)->first();

        if (date('Y-m-d H:i:s', strtotime($request->datetimestart)) != $exitingDataLeave->mulai_cuti 
            && date('Y-m-d H:i:s', strtotime($request->datetimeend)) != $exitingDataLeave->selesai_cuti) {

            $checkDataCuti = DataLeave::where('id_karyawan', $request->id_karyawan)
                ->where(function ($query) use ($date_start, $date_end) {
                    $query->where(function ($subQuery) use ($date_start) {
                        $subQuery->whereRaw('? BETWEEN SUBSTRING_INDEX(rentang_tanggal_cuti, ", ", 1) 
                                AND SUBSTRING_INDEX(rentang_tanggal_cuti, ", ", -1)', [date('Y-m-d', $date_start)])
                                ->where('status_cuti', '!=', 'Cancelled');
                    })
                    ->orWhere(function ($subQuery) use ($date_end) {
                        $subQuery->whereRaw('? BETWEEN SUBSTRING_INDEX(rentang_tanggal_cuti, ", ", 1) 
                                AND SUBSTRING_INDEX(rentang_tanggal_cuti, ", ", -1)', [date('Y-m-d', $date_end)])
                                ->where('status_cuti', '!=', 'Cancelled');
                    });
                })
                ->get();

        }

        $startLeave = Carbon::parse($request->datetimestart);
        $endLeave = Carbon::parse($request->datetimeend);

        // Check attendance
        $checkAttendance = Attendance::where('employee', $request->id_karyawan)
            ->whereBetween('attendance_date', [$startLeave->toDateString(), $endLeave->toDateString()])
            ->get();
            
        if (count($checkAttendance) > 0) {
            $listDateAttendance = $checkAttendance->pluck('attendance_date');
            $resultDateAttendance = [];
            
            foreach ($listDateAttendance as $dateAttendance) {
                $dateAttendanceObj = strtotime($dateAttendance);
                $formatDateAttendance = date("d F Y", $dateAttendanceObj);
                $resultDateAttendance[] = $formatDateAttendance;
            }
    
            $formatResultDateAttendance = implode(", ", $resultDateAttendance);

            $errorInfo .= "Sorry, there is employee " . $employeeData->nama_karyawan . " - " . $employeeData->id_card . 
                " attendance data made on the following date: " . $formatResultDateAttendance . "<br>";
        }

        if (count($checkDataCuti) > 0) {
            $dateLeaveStart = date("j F Y", strtotime($request->datetimestart));
            $dateLeaveEnd = date("j F Y", strtotime($request->datetimeend));

            $range_date = $dateLeaveStart . ' - ' . $dateLeaveEnd;
            if ($dateLeaveStart == $dateLeaveEnd) {
                $range_date = $dateLeaveStart;
            }

            $errorInfo .= "Sorry, there is already data leave made for the employee " . 
                $employeeData->nama_karyawan . " - " . $employeeData->id_card . " date: " . $range_date . "<br>";
        }

        if (empty($errorInfo)) {
            DataLeave::where('id_data_cuti', $request->id)->update([
                'id_karyawan' => $request->id_karyawan,
                'id_penangung_jawab' => $request->id_penangung_jawab,
                'deskripsi' => $request->deskripsi,
                'id_tipe_cuti' => $request->id_tipe_cuti,
                'mulai_cuti' => $request->datetimestart,
                'selesai_cuti' => $request->datetimeend,
                'rentang_tanggal_cuti' => $formatresultIntervalDate,
                'durasi_cuti' => $request->duration,
            ]);

            $file = $request->file('file');

            if ($file && $file->isValid()) {
                $fileName = $file->getClientOriginalName();
                $filePath = $file->storeAs('images/leave', $fileName);

                DataLeave::where('id_data_cuti', $request->id)->update([
                    'file' => $filePath,
                ]);
            }

            return redirect()->back();

        } else {
            $dataleave = DataLeave::where('id_data_cuti', $request->id)->first();
            $employee = Employee::all();
            $position = Position::pluck('nama_jabatan', 'id_jabatan');
            $division = Divisi::pluck('nama_divisi', 'id_divisi');
            $typeLeave = TypeLeave::all();

            return view('/backend/leave/leave_request', compact('dataleave', 'employee', 'position', 'division', 'typeLeave', 'errorInfo'));
        }
    }

    public function delete($id) {
        // Hapus data attendance yang berkaitan dengan data cuti
        Attendance::where('id_data_cuti',  $id)->delete();
        
        // Hapus DataLeave berdasarkan id_data_cuti
        DataLeave::where('id_data_cuti', $id)->delete();

        return redirect()->back();
    }

    public function cancel(Request $request) {
        $dataLeave = DataLeave::where('id_data_cuti', $request->id)->first();
        
        if ($dataLeave->status_cuti == 'Approved') {
            // Ambil tipe cuti
            $typeLeave = TypeLeave::where('id_tipe_cuti', $dataLeave->id_tipe_cuti)->first();

            // Check alokasi sisa cuti
            $allocationRequest = AllocationRequest::where('id_karyawan', $dataLeave->id_karyawan)
                ->where('id_tipe_cuti', $dataLeave->id_tipe_cuti)->first();

            // Update sisa cuti
            if ($allocationRequest) {
                $sisaCuti = $allocationRequest->sisa_cuti + $dataLeave->durasi_cuti;
                $allocationRequest->update(['sisa_cuti' => $sisaCuti]);
            }

            // Hapus attendance, jika berkaitan dengan data cuti
            Attendance::where('id_data_cuti', $dataLeave->id_data_cuti )
                ->where('information', $typeLeave->nama_tipe_cuti)
                ->delete();

            // Ubah status
            DataLeave::where('id_data_cuti', $request->id)->update([
                'status_cuti' => 'Cancelled',
                'reason' => $request->reason
            ]);

            return redirect('/leaves-summary'); 
        }
    }

    public function print(Request $request) {
        $dataleave = DataLeave::where('id_data_cuti', $request->id_data_cuti)->first();
        $employee = Employee::where('id_karyawan', $dataleave['id_karyawan'])->first();
        $responsible = Employee::where('id_karyawan', $dataleave['id_penangung_jawab'])->first();
        $position = Position::pluck('nama_jabatan', 'id_jabatan');
        $division = Divisi::pluck('nama_divisi', 'id_divisi');
        $typeLeave = TypeLeave::where('id_tipe_cuti', $dataleave['id_tipe_cuti'])->first();

        // Dapatkan nama karyawan & ID card & typeleave
        $employeeName = $employee->nama_karyawan;
        $employeeIDcard = $employee->id_card;
        $typeLeaveName = $typeLeave->nama_tipe_cuti;

        if (Str::contains(strtolower($typeLeave->nama_tipe_cuti), ['assignment', 'tugas', 'surat tugas'])) {
            $imagePath = public_path('asset/images/kop_su.png');
            $imageContent = file_get_contents($imagePath);

            $pdf = PDF::loadView('backend.leave.pdf_assignment_request', [
                'dataleave' => $dataleave,
                'employee' => $employee,
                'responsible' => $responsible,
                'position' => $position,
                'division' => $division,
                'typeLeave' => $typeLeave,
                'imageContent' => $imageContent
            ]);

        } else {
            $pdf = PDF::loadView('backend.leave.pdf_leave_request', [
                'dataleave' => $dataleave,
                'employee' => $employee,
                'responsible' => $responsible,
                'position' => $position,
                'division' => $division,
                'typeLeave' => $typeLeave
            ]);
        }

        $filename = 'Leave Request - ' . $employeeName . '_' . $employeeIDcard . ' (' . $typeLeaveName . ')' . '.pdf';
        return $pdf->download($filename);

    }

    public function upload(Request $request) {
        $request->validate([
            'file_approved' => 'image|mimes:jpeg,png,jpg,gif,svg|max:2048',
        ]);

        $file_upload = $request->file('file_approved');
        if ($file_upload){
            $fileName = $file_upload->getClientOriginalName();
            $filePath = $file_upload->storeAs('images/approved', $fileName);

            DataLeave::where('id_data_cuti', $request->id_data_cuti)->update([
                'file_approved'=> $filePath,
                'status_cuti' => 'Approved'
            ]); 
            
            $dataCuti = DataLeave::where('id_data_cuti', $request->id_data_cuti)->first();
            $typeLeave = TypeLeave::where('id_tipe_cuti', $dataCuti['id_tipe_cuti'])->first();
            $dataEmployee = Employee::where('id_karyawan', $dataCuti['id_karyawan'])->first();

            $startLeave = Carbon::parse($dataCuti->mulai_cuti);
            $endLeave = Carbon::parse($dataCuti->selesai_cuti);

            $difference = $startLeave->diff($endLeave);

            // Jika data leave terbuat, maka akan terbuat data baru di attendance
            for ($i = 0; $i <= $difference->d; $i++) {
                $currentDate = $startLeave->copy()->addDays($i);

                // Check attendance
                $checkAttendance = Attendance::where('employee', $dataCuti['id_karyawan'])
                    ->where('attendance_date', $currentDate->toDateString())
                    ->first();

                if (empty($checkAttendance)) {
                    if (!Str::contains(strtolower($typeLeave->nama_tipe_cuti), ['other', 'permission', 'izin'])) {
                        Attendance::insert([
                            'id_data_cuti' => $request->id_data_cuti,
                            'employee' => $dataCuti['id_karyawan'],
                            'id_card' => $dataEmployee->id_card,
                            'information' => $typeLeave->nama_tipe_cuti,
                            'attendance_date' => $currentDate->toDateString(),
                        ]);
                    }
                }
            }

            if (Str::contains(strtolower($typeLeave->nama_tipe_cuti), ['legal leave', 'cuti tahunan'])) {
                $allocationRequest = AllocationRequest::where('id_karyawan', $dataCuti->id_karyawan)
                    ->where('id_tipe_cuti', $dataCuti->id_tipe_cuti)->first();

                if (empty($allocationRequest)) {
                    AllocationRequest::insert([
                        'id_karyawan' => $dataCuti->id_karyawan,
                        'id_tipe_cuti' => $dataCuti->id_tipe_cuti,
                        'sisa_cuti' => 12 - $dataCuti->durasi_cuti
                    ]);

                } else {
                    $currentRemainingLeave = $allocationRequest->sisa_cuti;
                    $resultRemainingLeave = $currentRemainingLeave - $dataCuti->durasi_cuti;

                    // Update sisa cuti
                    $allocationRequest->update(['sisa_cuti' => $resultRemainingLeave]);
                }
            }
        }
 
        return redirect('/leaves-summary');
    }

    public function allocation() {
        $dataEmployee = [];
        $statusEmployee = StatusEmployee::whereIn('nama_status', ['harian', 'daily'])->first();

        // Cari data tipe cuti berdasarkan tahun saat ini
        $currentYear = Carbon::now()->year;
        $typeLeave = TypeLeave::where('nama_tipe_cuti', 'like', "%$currentYear%")->first();

        if ($statusEmployee) {
            // Cari data karyawan aktif dan status kecuali harian/daily
            $dataEmployee = Employee::where('is_active', true)->whereNotIn('id_status', [$statusEmployee->id_status])->get();
        }

        if (count($dataEmployee) > 0) {
            if ($typeLeave) {
                foreach ($dataEmployee as $data) {
                    $checkAllocationRequest = AllocationRequest::where('id_karyawan', $data->id_karyawan)
                        ->where('id_tipe_cuti', $typeLeave->id_tipe_cuti)->first();

                    if (empty($checkAllocationRequest)) {
                        AllocationRequest::insert([
                            'id_karyawan' => $data->id_karyawan,
                            'id_tipe_cuti' => $typeLeave->id_tipe_cuti,
                            'sisa_cuti' => 12
                        ]);
                    }
                }
            }
        }

        $allocationRequest = AllocationRequest::all();
        $employee = Employee::pluck('nama_karyawan', 'id_karyawan');
        $idcard = Employee::pluck('id_card', 'id_karyawan');
        $typeleave = TypeLeave::pluck('nama_tipe_cuti', 'id_tipe_cuti');
        
        return view('/backend/leave/allocation_request', [
            'allocationRequest' => $allocationRequest,
            'employee' => $employee,
            'idcard' => $idcard,
            'typeleave' => $typeleave
        ]);
    }

    public function allocation_search(Request $request) {
        $search = $request->input('search');
        $employee = Employee::pluck('nama_karyawan', 'id_karyawan');
        $idcard = Employee::pluck('id_card', 'id_karyawan');
        $typeleave = TypeLeave::pluck('nama_tipe_cuti', 'id_tipe_cuti');
    
        // Cari id karyawan yang sesuai dengan nama karyawan / id card yang dicari
        $id_employee = Employee::where(function($query) use ($search) {
            $query->where('nama_karyawan', 'like', "%$search%")
                  ->orWhere('id_card', 'like', "%$search%");
        })->where('is_active', true)->pluck('id_karyawan')->toArray();

        // Jika tidak ada hasil pencarian, tampilkan semua AllocationRequest
        if (empty($id_employee)) {
            $allocationRequest = AllocationRequest::whereHas('employee', function ($query) {
                $query->where('is_active', true);
            })->get();
            
        } else {
            // Temukan AllocationRequest yang sesuai dengan id karyawan
            $allocationRequest = AllocationRequest::whereIn('id_karyawan', $id_employee)->get();
            if ($allocationRequest->count() === 0) {
                $allocationRequest = AllocationRequest::whereHas('employee', function ($query) {
                    $query->where('is_active', true);
                })->get();
            } 
        }
        
        // Temukan DataLeave berdasarkan id karyawan yang ada di AllocationRequest
        $dataCuti = DataLeave::whereIn('id_karyawan', $allocationRequest->pluck('id_karyawan')->toArray())->get();
    
        return view('/backend/leave/allocation_request', [
            'allocationRequest' => $allocationRequest,
            'employee' => $employee,
            'idcard' => $idcard,
            'dataCuti' => $dataCuti,
            'typeleave' => $typeleave
        ]);
    }

    public function national_holiday() {
        $employee = [];
        $division = Divisi::all();
        $logError = '';

        return view('/backend/leave/national_holiday_leave', [
            'employee' => $employee,
            'division' => $division,
            'logError' => $logError
        ]);
    }

    public function national_holiday_search(Request $request) {
        // Inisiasi variabel
        $logError = '';

        $nationalHoliday = $request->information;
        $nationalHolidayArray = json_decode($nationalHoliday, true);

        $nationalHolidayDate = $nationalHolidayArray[0]['holiday_date'];
        $nationalHolidayName = $nationalHolidayArray[0]['holiday_name'];

        // Format tanggal
        $formatNationalHolidayDate = date("j F Y", strtotime($nationalHolidayDate));
        $informationNationalHoliday = $nationalHolidayName . ' - ' . $formatNationalHolidayDate;

        $statusEmployee = StatusEmployee::where(function ($query) {
            $query->whereRaw("LOWER(nama_status) = LOWER('daily')")
                  ->orWhereRaw("LOWER(nama_status) = LOWER('harian')");
        })->value('id_status');

        // Check data cuti
        $checkDataCuti = DataLeave::whereRaw('DATE(mulai_cuti) = ?', [$nationalHolidayDate])
            ->where('status_cuti', '!=', 'Cancelled')
            ->pluck('id_karyawan');

        // Check attendance
        $checkAttendance = Attendance::whereRaw('attendance_date = ?', [$nationalHolidayDate])
            ->pluck('employee');

        if ($request->id_divisi == 'all') {
            $employee = Employee::where('is_active', true)
                ->where('id_status', '!=', $statusEmployee)
                ->whereNotIn('id_karyawan', $checkDataCuti)
                ->whereNotIn('id_karyawan', $checkAttendance)
                ->get();

        } else {
            $employee = Employee::where('id_divisi', $request->id_divisi)
                ->where('is_active', true)
                ->where('id_status', '!=', $statusEmployee)
                ->whereNotIn('id_karyawan', $checkDataCuti)
                ->whereNotIn('id_karyawan', $checkAttendance)
                ->get();
        }

        $division = Divisi::all();
        $namePosistion = Position::pluck('nama_jabatan', 'id_jabatan');
        $nameDivision = Divisi::pluck('nama_divisi', 'id_divisi');
        $nameStatus =StatusEmployee::pluck('nama_status', 'id_status');

        return view('/backend/leave/national_holiday_leave', [
            'logError' => $logError,
            'employee' => $employee,
            'division' => $division,
            'namePosistion' => $namePosistion,
            'nameDivision' => $nameDivision,
            'nameStatus' => $nameStatus,
            'nationalHolidayDate' => $nationalHolidayDate,
            'nationalHolidayName' => $nationalHolidayName,
            'informationNationalHoliday' => $informationNationalHoliday
        ]);
    }
    
    public function national_holiday_store(Request $request) {
        // Inisiasi variabel
        $logError = '';

        $ids = $request->allSelectRow;
        $idArray = explode(',', $ids);

        $date = $request->dateNationalHolday;
        $carbonDateStart = Carbon::parse($date)->setTime(8, 0, 0);
        $carbonDateEnd = Carbon::parse($date)->setTime(17, 0, 0);

        // Cari karyawan yang memiliki jabatan/position HRD
        $position = Position::where(function($query) {
            $query->where('nama_jabatan', 'like', 'HRD')
                  ->orWhere('nama_jabatan', 'like', 'human resource development');
        })->first();

        if ($position) {
            $responsible = Employee::where('id_jabatan', $position->id_jabatan)->first();

            if (empty($responsible)) {
                $logError = 'Please contact the administrator that there are no employees who have the title of HRD 
                    or human resource development in the system.';
            }

        } else {
            $logError = 'Please contact the administrator that there is no HRD 
                or human resource development position in the system.';
        }
        
        // Cari data tipe cuti berdasarkan tahun saat ini
        $currentYear = Carbon::now()->year;
        $typeLeave = TypeLeave::where('nama_tipe_cuti', 'like', "%$currentYear%")->first();

        if (empty($typeLeave)) {
            $logError = 'Please contact the administrator that there is no authorized 
                or annual leave type for the current year in the system.';
        }

        if (empty($logError)) {
            foreach ($idArray as $id) {
                DataLeave::insert([
                    'id_karyawan'=> $id,
                    'id_penangung_jawab'=> $responsible->id_karyawan,
                    'deskripsi'=> $request->descNationalHoliday,
                    'id_tipe_cuti'=> $typeLeave->id_tipe_cuti,
                    'mulai_cuti'=> $carbonDateStart,
                    'selesai_cuti'=> $carbonDateEnd,
                    'durasi_cuti'=> 1,
                    'file'=> null,
                    'status_cuti' => 'Approved',
                    'file_approved' => null
                ]);

                // Update sisa cuti
                $allocationRequest = AllocationRequest::where('id_karyawan', $id)
                    ->where('id_tipe_cuti', $typeLeave->id_tipe_cuti)->first();
    
                if (empty($allocationRequest)) {
                    AllocationRequest::insert([
                        'id_karyawan' => $id,
                        'id_tipe_cuti' => $typeLeave->id_tipe_cuti,
                        'sisa_cuti' => 12 - 1
                    ]);
    
                } else {
                    $currentRemainingLeave = $allocationRequest->sisa_cuti;
                    $resultRemainingLeave = $currentRemainingLeave - 1;
                    $allocationRequest->update(['sisa_cuti' => $resultRemainingLeave]);
                }
            }

            return redirect('/leaves-summary');

        } else {
            $employee = [];
            $division = Divisi::all();

            return view('/backend/leave/national_holiday_leave', [
                'employee' => $employee,
                'division' => $division,
                'logError' => $logError
            ]);
        }
    }

}
