<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class positionController extends Controller
{
    public function index()
    {   
        $position = DB::table('tbl_jabatan')->get();
        return view('/backend/master/position', ['tbl_jabatan' => $position]);
    }

    public function store(Request $request)
    {   
        $amountPositionAllowance = $request->input_tunjangan_jabatan;

        // Hapus semua karakter selain angka
        // $numericAmountPositionAllowance = preg_replace("/[^0-9]/", "", explode(",", $amountPositionAllowance)[0]);

        DB::table('tbl_jabatan')->insert([
            'nama_jabatan'=> $request->input_jabatan,
            'tunjangan_jabatan'=> $amountPositionAllowance
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
        $amountPositionAllowance = $request->value_tunjangan_jabatan;

        // Hapus semua karakter selain angka
        // $numericAmountPositionAllowance = preg_replace("/[^0-9]/", "", explode(",", $amountPositionAllowance)[0]);

        DB::table('tbl_jabatan')->where('id_jabatan', $request->id_jabatan)->update([
            'nama_jabatan'=> $request->value_jabatan,
            'tunjangan_jabatan'=> $amountPositionAllowance
        ]);

        return redirect()->back();
    }
}
