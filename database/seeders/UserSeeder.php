<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class UserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $admin = User::where('is_admin', true)->first();
        for ($index = 1; $index <= 40; $index++) {
            User::create([
                'fullname' => 'User ' . $index,
                'username' => 'user' . $index,
                'email' => 'user' . $index . '@example.com',
                'password' => bcrypt('password' . $index),
                'is_admin' => false, // Every 5th user is an admin
                'download_access' => 1,
                'modification_job_tracker_master_access' => 1,
                'profile_image' => 'https://example.com/images/user' . $index . '.jpg',
                'created_at' => now(),
                'updated_at' => now(),
                'created_by' => $admin->id, 
                'updated_by' => $admin->id,
            ]);
        }
    }
}
