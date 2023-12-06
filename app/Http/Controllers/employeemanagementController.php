<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Employee;
use App\Models\Divisi;
use App\Models\Position;
use App\Models\Company;
use App\Models\StatusEmployee;
use Illuminate\Support\Facades\Crypt;
use PDF;

class EmployeeManagementController extends Controller
{
    public function index() {   
        $employee = Employee::where('is_active', true)->get();
        $positions = Position::pluck('nama_jabatan', 'id_jabatan');
        $divisions = Divisi::pluck('nama_divisi', 'id_divisi');
        $companies = Company::pluck('nama_perusahaan', 'id_perusahaan');
        $statuses = StatusEmployee::pluck('nama_status', 'id_status');

        $dataDivision = Divisi::all();

        return view('/backend/employee/list_employee', [
            'tbl_karyawan' => $employee, 
            'positions' => $positions, 
            'divisions' => $divisions, 
            'companies' => $companies, 
            'statuses' => $statuses,
            'dataDivision' => $dataDivision
        ]);
    }

    public function index_inactive() {   
        $employee = Employee::where('is_active', false)->get();
        $positions = Position::pluck('nama_jabatan', 'id_jabatan');
        $divisions = Divisi::pluck('nama_divisi', 'id_divisi');
        $companies = Company::pluck('nama_perusahaan', 'id_perusahaan');
        $statuses = StatusEmployee::pluck('nama_status', 'id_status');

        $dataDivision = Divisi::all();

        return view('/backend/employee/list_inactive_employee', [
            'tbl_karyawan' => $employee, 
            'positions' => $positions, 
            'divisions' => $divisions, 
            'companies' => $companies, 
            'statuses' => $statuses,
            'dataDivision' => $dataDivision
        ]);
    }

