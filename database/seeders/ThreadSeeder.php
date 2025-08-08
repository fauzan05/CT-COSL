<?php

namespace Database\Seeders;

use App\Models\ThreadModel;
use App\Models\User;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class ThreadSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $admin = User::where('is_admin', 1)->first();
        for ($threadId = 1; $threadId <= 1500; $threadId++) {
            ThreadModel::create([
                'id' => $threadId,
                'type' => 'Thread Type ' . $threadId,
                'created_by' => $admin->id,
                'updated_by' => $admin->id,
            ]);

            // Optionally, you can create sizes for each thread
            for ($sizeId = 1; $sizeId <= 3; $sizeId++) {
                ThreadModel::find($threadId)->sizes()->create([
                    'top_connection' => 'Top Connection ' . $sizeId,
                    'bottom_connection' => 'Bottom Connection ' . $sizeId,
                    'created_by' => $admin->id,
                    'updated_by' => $admin->id,
                ]);
            }
        }
    }
}
