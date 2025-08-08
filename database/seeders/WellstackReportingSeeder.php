<?php

namespace Database\Seeders;

use App\Models\User;
use App\Models\WellstackReportingHistoryModel;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class WellstackReportingSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $admin = User::where('is_admin', true)->first();
        for ($i = 1; $i <= 35; $i++) {
            WellstackReportingHistoryModel::create([
                'name' => 'Wellstack Reporting History ' . $i,
                'client' => 'Client ' . $i,
                'field' => 'Field ' . $i,
                'well_name_number' => 'Well ' . $i,
                'min_restriction' => rand(1, 100),
                'kop' => rand(1000, 5000),
                'category' => 'Category ' . $i,
                'bhp' => rand(1000, 5000),
                'bhst' => rand(1000, 5000),
                'so' => rand(1000, 5000),
                'supplier' => 'Supplier ' . $i,
                'date_drawn' => now()->subDays(rand(1, 30)),
                'drawn_by' => 'User ' . $i,
                'created_by' => $admin->id,
                'updated_by' => $admin->id,
            ]);
        }
    }
}
