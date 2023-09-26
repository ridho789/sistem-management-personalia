<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class positionController extends Controller
{
    public function index()
    {   
        $position = DB::table('tbl_jabatan')->get();
        return view('/backend/position', ['tbl_jabatan' => $position]);
    }

    public function store(Request $request)
    {   
        DB::table('tbl_jabatan')->insert([
            'nama_jabatan'=> $request->input_jabatan,
        ]);
        return redirect()->back();
    }

    public function delete($id)
    {
        DB::table('tbl_jabatan')->where('id_jabatan', $id)->delete();
        return redirect()->back();
    }

    public function update(Request $request)
    {
        DB::table('tbl_jabatan')->where('id_jabatan', $request->id_jabatan)->update([
            'nama_jabatan'=> $request->value_jabatan,
        ]);
        return redirect()->back();
    }
}
