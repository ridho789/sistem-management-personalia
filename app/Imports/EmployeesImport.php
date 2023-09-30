<?php

namespace App\Imports;

use Illuminate\Support\Collection;
use Maatwebsite\Excel\Concerns\ToCollection;
use App\Models\Employee;
use Illuminate\Support\Facades\Log;
use Throwable;

class EmployeesImport implements ToCollection
{
    /**
    * @param Collection $collection
    */

    private $currentRow = 0; // Inisialisasi nomor baris
    private $logErrors = [];

    public function collection(Collection $collection)
    {
        foreach ($collection as $row){
            // date format
            $dateText =  $row[3];
            $date = date_create_from_format('Y-m-d', $dateText);
            $date_format = date_format($date, 'Y-m-d');

            $this->currentRow++;
            $uniqueValues = []; // Array untuk menyimpan nilai unik

            foreach ($row as $columnIndex => $value) {
                // Catat pesan kesalahan jika kolom kosong
                if (empty($value)) {
                    Log::error('Error importing data: Kolom ' . $columnIndex . ' kosong di baris ' . $this->currentRow);
                }                
            }

            // periksa kolom nik dan id card
            $key = $row[1]. '-'.$row[11];
            if (isset($uniqueValues[$key])){
                Log::error('Error importing data: Duplikasi berdasarkan NIK dan ID Card ditemukan di baris ' . $this->currentRow);
            } else {
                $uniqueValues[$key] = true;
            }

            // periksa kolom tanggal harus berupa teks
            if (!is_string($row[3])) {
                // Catat pesan kesalahan jika nilai bukan teks
                Log::error('Error importing data: Nilai kolom tanggal bukan teks di baris ' . $this->currentRow);
            }

            Employee::create([
                'nama_karyawan' => $row[0],
                'nik' => $row[1],
                'tempat_lahir' => $row[2],
                'tanggal_lahir' => $date_format,
                'jenis_kelamin' => $row[4],
                'no_telp' => $row[5],
                'alamat' => $row[6],
                'id_jabatan' => $row[7],
                'id_divisi' => $row[8],
                'id_perusahaan' => $row[9],
                'id_status' => $row[10],
                'id_card' => $row[11],
                'foto' => '',
            ]);
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
