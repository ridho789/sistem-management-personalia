<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Models\Category;

class CategoryController extends Controller
{
    public function index()
    {   
        $category = DB::table('tbl_kategori')->get();
        $subcategory = DB::table('tbl_sub_kategori')->get();
        $categories = Category::all();
        $dataCategories = Category::pluck('nama_kategori', 'id_kategori');

        return view('/backend/master/category', [
            'tbl_kategori' => $category, 
            'tbl_sub_kategori' => $subcategory,
            'categories' => $categories,
            'dataCategories' => $dataCategories
        ]);
    }

    public function store(Request $request)
    {   
        DB::table('tbl_kategori')->insert([
            'nama_kategori'=> $request->input_kategori,
        ]);

        return redirect()->back();
    }

    public function delete($id)
    {   
        DB::table('tbl_kategori')->where('id_kategori', $id)->delete();
        return redirect()->back();
    }

    public function update(Request $request)
    {
        DB::table('tbl_kategori')->where('id_kategori', $request->id_kategori)->update([
            'nama_kategori'=> $request->value_kategori,
        ]);
        return redirect()->back();
    }
}
