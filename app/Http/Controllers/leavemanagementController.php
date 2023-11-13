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

class LeaveManagementController extends Controller
{
    public function index(){
        $dataleave = DataLeave::whereHas('employee', function ($query) {
            $query->where('is_active', true);
        })->get();
        
        $employee = Employee::pluck('nama_karyawan', 'id_karyawan');
        $idcard = Employee::pluck('id_card', 'id_karyawan');
        $typeleave = TypeLeave::pluck('nama_tipe_cuti', 'id_tipe_cuti');

        return view('/backend/leave/leaves_summary', [
            'dataleave' => $dataleave,
            'employee' => $employee,
            'idcard' => $idcard,
            'typeleave' => $typeleave
        ]);
    }

    public function leave_summary_search(Request $request){
        $startDate = $request->input('start_date');
        $endDate = $request->input('end_date');

        $employee = Employee::pluck('nama_karyawan', 'id_karyawan');
        $idcard = Employee::pluck('id_card', 'id_karyawan');
        $typeleave = TypeLeave::pluck('nama_tipe_cuti', 'id_tipe_cuti');

        $id_data_cuti = [];

        // Cari id karyawan yang sesuai dengan rentang tanggal yang dicari
        if ($startDate && $endDate) {
            $id_data_cuti = DataLeave::whereDate('mulai_cuti', '>=', $startDate)
                ->whereDate('mulai_cuti', '<=', $endDate)
                ->pluck('id_data_cuti')
                ->toArray();
        }

        // Jika tidak ada hasil pencarian, tampilkan semua
        if (empty($id_data_cuti)) {
            $dataleave = DataLeave::whereHas('employee', function ($query) {
                $query->where('is_active', true);
            })->get();
            
        } else {
            // Temukan yang sesuai dengan id karyawan
            $dataleave = DataLeave::whereIn('id_data_cuti', $id_data_cuti)
                ->join('tbl_karyawan', 'tbl_data_cuti.id_karyawan', '=', 'tbl_karyawan.id_karyawan')
                ->where('tbl_karyawan.is_active', true)
                ->get();
        }

        return view('/backend/leave/leaves_summary', [
            'dataleave' => $dataleave,
            'employee' => $employee,
            'idcard' => $idcard,
            'typeleave' => $typeleave
        ]);
    }

