<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Models\Employee;
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
        $logErrors = '';
        $employee = '';
        $division = Divisi::all();
        $position = Position::all();
        $company = Company::all();
        $statusEmployee = StatusEmployee::all();
        return view('/backend/employee/form_employee', compact('employee', 'division', 'position', 'company', 'statusEmployee', 'logErrors'));
    }

    public function store(Request $request)
    {   
        $request->validate([
            'val_nik' => 'min:16|unique:tbl_karyawan,nik',
            'val_photo' => 'image|mimes:jpeg,png,jpg,gif,svg|max:2048',
            'val_idcard' => 'min:6|unique:tbl_karyawan,id_card',
        ]);

        $photo = $request->file('val_photo');
        if ($photo){
            $photoName = $photo->getClientOriginalName();
            $photoPath = $photo->storeAs('images', $photoName);
            
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

    public function delete($id)
    {
        DB::table('tbl_karyawan')->where('id_karyawan', $id)->delete();
        return redirect()->back();
    }

    public function edit($id)
    {
        // mengambil data karyawan berdasarkan ID
        $employee = DB::table('tbl_karyawan')->where('id_karyawan', $id)->first();

        $division = Divisi::all();
        $position = Position::all();
        $company = Company::all();
        $statusEmployee = StatusEmployee::all();
        $logErrors = '';

        return view('/backend/employee/form_employee', compact('employee', 'division', 'position', 'company', 'statusEmployee', 'logErrors'));
    }

    public function update(Request $request)
    {   
        $request->validate([
            'val_nik' => 'min:16',
            'val_photo' => 'image|mimes:jpeg,png,jpg,gif,svg|max:2048',
            'val_idcard' => 'min:6',
        ]);

        DB::table('tbl_karyawan')->where('id_karyawan', $request->id)->update([
            'nama_karyawan' => $request->val_name,
            'nik' => $request->val_nik,
            'tempat_lahir' => $request->val_place_birth,
            'tanggal_lahir' => $request->val_date_birth,
            'jenis_kelamin' => $request->val_gender,
            'no_telp' => $request->val_phone,
            'alamat' => $request->val_address,
            'id_jabatan' => $request->id_jabatan,
            'id_divisi' => $request->id_divisi,
            'id_perusahaan' => $request->id_perusahaan,
            'id_status' => $request->id_status,
            'id_card' => $request->val_idcard,
        ]);

        $photo = $request->file('val_photo');
        if ($photo)
        {
            $photoName = $photo->getClientOriginalName();
            $photoPath = $photo->storeAs('images', $photoName);
            $newPhoto = $photoPath;

            DB::table('tbl_karyawan')->where('id_karyawan', $request->id)->update([
                'foto' => $newPhoto,
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

    public function search(Request $request)
    {
        $search = $request->input('search');

        $employees = DB::table('tbl_karyawan')
            ->where('nama_karyawan', 'like', "%$search%")
            ->orWhere('id_card', 'like', "%$search%")
            ->get();

        $positions = Position::pluck('nama_jabatan', 'id_jabatan');
        $divisions = Divisi::pluck('nama_divisi', 'id_divisi');
        $companies = Company::pluck('nama_perusahaan', 'id_perusahaan');
        $statuses = StatusEmployee::pluck('nama_status', 'id_status');

        return view('/backend/employee/list_employee', [
            'tbl_karyawan' => $employees, 
            'positions' => $positions, 
            'divisions' => $divisions, 
            'companies' => $companies, 
            'statuses' => $statuses
        ]);
    }
}
