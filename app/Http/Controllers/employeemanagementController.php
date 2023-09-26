<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class employeemanagementController extends Controller
{
    public function index()
    {   
        $employee = DB::table('tbl_karyawan')->get();
        return view('/backend/employee/list_employee', ['tbl_karyawan' => $employee]);
    }

    public function store(Request $request)
    {   
        DB::table('tbl_karyawan')->insert([
            'nama_karyawan'=> $request->nama_karyawan,
        ]);
        return redirect()->back();
    }
}
