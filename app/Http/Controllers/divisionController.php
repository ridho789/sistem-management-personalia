<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class divisionController extends Controller
{
    public function index()
    {   
        $division = DB::table('tbl_divisi')->get();
        return view('/backend/master/division', ['tbl_divisi' => $division]);
    }

    public function store(Request $request)
    {   
        DB::table('tbl_divisi')->insert([
            'nama_divisi'=> $request->input_divisi,
            'kode_divisi'=> $request->input_code_divisi,
        ]);

        return redirect()->back();
    }

    public function delete($id)
    {   
        DB::table('tbl_divisi')->where('id_divisi', $id)->delete();
        return redirect()->back();
    }

    public function update(Request $request)
    {
        DB::table('tbl_divisi')->where('id_divisi', $request->id_divisi)->update([
            'nama_divisi'=> $request->value_divisi,
            'kode_divisi'=> $request->value_code_divisi,
        ]);
        return redirect()->back();
    }
}
