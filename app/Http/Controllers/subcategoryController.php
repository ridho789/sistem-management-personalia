<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class subcategoryController extends Controller
{
    public function store(Request $request)
    {   
        DB::table('tbl_sub_kategori')->insert([
            'id_kategori'=> $request->id_kategori,
            'nama_sub_kategori'=> $request->input_sub_kategori,
        ]);

        return redirect()->back();
    }

    public function delete($id)
    {   
        DB::table('tbl_sub_kategori')->where('id_sub_kategori', $id)->delete();
        return redirect()->back();
    }

    public function update(Request $request)
    {
        DB::table('tbl_sub_kategori')->where('id_sub_kategori', $request->id_sub_kategori)->update([
            'id_kategori'=> $request->id_kategori,
            'nama_sub_kategori'=> $request->value_sub_kategori,
        ]);
        return redirect()->back();
    }
}
