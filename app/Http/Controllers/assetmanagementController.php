<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Category;
use App\Models\Subcategory;
use App\Models\Company;
use App\Models\Asset;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Crypt;
use PhpOffice\PhpSpreadsheet\Writer\Xlsx\Rels;

class assetmanagementController extends Controller
{
    public function index()
    {
        $asset = Asset::all();
        $category = Category::pluck('nama_kategori', 'id_kategori');
        $subcategory = Subcategory::pluck('nama_sub_kategori', 'id_sub_kategori');
        $company = Company::pluck('nama_perusahaan', 'id_perusahaan');

        return view('/backend/asset_data/list_asset', compact('asset', 'category', 'subcategory', 'company'));
    }

    public function create()
    {   
        $asset = '';
        $company = Company::all();
        $category = Category::all();
        $subcategory = Subcategory::all();

        return view('/backend/asset_data/form_asset', compact('asset', 'company', 'category', 'subcategory'));
    }

    public function store(Request $request)
    {   
        // Mendapatkan ID kategori yang dipilih dari form
        $id_kategori = $request->val_category;
        $id_sub_kategori = $request->val_sub_category;

        // cek id sub kategori
        if ($id_sub_kategori){
            $id_sub_kategori = $id_sub_kategori;

        } else {
            $id_sub_kategori = null;
        }

        // Menggunakan model Kategori untuk mendapatkan nama kategori
        $kategori = Category::where('id_kategori', $id_kategori)->first();

        // Menentukan nilai spesifikasi berdasarkan kategori yang dipilih
        if ($kategori && $kategori->nama_kategori == 'Kendaraan') {
            $spesifikasi = '';
            $nopol = $request->val_nopol;
            $merk = $request->val_merk;
            $tahun = $request->val_tahun;
            $masa_pajak = $request->val_masa_pajak;
            $masa_plat = $request->val_masa_plat;

        } else {
            $nopol = '';
            $merk = '';
            $tahun = '';
            $masa_pajak = null;
            $masa_plat = null;
            $spesifikasi = $request->val_spesification;
        }
        
        DB::table('tbl_aset')->insert([
            'nama_aset'=> $request->val_name,
            'lokasi'=> $request->val_location,
            'spesifikasi'=> $spesifikasi,
            'nopol'=> $nopol,
            'merk'=> $merk,
            'tahun'=> $tahun,
            'masa_pajak'=> $masa_pajak,
            'masa_plat'=> $masa_plat,
            'id_perusahaan'=> $request->val_company,
            'id_kategori'=> $request->val_category,
            'id_sub_kategori'=> $id_sub_kategori,
        ]);

        return redirect('/list-asset');;
        
    }

    public function delete($id)
    {
        Asset::where('id_aset', $id)->delete();
        return redirect('/list-asset');
    }

    public function update(Request $request)
    {   
        $id_kategori = $request->val_category;
        $id_sub_kategori = $request->val_sub_category;

        // cek id sub kategori
        if ($id_sub_kategori){
            $id_sub_kategori = $id_sub_kategori;

        } else {
            $id_sub_kategori = null;
        }
        
        // Menggunakan model Kategori untuk mendapatkan nama kategori
        $kategori = Category::where('id_kategori', $id_kategori)->first();

        // Menentukan nilai spesifikasi berdasarkan kategori yang dipilih
        if ($kategori && $kategori->nama_kategori == 'Kendaraan') {
            $spesifikasi = '';
            $nopol = $request->val_nopol;
            $merk = $request->val_merk;
            $tahun = $request->val_tahun;
            $masa_pajak = $request->val_masa_pajak;
            $masa_plat = $request->val_masa_plat;

        } else {
            $nopol = '';
            $merk = '';
            $tahun = '';
            $masa_pajak = null;
            $masa_plat = null;
            $spesifikasi = $request->val_spesification;
        }
        
        DB::table('tbl_aset')->where('id_aset', $request->id)->update([
            'nama_aset'=> $request->val_name,
            'lokasi'=> $request->val_location,
            'spesifikasi'=> $spesifikasi,
            'nopol'=> $nopol,
            'merk'=> $merk,
            'tahun'=> $tahun,
            'masa_pajak'=> $masa_pajak,
            'masa_plat'=> $masa_plat,
            'id_perusahaan'=> $request->val_company,
            'id_kategori'=> $request->val_category,
            'id_sub_kategori'=> $id_sub_kategori,
        ]);

        return redirect('/list-asset');
    }

    public function edit($id)
    {
        // Dekripsi ID
        $id = Crypt::decrypt($id);

        // mengambil data asset berdasarkan ID
        $asset = Asset::where('id_aset', $id)->first();
        $company = Company::all();
        $category = Category::all();
        $subcategory = Subcategory::all();
        return view('/backend/asset_data/form_asset', compact('asset', 'company', 'category', 'subcategory'));
    }

    public function search(Request $request)
    {
        $search = $request->input('search');
        $asset = Asset::
            where('nama_aset', 'like', "%$search%")
            ->orWhere('nopol', 'like', "%$search%")
            ->get();

        $category = Category::pluck('nama_kategori', 'id_kategori');
        $subcategory = Subcategory::pluck('nama_sub_kategori', 'id_sub_kategori');
        $company = Company::pluck('nama_perusahaan', 'id_perusahaan');

        if ($asset->count() === 0) {
            $asset = Asset::all();
            return view('/backend/asset_data/list_asset', compact('asset', 'category', 'subcategory', 'company'));

        } else {
            return view('/backend/asset_data/list_asset', compact('asset', 'category', 'subcategory', 'company'));
        }
    }

    public function getSubCategories($categoryId)
    {
        // Ganti ini dengan logika untuk mengambil sub kategori berdasarkan kategori yang dipilih
        $subCategories = SubCategory::where('id_kategori', $categoryId)->get();
        return response()->json($subCategories);
    }
}
