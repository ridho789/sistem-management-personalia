<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class companyController extends Controller
{
    public function index()
    {   
        $company = DB::table('tbl_perusahaan')->get();
        return view('/backend/company', ['tbl_perusahaan' => $company]);
    }

    public function store(Request $request)
    {   
        DB::table('tbl_perusahaan')->insert([
            'nama_perusahaan'=> $request->nama_perusahaan,
            'alamat_perusahaan'=> $request->alamat_perusahaan
        ]);
        return redirect()->back();
    }

    public function delete($id)
    {
        DB::table('tbl_perusahaan')->where('id_perusahaan', $id)->delete();
        return redirect()->back();
    }

    public function update(Request $request)
    {
        DB::table('tbl_perusahaan')->where('id_perusahaan', $request->id_perusahaan)->update([
            'nama_perusahaan'=> $request->nama_perusahaan,
            'alamat_perusahaan'=> $request->alamat_perusahaan
        ]);
        return redirect()->back();
    }
}
