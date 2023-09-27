<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Models\Divisi;
use App\Models\Position;
use App\Models\Company;
use App\Models\StatusEmployee;

class employeemanagementController extends Controller
{
    public function index()
    {   
        $employee = DB::table('tbl_karyawan')->get();

        $positions = Position::pluck('nama_jabatan', 'id_jabatan');
        $divisions = Divisi::pluck('nama_divisi', 'id_divisi');
        $companies = Company::pluck('nama_perusahaan', 'id_perusahaan');
        $statuses = StatusEmployee::pluck('nama_status', 'id_status');

        return view('/backend/employee/list_employee', [
            'tbl_karyawan' => $employee, 
            'positions' => $positions, 
            'divisions' => $divisions, 
            'companies' => $companies, 
            'statuses' => $statuses
        ]);
    }

    public function create()
    {   
        $division = Divisi::all();
        $position = Position::all();
        $company = Company::all();
        $statusEmployee = StatusEmployee::all();
        return view('/backend/employee/form_employee', compact('division', 'position', 'company', 'statusEmployee'));
    }

    public function store(Request $request)
    {   
        $request->validate([
            'val_nik' => 'min:16',
            'val_photo' => 'image|mimes:jpeg,png,jpg,gif,svg|max:2048',
            'val_idcard' => 'min:6',
        ]);

        $photo = $request->file('val_photo');
        if ($photo){
            $photoName = $photo->getClientOriginalName();
            $photoPath = $photo->storeAs('images/photo', $photoName);
            
            DB::table('tbl_karyawan')->insert([
                'nama_karyawan'=> $request->val_name,
                'nik'=> $request->val_nik,
                'tempat_lahir'=> $request->val_place_birth,
                'tanggal_lahir'=> $request->val_date_birth,
                'jenis_kelamin'=> $request->val_gender,
                'no_telp'=> $request->val_phone,
                'alamat'=> $request->val_address,
                'foto'=> $photoPath,
                'id_card'=> $request->val_idcard,
                'id_jabatan'=> $request->id_jabatan,
                'id_divisi'=> $request->id_divisi,
                'id_perusahaan'=> $request->id_perusahaan,
                'id_status'=> $request->id_status,
            ]);
        }

        $employee = DB::table('tbl_karyawan')->get();
        $positions = Position::pluck('nama_jabatan', 'id_jabatan');
        $divisions = Divisi::pluck('nama_divisi', 'id_divisi');
        $companies = Company::pluck('nama_perusahaan', 'id_perusahaan');
        $statuses = StatusEmployee::pluck('nama_status', 'id_status');

        return view('/backend/employee/list_employee', [
            'tbl_karyawan' => $employee, 
            'positions' => $positions, 
            'divisions' => $divisions, 
            'companies' => $companies, 
            'statuses' => $statuses
        ]);
    }
}
