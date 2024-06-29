<?php

namespace App\Imports;

use Illuminate\Support\Collection;
use Maatwebsite\Excel\Concerns\ToCollection;
use App\Models\Employee;
use App\Models\Company;
use App\Models\Position;
use App\Models\Divisi;
use App\Models\StatusEmployee;
use PhpOffice\PhpSpreadsheet\Shared\Date as ExcelDate;

class EmployeesImport implements ToCollection
{
    private $logErrors = [];

    public function collection(Collection $collection)
    {
        $rowNumber = 0;
        $currentRow = 0;
        $uniqueValues = []; // Array untuk menyimpan nilai unik

        foreach ($collection as $row) {
            $currentRow++;

            // Baris pertama tidak diproses karena header
            if ($rowNumber === 0) {
                $columnName = $row->toArray();
                $rowNumber++;
                continue;
            }

            // Position
            $idPosition = null;
            if ($row[8] && $row[8] != '-') {
                
                $position = Position::where('nama_jabatan', 'like', '%' . $row[8] . '%')->first();
                if (!$position) {
                    $position = Position::create(['nama_jabatan' => $row[8]]);
                }
                
                // Id position
                $idPosition = $position->id_jabatan;
            }

            // Divisi
            $idDivision = null;
            if ($row[9] && $row[9] != '-') {
                $division = Divisi::where('nama_divisi', 'like', '%' . $row[9] . '%')->first();
                if (!$division) {
                    $division = Divisi::create(['nama_divisi' => $row[9]]);
                }

                // Id division
                $idDivision = $division->id_divisi;
            }

            //  Company
            $idCompany = null;
            if ($row[10] && $row[10] != '-') {
                $company = Company::where('nama_perusahaan', 'like', '%' . $row[10] . '%')->first();
                if (!$company) {
                    $company = Company::create(['nama_perusahaan' => $row[10]]);
                }

                // Id company
                $idCompany = $company->id_perusahaan;
            }

            // Status
            $idStatus = null;
            if ($row[11] && $row[11] != '-') {
                $status = StatusEmployee::where('nama_status', 'like', '%' . $row[11] . '%')->first();
                if (!$status) {
                    $status = StatusEmployee::create(['nama_status' => $row[11]]);
                }

                // Id status
                $idStatus = $status->id_status;
            }

            // Gaji Pokok
            $basic_salary = $row[12];
            $numericAmountBasicSalary = ($basic_salary && $basic_salary != '-') ? preg_replace("/[^0-9]/", "", explode(",", $basic_salary)[0]) : 0;
            $basic_salary_idr = "Rp " . number_format($numericAmountBasicSalary, 0, ',', '.');

            foreach ($row as $columnIndex => $value) {
                $columnNameHeader = $columnName[$columnIndex] ?? "unknown";

                if ($idStatus) {
                    if (strtolower($status->nama_status) != 'kontrak' || strtolower($status->nama_status) != 'contract') {
                        continue;
                    }
                }

                // Catat pesan kesalahan jika kolom kosong
                if (empty($value)) {
                    $errorMessage = 'Error importing data: Kolom ' . $columnNameHeader . ' kosong di baris ' . $currentRow;
                    $this->logErrors[] = $errorMessage;
                }
            }

            // generate id card
            $idcard = $this->generateIdCard($row);

            // cek jumlah digit ID card
            $total_digit = strlen(strval($idcard));
            if ($total_digit != 7) {
                $errorMessage = 'Error importing data: Jumlah digit ID card harus berjumlah 7 di baris ' . $currentRow;
                $this->logErrors[] = $errorMessage;
            }

            // periksa kolom nik dan start joining
            $key = $row[1] . '-' . $row[20];
            if (isset($uniqueValues[$key])) {
                $errorMessage = 'Error importing data: Duplikasi berdasarkan NIK ditemukan di baris ' . $currentRow;
                $this->logErrors[] = $errorMessage;
            } else {
                $uniqueValues[$key] = true;
            }

            $employeeData = [
                'nama_karyawan' => $row[0],
                'nik' => $row[1],
                'tempat_lahir' => $row[2],
                'tanggal_lahir' => $this->getValidDate($row[3], $currentRow),
                'jenis_kelamin' => ($row[4] && $row[4] != '-') ? $row[4] : null,
                'no_telp' => $row[5],
                'lokasi' => $row[6],
                'alamat' => $row[7],
                'id_jabatan' => $idPosition,
                'id_divisi' => $idDivision,
                'id_perusahaan' => $idCompany,
                'id_status' => $idStatus,
                'awal_bergabung' => $this->getValidDate($row[16], $currentRow),
                'gaji_pokok' => $basic_salary_idr,
                'kontak_darurat' => $row[17],
                'bpjs_tk' => $row[18],
                'bpjs_kis' => $row[19],
                'id_card' => $idcard,
            ];

            if ($idStatus && in_array(strtolower($status->nama_status), ['kontrak', 'contract'])) {
                $employeeData['lama_kontrak'] = $row[13];
                $employeeData['awal_masa_kontrak'] = $this->getValidDate($row[14], $currentRow);
                $employeeData['akhir_masa_kontrak'] = $this->getValidDate($row[15], $currentRow);
            }

            if (empty($this->logErrors)) {
                Employee::create($employeeData);
            }

            $rowNumber++;
        }
    }

    private function getValidDate($dateValue, $currentRow)
    {
        if ($dateValue && $dateValue != '-') {
            try {
                $dateTime = new \DateTime('@' . $dateValue);
                $dateTime = \PhpOffice\PhpSpreadsheet\Shared\Date::excelToDateTimeObject($dateValue);
                if ($dateTime) {
                    return $dateTime;

                } else {
                    $errorMessage = 'Error importing data: format tanggal tidak valid ' . $dateValue . ' di baris ' . $currentRow;
                    $this->logErrors[] = $errorMessage;
                }

            } catch (\Exception $e) {
                $errorMessage = 'Error importing data: format tanggal tidak valid ' . $dateValue . ' di baris ' . $currentRow;
                $this->logErrors[] = $errorMessage;
                return null;
            }
        }
        return null;
    }

    private function generateIdCard($row)
    {
        $randomDigits = rand(1, 999);
        $formattedRandomDigits = str_pad($randomDigits, 3, '0', STR_PAD_LEFT);
        if ($row[20] && $row[20] != '-') {
            return $row[20];
        } else {
            if (($row[3] && $row[3] != '-') && ($row[16] && $row[16] != '-')) {
                return strval(date_format(ExcelDate::excelToDateTimeObject($row[3]), 'y')) .
                    strval(date_format(ExcelDate::excelToDateTimeObject($row[16]), 'y')) . strval($formattedRandomDigits);
            }
        }
        return null;
    }

    public function getLogErrors()
    {
        return $this->logErrors;
    }
}
