<?php

namespace Database\Seeders;

use App\Models\JobTracker\JobDescriptionModel;
use Carbon\Carbon;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class JobDescriptionSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $now = Carbon::now();

        $descriptions = [
            [
                'description' => 'Prepare daily activity reports',
                'created_by' => 1,
                'updated_by' => 1,
                'created_at' => $now,
                'updated_at' => $now,
            ],
            [
                'description' => 'Inspect equipment before job execution',
                'created_by' => 1,
                'updated_by' => 1,
                'created_at' => $now,
                'updated_at' => $now,
            ],
            [
                'description' => 'Coordinate with field supervisor',
                'created_by' => 2,
                'updated_by' => 2,
                'created_at' => $now,
                'updated_at' => $now,
            ],
        ];

        foreach ($descriptions as $desc) {
            JobDescriptionModel::create($desc);
        }
    }
}
