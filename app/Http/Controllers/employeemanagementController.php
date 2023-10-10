<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Models\Employee;
use App\Models\Divisi;
use App\Models\Position;
use App\Models\Company;
use App\Models\StatusEmployee;
use Illuminate\Support\Facades\Crypt;
use PDF;

class employeemanagementController extends Controller
{
    public function index()
    {   
        $employee = Employee::all();
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
        return view('/backend/employee/form_employee', compact(
            'employee', 'division', 'position', 'company', 'statusEmployee', 'logErrors'
        ));
    }

    public function store(Request $request)
    {   
        $request->validate([
            'val_nik' => 'min:16|unique:tbl_karyawan,nik',
            'val_photo' => 'image|mimes:jpeg,png,jpg,gif,svg|max:2048',
            'val_idcard' => 'min:6|max:6|unique:tbl_karyawan,id_card',
        ]);

        $photo = $request->file('val_photo');
        if ($photo){
            $photoName = $photo->getClientOriginalName();
            $photoPath = $photo->storeAs('images', $photoName);
            
            Employee::insert([
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

        return redirect('/list-employee');
    }

    public function delete($id)
    {
       Employee::where('id_karyawan', $id)->delete();
        return redirect('/list-employee');
    }

    public function edit($id)
    {   
        // Dekripsi ID
        $id = Crypt::decrypt($id);

        // mengambil data karyawan berdasarkan ID
        $employee = Employee::where('id_karyawan', $id)->first();

        $division = Divisi::all();
        $position = Position::all();
        $company = Company::all();
        $statusEmployee = StatusEmployee::all();
        $logErrors = '';

        return view('/backend/employee/form_employee', compact(
            'employee', 'division', 'position', 'company', 'statusEmployee', 'logErrors'
        ));
    }

    public function update(Request $request)
    {   
        $request->validate([
            'val_nik' => 'min:16',
            'val_photo' => 'image|mimes:jpeg,png,jpg,gif,svg|max:2048',
            'val_idcard' => 'min:6|max:6',
        ]);

        Employee::where('id_karyawan', $request->id)->update([
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

           Employee::where('id_karyawan', $request->id)->update([
                'foto' => $newPhoto,
            ]);
        }

        return redirect('/list-employee');
    }

    public function search(Request $request)
    {
        $search = $request->input('search');

        $employees = Employee::
            where('nama_karyawan', 'like', "%$search%")
            ->orWhere('id_card', 'like', "%$search%")
            ->get();

        $positions = Position::pluck('nama_jabatan', 'id_jabatan');
        $divisions = Divisi::pluck('nama_divisi', 'id_divisi');
        $companies = Company::pluck('nama_perusahaan', 'id_perusahaan');
        $statuses = StatusEmployee::pluck('nama_status', 'id_status');

        if ($employees->count() === 0) {
            $employees = Employee::all();
            return view('/backend/employee/list_employee', [
            'tbl_karyawan' => $employees, 
            'positions' => $positions, 
            'divisions' => $divisions, 
            'companies' => $companies, 
            'statuses' => $statuses
        ]);

        } else {
            return view('/backend/employee/list_employee', [
            'tbl_karyawan' => $employees, 
            'positions' => $positions, 
            'divisions' => $divisions, 
            'companies' => $companies, 
            'statuses' => $statuses
        ]);
        }
    }

    public function print(Request $request)
    {
        // Mendapatkan dataRow dari permintaan POST
        $dataRow = $request->input('dataRow');

        // Membagi dataRow menjadi array id karyawan
        $employeeIds = explode(',', $dataRow);

        // Mencari data karyawan berdasarkan id
        $employees = Employee::whereIn('id_karyawan', $employeeIds)->get();
        $positions = Position::pluck('nama_jabatan', 'id_jabatan');
        $divisions = Divisi::pluck('nama_divisi', 'id_divisi');
        $companies = Company::pluck('nama_perusahaan', 'id_perusahaan');
        $statuses = StatusEmployee::pluck('nama_status', 'id_status');

        foreach ($employees as $karyawan) {
            $data[] = [
                'Nama Karyawan' => $karyawan->nama_karyawan,
                'NIK' => $karyawan->nik,
                'Phone' => $karyawan->no_telp,
                'ID Card' => $karyawan->id_card,
                'Position' => $positions[$karyawan->id_jabatan],
                'Division' => $divisions[$karyawan->id_divisi],
                'Company' => $companies[$karyawan->id_perusahaan],
                'Status' => $statuses[$karyawan->id_status],
            ];
        }

        $pdf = PDF::loadView('backend.employee.pdf_employee', [
            'tbl_karyawan' => $employees, 
            'positions' => $positions, 
            'divisions' => $divisions, 
            'companies' => $companies, 
            'statuses' => $statuses
        ]);

        return $pdf->download('Data Employee.pdf');
    }
}
