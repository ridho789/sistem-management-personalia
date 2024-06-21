<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\TypeLeave;
use Carbon\Carbon;

class TypeLeaveTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $typeLeaves = [
            ['nama_tipe_cuti' => 'Legal Leave ' . Carbon::now()->year],
            ['nama_tipe_cuti' => 'Sick Leave'],
            ['nama_tipe_cuti' => 'Other'],
        ];

        foreach ($typeLeaves as $type) {
            TypeLeave::create($type);
        }
    }
}
