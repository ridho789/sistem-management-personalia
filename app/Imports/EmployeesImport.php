<?php

namespace App\Imports;

use Illuminate\Support\Collection;
use Maatwebsite\Excel\Concerns\ToCollection;
use App\Models\Employee;
use App\Models\Company;
use App\Models\Position;
use App\Models\Divisi;
use App\Models\StatusEmployee;
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
        $rowNumber = 0;
        foreach ($collection as $row){
            // baris pertama tidak diproses karena header
            if ($rowNumber === 0) {
                $rowNumber++;
                continue;
            }

            // date format
            $dateText =  $row[3];
            $date = date_create_from_format('Y-m-d', $dateText);
            $date_format = date_format($date, 'Y-m-d');

            // periksa kolom tanggal harus berupa teks
            if (!is_string($row[3])) {
                // Catat pesan kesalahan jika nilai bukan teks
                Log::error('Error importing data: Nilai kolom tanggal bukan teks di baris ' . $this->currentRow);
            }

            // date format (awal kontrak)
            $dateTextStartContract =  $row[13];
            if ($dateTextStartContract) {
                $dateStartContract = date_create_from_format('Y-m-d', $dateTextStartContract);
                if ($dateStartContract !== false) {
                    $dateFormatStartContract = date_format($dateStartContract, 'Y-m-d');
                }
            }

            // date format (selesai kontrak)
            $dateTextEndContract =  $row[14];
            if ($dateTextEndContract) {
                $dateEndContract = date_create_from_format('Y-m-d', $dateTextEndContract);
                if ($dateEndContract !== false) {
                    $dateFormatEndContract = date_format($dateEndContract, 'Y-m-d');
                }
            }

            // date format (awal bergabung)
            $dateTextStartJoining =  $row[15];
            if ($dateTextStartJoining) {
                $dateStartJoining = date_create_from_format('Y-m-d', $dateTextStartJoining);
                if ($dateStartJoining !== false) {
                    $dateFormatStartJoining = date_format($dateStartJoining, 'Y-m-d');
                }
            }

            $this->currentRow++;
            $uniqueValues = []; // Array untuk menyimpan nilai unik

            $namePosition = $row[7];
            $nameDivision = $row[8];
            $nameCompany = $row[9];
            $nameStatus = $row[10];

            // Gaji Pokok
            $basic_salary = $row[11];
            $numericAmountBasicSalary = preg_replace("/[^0-9]/", "", explode(",", $basic_salary)[0]);
            $basic_salary_idr = "Rp " . number_format($numericAmountBasicSalary, 0, ',', '.');

            $position = Position::where('nama_jabatan', $namePosition)->first();
            $division = Divisi::where('nama_divisi', $nameDivision)->first();
            $company = Company::where('nama_perusahaan', $nameCompany)->first();
            $status = StatusEmployee::where('nama_status', $nameStatus)->first();

            foreach ($row as $columnIndex => $value) {
                // Catat pesan kesalahan jika kolom kosong
                if (empty($value)) {
                    Log::error('Error importing data: Kolom ' . $columnIndex . ' kosong di baris ' . $this->currentRow);
                }                
            }

            // periksa kolom nik dan id card
            $key = $row[1]. '-'.$row[16];
            if (isset($uniqueValues[$key])){
                Log::error('Error importing data: Duplikasi berdasarkan NIK dan ID Card ditemukan di baris ' . $this->currentRow);
            } else {
                $uniqueValues[$key] = true;
            }

            if ($position && $division && $company && $status) {
                $employeeData = [
                    'nama_karyawan' => $row[0],
                    'nik' => $row[1],
                    'tempat_lahir' => $row[2],
                    'tanggal_lahir' => $date_format,
                    'jenis_kelamin' => $row[4],
                    'no_telp' => $row[5],
                    'alamat' => $row[6],
                    'id_jabatan' => $position->id_jabatan,
                    'id_divisi' => $division->id_divisi,
                    'id_perusahaan' => $company->id_perusahaan,
                    'id_status' => $status->id_status,
                    'awal_bergabung' => $dateFormatStartJoining,
                    'gaji_pokok' => $basic_salary_idr,
                    'id_card' => $row[16],
                ];
            
                if (strtolower($status->nama_status) == 'kontrak') {
                    $employeeData['lama_kontrak'] = $row[12];
                    $employeeData['awal_masa_kontrak'] = $dateFormatStartContract;
                    $employeeData['akhir_masa_kontrak'] = $dateFormatEndContract;

                }

                Employee::create($employeeData);

            } else {
                if (empty($position)){
                    Log::error('Error importing data: Nilai kolom Position/Jabatan tidak valid di baris ' . $this->currentRow);
                }

                if (empty($division)){
                    Log::error('Error importing data: Nilai kolom Division/Divisi tidak valid di baris ' . $this->currentRow);
                }

                if (empty($company)){
                    Log::error('Error importing data: Nilai kolom Company/Perusahaan tidak valid di baris ' . $this->currentRow);
                }

                if (empty($status)){
                    Log::error('Error importing data: Nilai kolom Status tidak valid di baris ' . $this->currentRow);
                }
            } $rowNumber++;
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
