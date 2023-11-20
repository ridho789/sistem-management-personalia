<?php

namespace App\Imports;

use Illuminate\Support\Collection;
use Maatwebsite\Excel\Concerns\ToCollection;
use App\Models\Employee;
use App\Models\DailyReport;
use Illuminate\Support\Facades\Log;
use Throwable;

class DailyReportImport implements ToCollection
{
    /**
    * @param Collection $collection
    */

    private $logErrors = [];

    public function collection(Collection $collection)
    {
        $rowNumber = 0;
        foreach ($collection as $row){
            // baris pertama tidak diproses karena header
            if ($rowNumber <= 1) {
                $rowNumber++;
                continue;
            }
            
            // date format
            $dateText =  $row[1];
            $date = date_create_from_format('Y-m-d', $dateText);
            $date_format = date_format($date, 'Y-m-d');
            
            $employee = Employee::where('id_card', $row[0])->first();

            if ($employee) {
                $dailyReportCheck = DailyReport::where('tanggal_catatan_harian', $date_format)
                ->where('id_karyawan', $employee->id_karyawan)
                ->get();
                
                if (count($dailyReportCheck) == 0) {
                    DailyReport::insert([
                        'id_karyawan' => $employee->id_karyawan,
                        'tanggal_catatan_harian' => $date_format,
                        'keterangan' => $row[2],
                        'dibuat_oleh' => 'Import - ' . $row[3]
                    ]);

                } else {
                    $errorRowNumber = $rowNumber + 1;
                    $errorMessage = 'Error importing data: there is already data made for the employee ' . 
                        $employee->nama_karyawan . ' - ' . $employee->id_card . ' date: ' . date("j F Y", strtotime($date_format)) . 
                        ' at row: ' . $errorRowNumber;

                    // Tambahkan pesan kesalahan ke dalam array logErrors
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
