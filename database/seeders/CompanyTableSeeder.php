<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Company;

class CompanyTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $Company = [
            ['nama_perusahaan' => 'PT. SU'],
            ['nama_perusahaan' => 'PT. SNM'],
            ['nama_perusahaan' => 'PT. ATM'],
            ['nama_perusahaan' => 'PT. KPN'],
            ['nama_perusahaan' => 'PT. BMM'],
            ['nama_perusahaan' => 'PT. BEVI'],
        ];

        foreach ($Company as $c) {
            Company::create($c);
        }
    }
}
