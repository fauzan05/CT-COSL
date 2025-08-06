<?php

namespace Database\Seeders;

use App\Models\DocumentModel;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class CoiledTubingSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        for( $i = 0; $i < 45; $i++) {
            DocumentModel::create([
                'name' => 'Nitrogen - ' . ($i + 1),
                'description' => 'Nitrogen is a chemical element with the symbol N and atomic number 7. It is a colorless, odorless, tasteless, nonmetallic diatomic gas at standard conditions, constituting 78.1% of Earth\'s atmosphere by volume.',
                'menu' => 'coiled_tubing',
            ]);
        }
    }
}
