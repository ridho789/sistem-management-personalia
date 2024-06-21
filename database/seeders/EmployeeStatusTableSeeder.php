<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\StatusEmployee;

class EmployeeStatusTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $employeeStatus = [
            ['nama_status' => 'Tetap'],
            ['nama_status' => 'Kontrak'],
            ['nama_status' => 'Harian'],
        ];

        foreach ($employeeStatus as $status) {
            StatusEmployee::create($status);
        }
    }
}
