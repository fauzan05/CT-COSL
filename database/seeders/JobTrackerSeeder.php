<?php

namespace Database\Seeders;

use App\Models\JobTracker\JobTrackerModel;
use App\Models\User;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Str;

class JobTrackerSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // create jobtracker form
        $admin = User::where('is_admin', 1)->first();
        for ($index = 1; $index <= 1200; $index++) {
            JobTrackerModel::create([
                'id' => Str::uuid(),
                'bh_pressure' => 1000,
                'bh_pressure_unit' => 'psi',
                'bh_temp' => 80,
                'bh_temp_unit' => '°F',
                'cosl_base' => 'COSL Base',
                'cosl_ocd_representative' => 'John Doe',
                'bop' => 'BOP Type',
                'casing_liner_size' => 9.625,
                'casing_liner_size_unit' => 'in',
                'cement_volume' => 1000,
                'cement_volume_unit' => 'bbl',
                'company_man' => 'Company Man 1',
                'completion_size' => '7',
                'completion_size_unit' => 'in',
                'control_cabin' => 'Control Cabin 1',
                'created_by' => $admin->id,
                'ct_grade' => 'CT Grade 1',
                'ct_size' => 2.875,
                'ct_size_unit' => 'in',
                'ct_string' => 'CT String 1',
                'ct_supervisor' => 'CT Supervisor 1',
                'cj_injector' => 'Injector Type',
                'customer' => 'Customer Name ' . $index,
                'depth_md' => 5000,
                'depth_md_unit' => 'ft',
                'depth_tvd' => 4800,
                'depth_tvd_unit' => 'ft',
                'field_location' => 'Field Location ' . $index,
                'field_type' => 'Field Type ' . $index,
                'job_days' => 5,
                'job_finish_date' => now()->addDays(5)->format('Y-m-d'),
                'job_start_date' => now()->format('Y-m-d'),
                'max_bha_od' => 10,
                'max_bha_od_unit' => 'in',
                'max_deviation' => 5,
                'material_charges' => 5000,
                'mobilization_charges' => 2000,
                'n2_converter' => 'Converter Type',
                'nitrogen_supervisor' => 'Nitrogen Supervisor 1',
                'nitrogen_volume' => 500,
                'nitrogen_volume_unit' => 'bbl',
                'nozzle_type' => 'Nozzle Type 1',
                'other_charges' => 1000,
                'personnel_charges' => 3000,
                'power_pack' => 'Power Pack Type',
                'power_reel' => 'Power Reel Type',
                'pump_supervisor' => 'Pump Supervisor 1',
                'revenue_acid' => 10000,
                'revenue_cement' => 5000,
                'revenue_coiled_tubing' => 15000,
                'revenue_currency' => 'USD',
                'revenue_nitrogen_equipment' => 8000,
                'revenue_nitrogen_product' => 12000,
                'revenue_pumping' => 20000,
                'revenue_special_tools' => 3000,
                'service_charges' => 2500,
                'total_revenue' => 0, // This will be calculated later
                'updated_by' => $admin->id,
                'well_name' => 'Well Name ' . $index,
                'well_status' => 'Active',
                'well_type' => 'Production',
                'wellhead_x_over' => 'Wellhead X-Over Type',
                'wt' => 10,
                'cement_volume_unit' => 'bbl',
            ]);
        }
    }
}
