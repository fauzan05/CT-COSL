<?php

namespace Database\Seeders;

use App\Models\ToolstringReportingHistoryModel;
use App\Models\User;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class ToolstringCoiledTubingPanelSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // get current admin
        $admin = User::where('is_admin', true)->first();
        for ($i = 1; $i <= 35; $i++) {
            ToolstringReportingHistoryModel::create([
                'name' => "Toolstring Coiled Tubing Panel $i",
                'title' => "Toolstring Coiled Tubing Panel Title $i",
                'client' => "Client $i",
                'well' => "Well $i",
                'date' => now(),
                'created_by' => $admin->id,
                'updated_by' => $admin->id, // Assuming user with ID 1 exists
            ]);
        }
    }
}
