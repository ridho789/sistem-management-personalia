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
use Illuminate\Support\Facades\Crypt;
use PDF;
use Ramsey\Uuid\Type\Integer;

class leavemanagementController extends Controller
{
    // leave
    public function index(){
        $dataleave = DataLeave::all();
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

    public function create(){
        $dataleave = '';
        // filter hanya karyawan status kontrak
        $statusEmployee = StatusEmployee::whereRaw("LOWER(nama_status) = LOWER('kontrak')")->value('id_status');
        $employee = Employee::where('id_status', $statusEmployee)->get();
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
        if ($file){
            $fileName = $file->getClientOriginalName();
            $filePath = $file->storeAs('images/leave', $fileName);
            
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
        }

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
            
            // $dataCuti = DataLeave::where('id_data_cuti', $request->id_data_cuti)->first();
            // // Mengambil semua data cuti dengan id karyawan yang sama
            // $dataCutiSamaKaryawan = DataLeave::where('id_karyawan', $dataCuti['id_karyawan'])->get();
            // dd($dataCutiSamaKaryawan['id_karyawan']);

            // // Menghitung total durasi
            // $totalDurasi = $dataCutiSamaKaryawan->sum('durasi_cuti');

            // // Mengurangi total durasi dari sisa cuti yang ada di tabel alokasi request
            // $sisacuti = 12 - $totalDurasi;

            // // Update tabel alokasi request
            // AllocationRequest::insert([
            //     'id_karyawan' => $dataCuti['id_karyawan'],
            //     'id_data_cuti' => $request->id_data_cuti,
            //     'sisa_cuti' => $sisacuti,
            // ]);

        }
 
        return redirect('/leaves-summary');
    }
}
