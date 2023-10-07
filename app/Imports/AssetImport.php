<?php

namespace App\Imports;

use Illuminate\Support\Collection;
use Maatwebsite\Excel\Concerns\ToCollection;
use App\Models\Asset;
use App\Models\Category;
use App\Models\Subcategory;
use App\Models\Company;
use Illuminate\Support\Facades\Log;
use Throwable;

class AssetImport implements ToCollection
{
    /**
    * @param Collection $collection
    */

    private $currentRow = 0; // Inisialisasi nomor baris
    private $logErrors = [];

    public function collection(Collection $collection)
    {   
        $rowNumber = 0;
        $this->currentRow++;
        foreach ($collection as $row){
            // baris pertama tidak diproses karena header
            if ($rowNumber === 0) {
                $rowNumber++;
                continue;
            }
            
            $namaKategori = $row[1];
            $namaSubKategori = $row[2];
            $namaPerusahaan = $row[10];
            $dateTextTax =  $row[7];
            $dateTextPlate =  $row[8];

            $date_format_tax = null;
            $date_format_plate = null;
            
            if ($dateTextTax && $dateTextPlate){
                $dateTax = date_create_from_format('Y-m-d', $dateTextTax);
                $datePlate = date_create_from_format('Y-m-d', $dateTextPlate);
                
                $date_format_tax = date_format($dateTax, 'Y-m-d');
                $date_format_plate = date_format($datePlate, 'Y-m-d');
            }

            $kategori = Category::where('nama_kategori', $namaKategori)->first();
            $subKategori = Subcategory::where('nama_sub_kategori', $namaSubKategori)->first();
            $perusahaan = Company::where('nama_perusahaan', $namaPerusahaan)->first();

            // optional
            if ($namaSubKategori){
                $optionalSubKategori = $subKategori->id_sub_kategori;
            } else {
                $optionalSubKategori = null;
            }
            
            if ($kategori && $perusahaan){
                Asset::create([
                    'nama_aset'=> $row[0],
                    'id_kategori'=> $kategori->id_kategori,
                    'id_sub_kategori'=> $optionalSubKategori,
                    'spesifikasi'=> $row[3],
                    'nopol'=> $row[4],
                    'merk'=> $row[5],
                    'tahun'=> $row[6],
                    'masa_pajak'=> $date_format_tax,
                    'masa_plat'=> $date_format_plate,
                    'lokasi'=> $row[9],
                    'id_perusahaan'=> $perusahaan->id_perusahaan
                ]);
                
            } else {
                if (empty($kategori)){
                    Log::error('Error importing data: Nilai kolom Category/Kategori tidak valid di baris ' . $this->currentRow);
                }

                if (empty($perusahaan)){
                    Log::error('Error importing data: Nilai kolom Company/Perusahaan tidak valid di baris ' . $this->currentRow);
                }
            }

            $rowNumber++;
        }
    }

    public function onError(Throwable $e)
    {
        // Catat pesan kesalahan ke log
        $errorMessage = 'Error importing data: ' . $e->getMessage();
        Log::error($errorMessage);

        // Tambahkan pesan kesalahan ke dalam array logErrors
        $this->logErrors[] = $errorMessage;
    }

    public function getLogErrors()
    {
        return $this->logErrors;
    }
}
