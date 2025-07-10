<?php

namespace Database\Seeders;

use App\Models\ToolstringCategoryModel;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class ToolstringCategory extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $toolstring_categories = [
            ['name' => 'Fishing', 'slug' => 'fishing', 'created_by' => 1, 'updated_by' => 1],
            ['name' => 'Drilling', 'slug' => 'drilling', 'created_by' => 1, 'updated_by' => 1],
            ['name' => 'Completion', 'slug' => 'completion', 'created_by' => 1, 'updated_by' => 1],
            ['name' => 'Well Intervention', 'slug' => 'well-intervention', 'created_by' => 1, 'updated_by' => 1],
            // ['name' => 'Well Testing', 'slug' => 'well-testing', 'created_by' => 1, 'updated_by' => 1],
            // ['name' => 'Well Abandonment', 'slug' => 'well-abandonment', 'created_by' => 1, 'updated_by' => 1],
            // ['name' => 'All Items', 'slug' => 'all-items', 'created_by' => 1, 'updated_by' => 1],
            // ['name' => 'Miscellaneous', 'slug' => 'miscellaneous', 'created_by' => 1, 'updated_by' => 1],
            // ['name' => 'BHA', 'slug' => 'bha', 'created_by' => 1, 'updated_by' => 1],
            // ['name' => 'Casing', 'slug' => 'casing', 'created_by' => 1, 'updated_by' => 1],
            // ['name' => 'Tubing', 'slug' => 'tubing', 'created_by' => 1, 'updated_by' => 1],
            // ['name' => 'Drill Pipe', 'slug' => 'drill-pipe', 'created_by' => 1, 'updated_by' => 1],
            // ['name' => 'Drill Collar', 'slug' => 'drill-collar', 'created_by' => 1, 'updated_by' => 1],
            // ['name' => 'Stabilizer', 'slug' => 'stabilizer', 'created_by' => 1, 'updated_by' => 1],
            // ['name' => 'Subsea Equipment', 'slug' => 'subsea-equipment', 'created_by' => 1, 'updated_by' => 1],
            // ['name' => 'Surface Equipment', 'slug' => 'surface-equipment', 'created_by' => 1, 'updated_by' => 1],
        ];

        ToolstringCategoryModel::insert($toolstring_categories);
    }
}
