<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\User;

class UsersTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        User::create([
            'name' => 'God',
            'email' => 'god@gmail.com',
            'level' => '0',
            'password' => bcrypt('12345'),
        ]);
    }
}
