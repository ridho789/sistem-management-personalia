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
use Maatwebsite\Excel\Row;

class EmployeesImport implements ToCollection
{
    /**
    * @param Collection $collection
    */

    private $logErrors = [];

    public function collection(Collection $collection)
    {
        $rowNumber = 0;
        $currentRow = 0;

        foreach ($collection as $row){
            $currentRow++;

            // baris pertama tidak diproses karena header
            if ($rowNumber === 0) {
                $columnName = $row->toArray();
                $rowNumber++;
                continue;
            }

            $uniqueValues = []; // Array untuk menyimpan nilai unik

            $namePosition = $row[8];
            $nameDivision = $row[9];
            $nameCompany = $row[10];
            $nameStatus = $row[11];

            // Gaji Pokok
            $basic_salary = $row[12];
            $numericAmountBasicSalary = preg_replace("/[^0-9]/", "", explode(",", $basic_salary)[0]);
            $basic_salary_idr = "Rp " . number_format($numericAmountBasicSalary, 0, ',', '.');

            $position = Position::where('nama_jabatan', $namePosition)->first();
            $division = Divisi::where('nama_divisi', $nameDivision)->first();
            $company = Company::where('nama_perusahaan', $nameCompany)->first();
            $status = StatusEmployee::where('nama_status', $nameStatus)->first();

            foreach ($row as $columnIndex => $value) {
                $columnNameHeader = $columnName[$columnIndex] ?? "unknown";

                // Cek jika status bukan kontrak
                if (strtolower($status) != 'kontrak' || strtolower($status) != 'contract') {
                    continue;
                }

                // Catat pesan kesalahan jika kolom kosong
                if (empty($value)) {
                    $errorMessage = 'Error importing data: Kolom ' . $columnNameHeader . ' kosong di baris ' . $currentRow;
                    $this->logErrors[] = $errorMessage;
                }                
            }

            
            // generate id card
            $randomDigits = rand(1, 999);
            $formattedRandomDigits = str_pad($randomDigits, 3, '0', STR_PAD_LEFT);
            $idcard = strval(date_format(\PhpOffice\PhpSpreadsheet\Shared\Date::excelToDateTimeObject($row[3]), 'y')) . strval(date_format(\PhpOffice\PhpSpreadsheet\Shared\Date::excelToDateTimeObject($row[16]), 'y')) . strval($formattedRandomDigits);
            
            // cek jumlah digit ID card
            $total_digit = strlen(strval($idcard));

            if ($total_digit != 7) {
                $errorMessage = 'Error importing data: Jumlah digit ID card harus berjumlah 7 di baris ' . $currentRow;
                    $this->logErrors[] = $errorMessage;
            }

            // periksa kolom nik dan start joining
            $key = $row[1]. '-'.$row[16];
            if (isset($uniqueValues[$key])){
                $errorMessage = 'Error importing data: Duplikasi berdasarkan NIK ditemukan di baris ' . $currentRow;
                $this->logErrors[] = $errorMessage;

            } else {
                $uniqueValues[$key] = true;
            }

            if ($position && $division && $company && $status) {
                $employeeData = [
                    'nama_karyawan' => $row[0],
                    'nik' => $row[1],
                    'tempat_lahir' => $row[2],
                    'tanggal_lahir' => \PhpOffice\PhpSpreadsheet\Shared\Date::excelToDateTimeObject($row[3]),
                    'jenis_kelamin' => $row[4],
                    'no_telp' => $row[5],
                    'lokasi' => $row[6],
                    'alamat' => $row[7],
                    'id_jabatan' => $position->id_jabatan,
                    'id_divisi' => $division->id_divisi,
                    'id_perusahaan' => $company->id_perusahaan,
                    'id_status' => $status->id_status,
                    'awal_bergabung' => \PhpOffice\PhpSpreadsheet\Shared\Date::excelToDateTimeObject($row[16]),
                    'gaji_pokok' => $basic_salary_idr,
                    'id_card' => $idcard,
                ];
            
                if (strtolower($status->nama_status) == 'kontrak') {
                    $employeeData['lama_kontrak'] = $row[13];
                    $employeeData['awal_masa_kontrak'] = \PhpOffice\PhpSpreadsheet\Shared\Date::excelToDateTimeObject($row[14]);
                    $employeeData['akhir_masa_kontrak'] = \PhpOffice\PhpSpreadsheet\Shared\Date::excelToDateTimeObject($row[15]);

                }

                if (empty($this->logErrors)) {
                    Employee::create($employeeData);
                }

            } else {
                if (empty($position)){
                    $errorMessage = 'Error importing data: Nilai kolom Position/Jabatan tidak valid di baris ' . $currentRow;
                    $this->logErrors[] = $errorMessage;
                }

                if (empty($division)){
                    $errorMessage = 'Error importing data: Nilai kolom Division/Divisi tidak valid di baris ' . $currentRow;
                    $this->logErrors[] = $errorMessage;
                }

                if (empty($company)){
                    $errorMessage = 'Error importing data: Nilai kolom Company/Perusahaan tidak valid di baris ' . $currentRow;
                    $this->logErrors[] = $errorMessage;
                }

                if (empty($status)){
                    $errorMessage = 'Error importing data: Nilai kolom Status tidak valid di baris ' . $currentRow;
                    $this->logErrors[] = $errorMessage;
                }
            } 
            $rowNumber++;
        }
    }

    public function getLogErrors()
    {
        return $this->logErrors;
    }
}
