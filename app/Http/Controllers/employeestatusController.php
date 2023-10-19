<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class employeestatusController extends Controller
{
    public function index()
    {   
        $employee_statue = DB::table('tbl_status_kary')->get();
        return view('/backend/master/employee_status', ['tbl_status_kary' => $employee_statue]);
    }

    public function store(Request $request)
    {   
        DB::table('tbl_status_kary')->insert([
            'nama_status'=> $request->nama_status,
            'kode_status'=> $request->kode_status,
        ]);
        return redirect()->back();
    }

    public function delete($id)
    {
        DB::table('tbl_status_kary')->where('id_status', $id)->delete();
        return redirect()->back();
    }

    public function update(Request $request)
    {
        DB::table('tbl_status_kary')->where('id_status', $request->id_status)->update([
            'nama_status'=> $request->nama_status,
            'kode_status'=> $request->kode_status,
        ]);
        return redirect()->back();
    }
}