    public function create() {   
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

    public function store(Request $request) {   
        $request->validate([
            'val_nik' => 'min:16|unique:tbl_karyawan,nik',
            'val_photo' => 'image|mimes:jpeg,png,jpg,gif,svg|max:2048',
            'val_idcard' => 'min:7|max:7|unique:tbl_karyawan,id_card',
        ]);

        $employeeData = [
            'nama_karyawan' => $request->val_name,
            'nik' => $request->val_nik,
            'tempat_lahir' => $request->val_place_birth,
            'tanggal_lahir' => $request->val_date_birth,
            'jenis_kelamin' => $request->val_gender,
            'no_telp' => $request->val_phone,
            'lokasi' => $request->val_location,
            'alamat' => $request->val_address,
            'foto' => null,
            'id_card' => $request->val_idcard,
            'id_jabatan' => $request->id_jabatan,
            'id_divisi' => $request->id_divisi,
            'id_perusahaan' => $request->id_perusahaan,
            'id_status' => $request->id_status,
            'gaji_pokok' => $request->val_basic_salary,
            'awal_bergabung' => $request->val_start_joining
        ];

        $dataStatus = StatusEmployee::where('id_status', $request->id_status)->first();
        $namaStatus = strtolower($dataStatus->nama_status);
        
        if ($dataStatus && $namaStatus == 'kontrak') {
            $employeeData['lama_kontrak'] = $request->val_term_contract;
            $employeeData['awal_masa_kontrak'] = $request->val_start_contract;
            $employeeData['akhir_masa_kontrak'] = $request->val_end_contract;

        } else {
            $employeeData['lama_kontrak'] = null;
            $employeeData['awal_masa_kontrak'] = null;
            $employeeData['akhir_masa_kontrak'] = null;
        }

        $photo = $request->file('val_photo');
        if ($photo){
            $photoName = $photo->getClientOriginalName();
            $photoPath = $photo->storeAs('images', $photoName);
            $employeeData['foto'] = $photoPath;
        }

        Employee::insert($employeeData);
        return redirect('/list-employee');
    }

    public function delete($id) {
       Employee::where('id_karyawan', $id)->delete();
        return redirect('/list-employee');
    }

    public function edit($id) {   
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

    public function update(Request $request) {   
        // Mengambil data karyawan berdasarkan ID
        $existingEmployee = Employee::where('id_karyawan', $request->id)->first();

        // Mengambil id card | nik saat ini dari data yang ada
        $currentIdCard = $existingEmployee->id_card;
        $currentNIK = $existingEmployee->nik;

        $rules = [
            'val_nik' => 'min:16',
            'val_photo' => 'image|mimes:jpeg,png,jpg,gif,svg|max:2048',
            'val_idcard' => 'min:7|max:7',
        ];
    
        // Hanya menjalankan validasi unique jika id card | nik berubah atau tidak sama dengan id card | nik saat ini
        if ($request->val_idcard != $currentIdCard) {
            $rules['val_idcard'] .= '|unique:tbl_karyawan,id_card';
        }

        if ($request->val_nik != $currentNIK) {
            $rules['val_nik'] .= '|unique:tbl_karyawan,nik';
        }
    
        $request->validate($rules);

        $employeeData = [
            'nama_karyawan' => $request->val_name,
            'nik' => $request->val_nik,
            'tempat_lahir' => $request->val_place_birth,
            'tanggal_lahir' => $request->val_date_birth,
            'jenis_kelamin' => $request->val_gender,
            'no_telp' => $request->val_phone,
            'lokasi' => $request->val_location,
            'alamat' => $request->val_address,
            'id_jabatan' => $request->id_jabatan,
            'id_divisi' => $request->id_divisi,
            'id_perusahaan' => $request->id_perusahaan,
            'id_status' => $request->id_status,
            'id_card' => $request->val_idcard,
            'gaji_pokok' => $request->val_basic_salary,
            'awal_bergabung' => $request->val_start_joining,
            'lama_kontrak' => null,
            'awal_masa_kontrak' => null,
            'akhir_masa_kontrak' => null,
            'is_active' => $request->is_active,
            'reason' => $request->val_reason
        ];
        
        $photo = $request->file('val_photo');
        if ($photo) {
            $photoName = $photo->getClientOriginalName();
            $photoPath = $photo->storeAs('images', $photoName);
            $employeeData['foto'] = $photoPath;
        }
        
        $dataStatus = StatusEmployee::where('id_status', $request->id_status)->first();
        $namaStatus = strtolower($dataStatus->nama_status);
        
        if ($dataStatus && $namaStatus == 'kontrak') {
            $employeeData['lama_kontrak'] = $request->val_term_contract;
            $employeeData['awal_masa_kontrak'] = $request->val_start_contract;
            $employeeData['akhir_masa_kontrak'] = $request->val_end_contract;
        }
        
        Employee::where('id_karyawan', $request->id)->update($employeeData);

        $checkEmployee = Employee::where('id_karyawan', $request->id)->first();
        if ($checkEmployee->is_active == true) {
            return redirect('/list-employee');

        } else {
            return redirect('/list-inactive-employee');
        }
    }

    public function search(Request $request) {
        $search = $request->input('search');
        $id_divisi = $request->id_divisi;

        $employees = Employee::where(function ($query) use ($search) {
            $query->where('nama_karyawan', 'like', "%$search%")
                ->orWhere('id_card', 'like', "%$search%");
        })->where('is_active', true)->where('id_divisi', $id_divisi)->get();

        $positions = Position::pluck('nama_jabatan', 'id_jabatan');
        $divisions = Divisi::pluck('nama_divisi', 'id_divisi');
        $companies = Company::pluck('nama_perusahaan', 'id_perusahaan');
        $statuses = StatusEmployee::pluck('nama_status', 'id_status');

        $dataDivision = Divisi::all();

        // Jika tidak ada hasil pencarian, ambil semua karyawan aktif
        if ($employees->isEmpty()) {
            $employees = Employee::where('is_active', true)->get();
        }

        return view('/backend/employee/list_employee', [
            'tbl_karyawan' => $employees,
            'positions' => $positions,
            'divisions' => $divisions,
            'companies' => $companies,
            'statuses' => $statuses,
            'dataDivision' => $dataDivision
        ]);
    }

    public function search_inactive(Request $request) {
        $search = $request->input('search');

        $employees = Employee::where(function ($query) use ($search) {
            $query->where('nama_karyawan', 'like', "%$search%")
                ->orWhere('id_card', 'like', "%$search%");
        })->where('is_active', false)->get();

        $positions = Position::pluck('nama_jabatan', 'id_jabatan');
        $divisions = Divisi::pluck('nama_divisi', 'id_divisi');
        $companies = Company::pluck('nama_perusahaan', 'id_perusahaan');
        $statuses = StatusEmployee::pluck('nama_status', 'id_status');

        // Jika tidak ada hasil pencarian, ambil semua karyawan tidak aktif
        if ($employees->isEmpty()) {
            $employees = Employee::where('is_active', false)->get();
        }

        return view('/backend/employee/list_inactive_employee', [
            'tbl_karyawan' => $employees,
            'positions' => $positions,
            'divisions' => $divisions,
            'companies' => $companies,
            'statuses' => $statuses
        ]);
    }

    public function print(Request $request) {
        // Mendapatkan dataRow & dataSearch dari permintaan POST
        $dataRow = $request->input('dataRow');
        $dataSearch = $request->input('dataSearch');

        // cek apakah dataRow ada
        if ($dataRow){
            // Membagi dataRow menjadi array id karyawan
            $employeeIds = explode(',', $dataRow);
    
            // Mencari data karyawan berdasarkan id
            $employees = Employee::whereIn('id_karyawan', $employeeIds)->get();

        } else {

            // cek apakah dataSearch ada
            if ($dataSearch){
                $employees = Employee::where('nama_karyawan', 'like', "%$dataSearch%")->get();

                // jika tidak ada data hasil seacrh, tampilkan semua
                if ($employees->count() === 0){
                    $employees = Employee::all();
                }

            } else {
                $employees = Employee::all();
            }
        }

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
        ])->setPaper('a4', 'landscape');

        return $pdf->download('Data Employee.pdf');
    }
}
