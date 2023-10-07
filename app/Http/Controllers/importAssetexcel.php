<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Maatwebsite\Excel\Facades\Excel;
use App\Imports\AssetImport;

class importAssetexcel extends Controller
{
    public function importExcel(Request $request)
    {   
        $request->validate([
            'file' => 'required|mimes:xlsx|max:2048',
        ]);

        $file = $request->file('file');
        if ($file){
            $import = new AssetImport;
            Excel::import($import, $file);
            return redirect('/list-asset');
        }
    }
}
