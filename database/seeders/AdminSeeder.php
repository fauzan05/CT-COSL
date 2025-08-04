<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;

class AdminSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        User::insert([
            'id' => (string) Str::uuid(),
            'fullname' => 'Banu Kristyanto',
            'username' => 'banu.kristyanto',
            'email' => 'admin@email.com',
            'password' => Hash::make('Rahasia123#'),
            'is_admin' => true,
            'download_access' => true,
            'profile_image' => '',
        ]);
    }
}
