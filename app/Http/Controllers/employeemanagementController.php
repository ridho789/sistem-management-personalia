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
        return view('/backend/employee/list_employee', ['tbl_karyawan' => $employee]);
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
            'val_name' => 'required',
            'val_nik' => 'required|min:20',
            'val_name' => 'required',
            'val_place_birth' => 'required',
            'val_date_birth' => 'required',
            'val_gender' => 'required',
            'val_phone' => 'required',
            'val_address' => 'required',
            'val_photo' => 'required|image|mimes:jpeg,png,jpg,gif,svg|max:2048',
            'val_position' => 'required',
            'val_division' => 'required',
            'val_company' => 'required',
            'val_status' => 'required',
            'val_idcard' => 'required|min:6',
        ]);

        $photo = $request->file('val_photo');
        dd($photo);
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
                'id_jabatan'=> $request->val_position,
                'id_divisi'=> $request->val_division,
                'id_perusahaan'=> $request->val_company,
                'id_status'=> $request->val_status,
            ]);
        }
        return redirect()->back();
    }
}
