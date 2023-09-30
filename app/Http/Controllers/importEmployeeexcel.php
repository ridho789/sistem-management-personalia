<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Maatwebsite\Excel\Facades\Excel;
use App\Imports\EmployeesImport;
use Illuminate\Support\Facades\DB;
use App\Models\Divisi;
use App\Models\Position;
use App\Models\Company;
use App\Models\StatusEmployee;
use Illuminate\Support\Facades\Log;

class importEmployeeexcel extends Controller
{
    public function importExcel(Request $request)
    {   
        $request->validate([
            'file' => 'required|mimes:xlsx|max:2048',
        ]);

        $file = $request->file('file');
        if ($file){
            $import = new EmployeesImport;
            
            try {
                Excel::import($import, $file);
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
                    'statuses' => $statuses,
                ]);
                
            } catch (\Exception $e) {
                $logErrors = $import->getLogErrors();
                $sqlErrors = $e->getMessage();

                if (!empty($sqlErrors)){
                    $logErrors = $sqlErrors;
                }

                $employee = '';
                $division = Divisi::all();
                $position = Position::all();
                $company = Company::all();
                $statusEmployee = StatusEmployee::all();
                return view('/backend/employee/form_employee', [
                    'employee' => $employee, 
                    'division' => $division, 
                    'position' => $position, 
                    'company' => $company, 
                    'statusEmployee' => $statusEmployee,
                    'logErrors' => $logErrors
                ]);
            }
        }
    }
}
