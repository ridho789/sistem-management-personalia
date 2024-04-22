<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\TypeLeave;


class LeaveTypeController extends Controller
{
    public function index()
    {   
        $typeLeave = TypeLeave::orderBy('nama_tipe_cuti')->get();
        return view('/backend/master/leave_type', ['tbl_tipe_cuti' => $typeLeave]);
    }

    public function store(Request $request)
    {   
       TypeLeave::insert([
            'nama_tipe_cuti'=> $request->input_tipe_cuti,
        ]);

        return redirect()->back();
    }

    public function delete($id)
    {   
        TypeLeave::where('id_tipe_cuti', $id)->delete();
        return redirect()->back();
    }

    public function update(Request $request)
    {
        TypeLeave::where('id_tipe_cuti', $request->id_tipe_cuti)->update([
            'nama_tipe_cuti'=> $request->value_tipe_cuti,
        ]);
        return redirect()->back();
    }
}
