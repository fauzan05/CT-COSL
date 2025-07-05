<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;

class AdminSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        DB::table('users')->insert([
            'fullname' => 'John Doe',
            'username' => 'admin123',
            'email' => 'admin@email.com',
            'password' => Hash::make('Rahasia123#'),
            'is_admin' => true,
            'download_access' => true,
            'profile_image' => '',
        ]);
    }
}
