<?php

namespace Database\Seeders;

use App\Models\ToolstringCategoryModel;
use App\Models\ToolstringTypeModel;
use App\Models\WellstackTypeModel;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class WellstackType extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $wellstack_types = [
            ['name' => 'Injector Heads', 'slug' => 'injector-heads', 'created_by' => 1, 'updated_by' => 1],
            ['name' => 'Strippers', 'slug' => 'strippers', 'created_by' => 1, 'updated_by' => 1],
            ['name' => 'BOPS', 'slug' => 'bops', 'created_by' => 1, 'updated_by' => 1],
            ['name' => 'Risers', 'slug' => 'risers', 'created_by' => 1, 'updated_by' => 1],
        ];

        WellstackTypeModel::insert($wellstack_types);
    }
}
