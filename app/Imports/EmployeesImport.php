<?php

namespace App\Imports;

use Illuminate\Support\Collection;
use Maatwebsite\Excel\Concerns\ToCollection;
use App\Models\Employee;
use App\Models\Company;
use App\Models\Position;
use App\Models\Divisi;
use App\Models\StatusEmployee;

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

            // Baris pertama tidak diproses karena header
            if ($rowNumber === 0) {
                $columnName = $row->toArray();
                $rowNumber++;
                continue;
            }

            $uniqueValues = []; // Array untuk menyimpan nilai unik

            // Position
            if ($row[8]) {
                
                $position = Position::where('nama_jabatan', 'like', '%' . $row[8] . '%')->first();
                if (!$position) {
                    $position = Position::create(['nama_jabatan' => $row[8]]);
                }
                
                // Id position
                $idPosition = $position->id_jabatan;
            }

            // Divisi
            if ($row[9]) {
                $division = Divisi::where('nama_divisi', 'like', '%' . $row[9] . '%')->first();
                if (!$division) {
                    $division = Divisi::create(['nama_divisi' => $row[9]]);
                }

                // Id division
                $idDivision = $division->id_divisi;
            }

            //  Company
            if ($row[10]) {
                $company = Company::where('nama_perusahaan', 'like', '%' . $row[10] . '%')->first();
                if (!$company) {
                    $company = Company::create(['nama_perusahaan' => $row[10]]);
                }

                // Id company
                $idCompany = $company->id_perusahaan;
            }
            
            // Status
            if ($row[11]) {
                $status = StatusEmployee::where('nama_status', 'like', '%' . $row[11] . '%')->first();
                if (!$status) {
                    $status = StatusEmployee::create(['nama_status' => $row[11]]);
                }

                // Id status
                $idStatus = $status->id_status;
            }

            // Gaji Pokok
            $basic_salary = $row[12];
            $numericAmountBasicSalary = preg_replace("/[^0-9]/", "", explode(",", $basic_salary)[0]);
            $basic_salary_idr = "Rp " . number_format($numericAmountBasicSalary, 0, ',', '.');

            foreach ($row as $columnIndex => $value) {
                $columnNameHeader = $columnName[$columnIndex] ?? "unknown";

                // Cek jika status bukan kontrak
                if (strtolower($status->nama_status) != 'kontrak' || strtolower($status->nama_status) != 'contract') {
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
            $idcard = strval(date_format(\PhpOffice\PhpSpreadsheet\Shared\Date::excelToDateTimeObject($row[3]), 'y')) . 
            strval(date_format(\PhpOffice\PhpSpreadsheet\Shared\Date::excelToDateTimeObject($row[16]), 'y')) . strval($formattedRandomDigits);
            
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

            $employeeData = [
                'nama_karyawan' => $row[0],
                'nik' => $row[1],
                'tempat_lahir' => $row[2],
                'tanggal_lahir' => \PhpOffice\PhpSpreadsheet\Shared\Date::excelToDateTimeObject($row[3]),
                'jenis_kelamin' => $row[4],
                'no_telp' => $row[5],
                'lokasi' => $row[6],
                'alamat' => $row[7],
                'id_jabatan' => $idPosition,
                'id_divisi' => $idDivision,
                'id_perusahaan' => $idCompany,
                'id_status' => $idStatus,
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

            $rowNumber++;
        }
    }

    public function getLogErrors()
    {
        return $this->logErrors;
    }
}
