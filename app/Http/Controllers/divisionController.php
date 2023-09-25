<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class divisionController extends Controller
{
    public function index()
    {   
        $division = DB::table('tbl_divisi')->get();
        return view('/frontend/division', ['tbl_divisi' => $division]);
    }

    public function store(Request $request)
    {   
        DB::table('tbl_divisi')->insert([
            'nama_divisi'=> $request->input_divisi,
        ]);

        return redirect()->back();
    }

    public function delete($id)
    {
        DB::table('tbl_divisi')->where('id_divisi', $id)->delete();
        return redirect()->back();
    }

    public function select($id)
    {
        // $division_selected = DB::table('tbl_divisi')->where('id_divisi', $id)->get();
        // return view('/frontend/division', ['tbl_divisi' => $division_selected]);

        return redirect()->back();
    }
}
