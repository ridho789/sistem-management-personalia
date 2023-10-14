<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Employee;
use App\Models\Position;
use App\Models\Divisi;
use App\Models\DataLeave;
use App\Models\TypeLeave;

class attendancemanagementController extends Controller
{
    // leave
    public function index(){
        $dataleave = DataLeave::all();
        $employee = Employee::pluck('nama_karyawan', 'id_karyawan');
        $idcard = Employee::pluck('id_card', 'id_karyawan');
        $typeleave = TypeLeave::pluck('nama_tipe_cuti', 'id_tipe_cuti');

        return view('/backend/attendance/leave/leaves_summary', [
            'dataleave' => $dataleave,
            'employee' => $employee,
            'idcard' => $idcard,
            'typeleave' => $typeleave
        ]);
    }

    public function create(){
        $employee = Employee::all();
        $position = Position::pluck('nama_jabatan', 'id_jabatan');
        $division = Divisi::pluck('nama_divisi', 'id_divisi');
        $typeLeave = TypeLeave::all();
        return view('/backend/attendance/leave/leave_request', [
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
                'status_cuti' => 'To Submit'
            ]);
        }

        return redirect('/leaves-summary');
    }
}
