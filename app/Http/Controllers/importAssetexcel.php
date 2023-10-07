<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Maatwebsite\Excel\Facades\Excel;
use App\Imports\AssetImport;
use App\Models\Category;
use App\Models\Subcategory;
use App\Models\Company;
use App\Models\Asset;

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
            try {
                Excel::import($import, $file);
                return redirect('/list-asset');
                
            } catch (\Exception $e) {
                $logErrors = $import->getLogErrors();
                $sqlErrors = $e->getMessage();

                if (!empty($sqlErrors)){
                    $logErrors = $sqlErrors;
                }

                $asset = '';
                $company = Company::all();
                $category = Category::all();
                $subcategory = Subcategory::all();

                return view('/backend/asset_data/form_asset', compact('asset', 'company', 'category', 'subcategory', 'logErrors'));
            }
        }
    }
}
