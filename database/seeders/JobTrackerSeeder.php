<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class JobTrackerSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // create jobtracker form
        $data = [
            'bh_pressure' => 0,
            'bh_pressure_unit' => 'psi',
            'bh_temp' => 0,
            'bh_temp_unit' => '°F',
            'bj_district' => '',
            'bj_representative' => '',
            'bop' => '',
        ];
    }
}