    public function create(){
        $dataleave = '';
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
            'typeLeave' => $typeLeave
        ]);
    }

    public function store(Request $request){
        $request->validate([
            'file' => 'image|mimes:jpeg,png,jpg,gif,svg|max:2048',
        ]);

        $file = $request->file('file');
        $filePath = null;

        if ($file){
            $fileName = $file->getClientOriginalName();
            $filePath = $file->storeAs('images/leave', $fileName);
        }

        DataLeave::insert([
            'id_karyawan'=> $request->id_karyawan,
            'id_penangung_jawab'=> $request->id_penangung_jawab,
            'deskripsi'=> $request->deskripsi,
            'id_tipe_cuti'=> $request->id_tipe_cuti,
            'mulai_cuti'=> $request->datetimestart,
            'selesai_cuti'=> $request->datetimeend,
            'durasi_cuti'=> $request->duration,
            'file'=> $filePath,
            'status_cuti' => 'To Approved',
            'file_approved' => null
        ]);

        return redirect('/leaves-summary');
    }

    public function edit($id){
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
            'typeLeave' => $typeLeave
        ]);
    }

    public function update(Request $request){
        $request->validate([
            'file' => 'image|mimes:jpeg,png,jpg,gif,svg|max:2048',
        ]);

        DataLeave::where('id_data_cuti', $request->id)->update([
            'id_karyawan'=> $request->id_karyawan,
            'id_penangung_jawab'=> $request->id_penangung_jawab,
            'deskripsi'=> $request->deskripsi,
            'id_tipe_cuti'=> $request->id_tipe_cuti,
            'mulai_cuti'=> $request->datetimestart,
            'selesai_cuti'=> $request->datetimeend,
            'durasi_cuti'=> $request->duration,
        ]);

        $file = $request->file('file');
        if ($file){
            $fileName = $file->getClientOriginalName();
            $filePath = $file->storeAs('images/leave', $fileName);

            DataLeave::where('id_data_cuti', $request->id)->update([
                'file'=> $filePath,
            ]);
        }

        return redirect()->back();
    }

    public function delete($id)
    {
        // Hapus data attendance yang berkaitan dengan data cuti
        Attendance::where('id_data_cuti',  $id)->delete();
        
        // Hapus DataLeave berdasarkan id_data_cuti
        DataLeave::where('id_data_cuti', $id)->delete();

        // Hapus AllocationRequest yang tidak memiliki DataLeave yang sesuai
        AllocationRequest::whereNotIn('id_karyawan', function ($query) {
            $query->select('id_karyawan')->from('tbl_data_cuti');
        })->delete();


        return redirect()->back();
    }

    public function print(Request $request){
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

        $pdf = PDF::loadView('backend.leave.pdf_leave_request', [
            'dataleave' => $dataleave,
            'employee' => $employee,
            'responsible' => $responsible,
            'position' => $position,
            'division' => $division,
            'typeLeave' => $typeLeave
        ]);

        $filename = 'Leave Request - ' . $employeeName . '_' . $employeeIDcard . ' (' . $typeLeaveName . ')' . '.pdf';
        return $pdf->download($filename);

    }

    public function upload(Request $request){
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
                $startLeave->addDays($i);
                Attendance::insert([
                    'id_data_cuti' => $request->id_data_cuti,
                    'employee' => $dataCuti['id_karyawan'],
                    'id_card' => $dataEmployee->id_card,
                    'information' => $typeLeave->nama_tipe_cuti,
                    'attendance_date' => $startLeave->toDateString(),
                ]);
            }

            // Mengambil semua data cuti dengan id karyawan yang sama
            $dataCutiSamaKaryawan = DataLeave::where('id_karyawan', $dataCuti['id_karyawan'])
            ->where(function ($query) {
                $query->where('durasi_cuti', '>=', 1);
                })
            ->get();

            // Menghitung total durasi
            $totalDurasi = $dataCutiSamaKaryawan->sum('durasi_cuti');

            // Mengurangi total durasi dari sisa cuti yang ada di tabel alokasi request
            $sisacuti = 12 - $totalDurasi;
            $id_karyawan = $dataCuti['id_karyawan'];

            AllocationRequest::updateOrInsert(
                ['id_karyawan' => $id_karyawan],
                ['sisa_cuti' => $sisacuti]
            );
        }
 
        return redirect('/allocation-request');
    }

    public function allocation() {
        $allocationRequest = AllocationRequest::whereHas('employee', function ($query) {
            $query->where('is_active', true);
        })->get();
        
        $id_karyawan_array = $allocationRequest->pluck('id_karyawan')->toArray();
        $dataCuti = DataLeave::whereIn('id_karyawan', $id_karyawan_array)
            ->where('status_cuti', 'Approved')
            ->get();

        $employee = Employee::pluck('nama_karyawan', 'id_karyawan');
        $idcard = Employee::pluck('id_card', 'id_karyawan');
        $typeleave = TypeLeave::pluck('nama_tipe_cuti', 'id_tipe_cuti');

        $totalDurasiCutiPerKaryawan = DataLeave::whereIn('id_karyawan', $id_karyawan_array)
            ->where('status_cuti', 'Approved')
            ->groupBy('id_karyawan')
            ->selectRaw('id_karyawan, SUM(CASE WHEN durasi_cuti >= 1 THEN durasi_cuti ELSE 0 END) as total_durasi_cuti')
            ->get();

        foreach ($totalDurasiCutiPerKaryawan as $data) {
            $id_karyawan = $data->id_karyawan;
            $totalDurasiCuti = $data->total_durasi_cuti;

            $sisaCuti = ($totalDurasiCuti >= 1) ? (12 - $totalDurasiCuti) : 12;

            $allocationRequestData = AllocationRequest::where('id_karyawan', $id_karyawan)->first();

            if ($allocationRequestData) {
                AllocationRequest::where('id_alokasi_sisa_cuti', $allocationRequestData->id_alokasi_sisa_cuti)
                    ->update([
                        'sisa_cuti' => $sisaCuti,
                    ]);
            }
        }

        return view('/backend/leave/allocation_request', [
            'allocationRequest' => $allocationRequest,
            'employee' => $employee,
            'idcard' => $idcard,
            'dataCuti' => $dataCuti,
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
    
}
