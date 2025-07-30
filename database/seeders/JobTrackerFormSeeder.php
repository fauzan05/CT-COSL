<?php

namespace Database\Seeders;

use App\Models\JobTracker\BOPModel;
use App\Models\JobTracker\CasingLinerSizeModel;
use App\Models\JobTracker\CJInjectorModel;
use App\Models\JobTracker\CompletionSizeModel;
use App\Models\JobTracker\ContainerModel;
use App\Models\JobTracker\ControlCabinModel;
use App\Models\JobTracker\COSLBaseModel;
use App\Models\JobTracker\CTGradeModel;
use App\Models\JobTracker\CTPersonnelModel;
use App\Models\JobTracker\CTSizeModel;
use App\Models\JobTracker\CTStringModel;
use App\Models\JobTracker\CustomerModel;
use App\Models\JobTracker\FieldLocationModel;
use App\Models\JobTracker\FieldTypeModel;
use App\Models\JobTracker\InjectorGoosneckModel;
use App\Models\JobTracker\JobDescriptionModel;
use App\Models\JobTracker\JobTrackerAcidTypeModel;
use App\Models\JobTracker\JobTrackerAcidVolumeModel;
use App\Models\JobTracker\JobTrackerContainerModel;
use App\Models\JobTracker\JobTrackerCTPersonnelModel;
use App\Models\JobTracker\JobTrackerInjectorGoosneckModel;
use App\Models\JobTracker\JobTrackerJobDescriptionModel;
use App\Models\JobTracker\JobTrackerMaxDepthModel;
use App\Models\JobTracker\JobTrackerMiscellaneousToolModel;
use App\Models\JobTracker\JobTrackerModel;
use App\Models\JobTracker\JobTrackerN2TankModel;
use App\Models\JobTracker\JobTrackerNitrogenPersonnelModel;
use App\Models\JobTracker\JobTrackerPumpPersonnelModel;
use App\Models\JobTracker\MaxBHAODModel;
use App\Models\JobTracker\MiscellaneousToolModel;
use App\Models\JobTracker\N2ConverterModel;
use App\Models\JobTracker\N2TankModel;
use App\Models\JobTracker\NitrogenPersonnelModel;
use App\Models\JobTracker\NozzleTypeModel;
use App\Models\JobTracker\PowerPackModel;
use App\Models\JobTracker\PowerReelModel;
use App\Models\JobTracker\WellheadXOverModel;
use App\Models\JobTracker\WellStatusModel;
use App\Models\JobTracker\WellTypeModel;
use App\Models\JobTracker\WTSModel;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class JobTrackerFormSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Insert Job Descriptions
        JobDescriptionModel::insert([
            ['description' => 'Well Intervention'],
            ['description' => 'Well Completion'],
            ['description' => 'Well Abandonment'],
            ['description' => 'Well Testing'],
            ['description' => 'Well Maintenance'],
            ['description' => 'Other'],
        ]);

        // Insert N2 Tanks
        N2TankModel::insert([
            ['n2_tank_name' => 'N2 Tank 1'],
            ['n2_tank_name' => 'N2 Tank 2'],
            ['n2_tank_name' => 'N2 Tank 3'],
            ['n2_tank_name' => 'N2 Tank 4'],
            ['n2_tank_name' => 'N2 Tank 5'],
        ]);

        // Insert Containers
        ContainerModel::insert([
            ['container_name' => 'Container 1'],
            ['container_name' => 'Container 2'],
            ['container_name' => 'Container 3'],
            ['container_name' => 'Container 4'],
            ['container_name' => 'Container 5'],
        ]);

        // Insert Injector Goosneck
        InjectorGoosneckModel::insert([
            ['injector_goosneck_name' => 'Goosneck 1'],
            ['injector_goosneck_name' => 'Goosneck 2'],
            ['injector_goosneck_name' => 'Goosneck 3'],
            ['injector_goosneck_name' => 'Goosneck 4'],
            ['injector_goosneck_name' => 'Goosneck 5'],
        ]);

        // Insert Miscellaneous Tools
        MiscellaneousToolModel::insert([
            ['miscellaneous_tool_name' => 'Tool 1'],
            ['miscellaneous_tool_name' => 'Tool 2'],
            ['miscellaneous_tool_name' => 'Tool 3'],
            ['miscellaneous_tool_name' => 'Tool 4'],
            ['miscellaneous_tool_name' => 'Tool 5'],
        ]);

        // Insert CT Personnels
        CTPersonnelModel::insert([
            ['ct_personnel_name' => 'CT Personnel 1'],
            ['ct_personnel_name' => 'CT Personnel 2'],
            ['ct_personnel_name' => 'CT Personnel 3'],
            ['ct_personnel_name' => 'CT Personnel 4'],
            ['ct_personnel_name' => 'CT Personnel 5'],
        ]);

        // Insert Nitrogen Personnels
        NitrogenPersonnelModel::insert([
            ['nitrogen_personnel_name' => 'Nitrogen Personnel 1'],
            ['nitrogen_personnel_name' => 'Nitrogen Personnel 2'],
            ['nitrogen_personnel_name' => 'Nitrogen Personnel 3'],
            ['nitrogen_personnel_name' => 'Nitrogen Personnel 4'],
            ['nitrogen_personnel_name' => 'Nitrogen Personnel 5'],
        ]);

        // Insert WT
        WTSModel::insert([
            ['size' => 'WT 1'],
            ['size' => 'WT 2'],
            ['size' => 'WT 3'],
            ['size' => 'WT 4'],
            ['size' => 'WT 5'],
        ]);

        // Insert Customers
        CustomerModel::insert([
            ['customer_name' => 'Customer A'],
            ['customer_name' => 'Customer B'],
            ['customer_name' => 'Customer C'],
            ['customer_name' => 'Customer D'],
            ['customer_name' => 'Customer E'],
        ]);

        // Insert Field Location
        FieldLocationModel::insert([
            ['location_name' => 'Field Location 1'],
            ['location_name' => 'Field Location 2'],
            ['location_name' => 'Field Location 3'],
            ['location_name' => 'Field Location 4'],
            ['location_name' => 'Field Location 5'],
        ]);

        // Insert Type
        FieldTypeModel::insert([
            ['type_name' => 'Type A'],
            ['type_name' => 'Type B'],
            ['type_name' => 'Type C'],
            ['type_name' => 'Type D'],
            ['type_name' => 'Type E'],
        ]);

        // Insert Well Status
        WellStatusModel::insert([
            ['status_name' => 'Active'],
            ['status_name' => 'Inactive'],
            ['status_name' => 'Completed'],
            ['status_name' => 'Abandoned'],
            ['status_name' => 'Testing'],
        ]);

        // Insert Well Type
        WellTypeModel::insert([
            ['type_name' => 'Oil Well'],
            ['type_name' => 'Gas Well'],
            ['type_name' => 'Water Well'],
            ['type_name' => 'Injection Well'],
            ['type_name' => 'Observation Well'],
        ]);

        // Insert Wellhead x Over
        WellheadXOverModel::insert([
            ['wellhead_name' => 'Wellhead X-Over 1'],
            ['wellhead_name' => 'Wellhead X-Over 2'],
            ['wellhead_name' => 'Wellhead X-Over 3'],
            ['wellhead_name' => 'Wellhead X-Over 4'],
            ['wellhead_name' => 'Wellhead X-Over 5'],
        ]);

        // Insert Casing Liner Size (decimal)
        CasingLinerSizeModel::insert([
            ['size' => 7.00],
            ['size' => 9.00],
            ['size' => 10.75],
            ['size' => 13.37],
            ['size' => 16.00],
        ]);

        // Insert Completion Size (decimal)
        CompletionSizeModel::insert([
            ['size' => 5.50],
            ['size' => 7.00],
            ['size' => 9.00],
            ['size' => 10.75],
            ['size' => 13.37],
        ]);

        // Nozzle type
        NozzleTypeModel::insert([
            ['type_name' => 'Nozzle Type 1'],
            ['type_name' => 'Nozzle Type 2'],
            ['type_name' => 'Nozzle Type 3'],
            ['type_name' => 'Nozzle Type 4'],
            ['type_name' => 'Nozzle Type 5'],
        ]);

        // Max BHA Model
        MaxBHAODModel::insert([
            ['size' => 3.00],
            ['size' => 4.00],
            ['size' => 5.00],
            ['size' => 6.00],
            ['size' => 7.00],
        ]);

        // Control cabin
        ControlCabinModel::insert([
            ['cabin_name' => 'Control Cabin 1'],
            ['cabin_name' => 'Control Cabin 2'],
            ['cabin_name' => 'Control Cabin 3'],
            ['cabin_name' => 'Control Cabin 4'],
            ['cabin_name' => 'Control Cabin 5'],
        ]);

        // Power Pack
        PowerPackModel::insert([
            ['power_pack_name' => 'Power Pack 1'],
            ['power_pack_name' => 'Power Pack 2'],
            ['power_pack_name' => 'Power Pack 3'],
            ['power_pack_name' => 'Power Pack 4'],
            ['power_pack_name' => 'Power Pack 5'],
        ]);

        // Power Reel
        PowerReelModel::insert([
            ['power_reel_name' => 'Power Reel 1'],
            ['power_reel_name' => 'Power Reel 2'],
            ['power_reel_name' => 'Power Reel 3'],
            ['power_reel_name' => 'Power Reel 4'],
            ['power_reel_name' => 'Power Reel 5'],
        ]);

        // CJ Injector
        CJInjectorModel::insert([
            ['cj_injector_name' => 'CJ Injector 1'],
            ['cj_injector_name' => 'CJ Injector 2'],
            ['cj_injector_name' => 'CJ Injector 3'],
            ['cj_injector_name' => 'CJ Injector 4'],
            ['cj_injector_name' => 'CJ Injector 5'],
        ]);

        // BOP 
        BOPModel::insert([
            ['bop_name' => 'BOP 1'],
            ['bop_name' => 'BOP 2'],
            ['bop_name' => 'BOP 3'],
            ['bop_name' => 'BOP 4'],
            ['bop_name' => 'BOP 5'],
        ]);

        // CT Size
        CTSizeModel::insert([
            ['size' => 1.00],
            ['size' => 1.25],
            ['size' => 1.50],
            ['size' => 1.75],
            ['size' => 2.00],
        ]);

        // CT Grade
        CTGradeModel::insert([
            ['grade_name' => 'Grade A'],
            ['grade_name' => 'Grade B'],
            ['grade_name' => 'Grade C'],
            ['grade_name' => 'Grade D'],
            ['grade_name' => 'Grade E'],
        ]);

        // Insert CT String
        CTStringModel::insert([
            ['ct_string_name' => 'CT String 1'],
            ['ct_string_name' => 'CT String 2'],
            ['ct_string_name' => 'CT String 3'],
            ['ct_string_name' => 'CT String 4'],
            ['ct_string_name' => 'CT String 5'],
        ]);

        // Insert N2 Converter
        N2ConverterModel::insert([
            ['n2_converter_name' => 'N2 Converter 1'],
            ['n2_converter_name' => 'N2 Converter 2'],
            ['n2_converter_name' => 'N2 Converter 3'],
            ['n2_converter_name' => 'N2 Converter 4'],
            ['n2_converter_name' => 'N2 Converter 5'],
        ]);

        // Insert COSL Bases
        COSLBaseModel::insert([
            ['base_name' => 'COSL Base 1'],
            ['base_name' => 'COSL Base 2'],
            ['base_name' => 'COSL Base 3'],
            ['base_name' => 'COSL Base 4'],
            ['base_name' => 'COSL Base 5'],
        ]);

        // Insert Job Trackers
        JobTrackerModel::insert([
            'bh_pressure' => 0,
            'bh_pressure_unit' => 'psi',
            'bh_temp' => 0,
            'bh_temp_unit' => '°F',
            'cosl_base' => 'COSL Base 1',
            'cosl_ocd_representative' => 'COSL Representative 1',
            'bop' => 'BOP 1',
            'casing_liner_size' => 7.00,
            'casing_liner_size_unit' => 'in',
            'cement_volume' => 0,
            'cement_volume_unit' => 'Bbls',
            'company_man' => 'Company Man 1',
            'completion_size' => 5.50,
            'completion_size_unit' => 'in',
            'control_cabin' => 'Control Cabin 1',
            'created_by' => 1,
            'ct_grade' => 'Grade A',
            'ct_size' => 1.00,
            'ct_size_unit' => 'in',
            'ct_string' => 'CT String 1',
            'ct_supervisor' => 'CT Supervisor 1',
            'cj_injector' => 'CJ Injector 1',
            'customer' => 'Customer A',
            'depth_md' => 1000.00,
            'depth_md_unit' => 'ft',
            'depth_tvd' => 800.00,
            'depth_tvd_unit' => 'ft',
            'field_location' => 'Field Location 1',
            'field_type' => 'Type A',
            'job_days' => 5,
            'job_finish_date' => now()->addDays(5),
            'job_start_date' => now(),
            'max_bha_od' => 3.00,
            'max_bha_od_unit' => 'in',
            'max_deviation' => 30.00,
            'material_charges' => 1000.00,
            'mobilization_charges' => 500.00,
            'n2_converter' => 'N2 Converter 1',
            'nitrogen_supervisor' => 'Nitrogen Supervisor 1',
            'nitrogen_volume' => 1000.00,
            'nitrogen_volume_unit' => 'Gals',
            'nozzle_type' => 'Nozzle Type 1',
            'other_charges' => 200.00,
            'personnel_charges' => 300.00,
            'power_pack' => 'Power Pack 1',
            'power_reel' => 'Power Reel 1',
            'pump_supervisor' => 'Pump Supervisor 1',
            'revenue_acid' => 0,
            'revenue_cement' => 0,
            'revenue_coiled_tubing' => 0,
            'revenue_currency' => 'USD',
            'revenue_nitrogen_equipment' => 0,
            'revenue_nitrogen_product' => 0,
            'revenue_pumping' => 0,
            'revenue_special_tools' => 0,
            'service_charges' => 0,
            'total_revenue' => 0,
            'updated_by' => 1,
            'well_name' => 'Well 1',
            'well_status' => 'Active',
            'well_type' => 'Oil Well',
            'wellhead_x_over' => 'Wellhead X-Over 1',
            'wt' => 1.00,
        ]);

        // insert Job Tracker relation
        JobTrackerJobDescriptionModel::insert(
            [
                'job_tracker_id' => 1,
                'description' => 'Well Intervention',
                'created_by' => 1,
                'updated_by' => 1,
            ],
            [
                'job_tracker_id' => 1,
                'description' => 'Well Completion',
                'created_by' => 1,
                'updated_by' => 1,
            ],
            [
                'job_tracker_id' => 1,
                'description' => 'Well Abandonment',
                'created_by' => 1,
                'updated_by' => 1,
            ],
            [
                'job_tracker_id' => 1,
                'description' => 'Well Testing',
                'created_by' => 1,
                'updated_by' => 1,
            ],
            [
                'job_tracker_id' => 1,
                'description' => 'Well Maintenance',
                'created_by' => 1,
                'updated_by' => 1,
            ]
        );

        JobTrackerMaxDepthModel::insert(
            [
                'job_tracker_id' => 1,
                'max_depth' => 1000.00,
                'max_depth_unit' => 'ft',
                'created_by' => 1,
                'updated_by' => 1,
            ],
            [
                'job_tracker_id' => 1,
                'max_depth' => 800.00,
                'max_depth_unit' => 'ft',
                'created_by' => 1,
                'updated_by' => 1,
            ]
        );

        JobTrackerN2TankModel::insert(
            [
                'job_tracker_id' => 1,
                'n2_tank_name' => 'N2 Tank 3',
                'created_by' => 1,
                'updated_by' => 1,
            ],
            [
                'job_tracker_id' => 1,
                'n2_tank_name' => 'N2 Tank 1',
                'created_by' => 1,
                'updated_by' => 1,
            ],
            [
                'job_tracker_id' => 1,
                'n2_tank_name' => 'N2 Tank 2',
                'created_by' => 1,
                'updated_by' => 1,
            ],
            [
                'job_tracker_id' => 1,
                'n2_tank_name' => 'N2 Tank 4',
                'created_by' => 1,
                'updated_by' => 1,
            ],
            [
                'job_tracker_id' => 1,
                'n2_tank_name' => 'N2 Tank 5',
                'created_by' => 1,
                'updated_by' => 1,
            ]
        );

        JobTrackerContainerModel::insert(
            [
                'job_tracker_id' => 1,
                'container_name' => 'Container 2',
                'created_by' => 1,
                'updated_by' => 1,
            ],
            [
                'job_tracker_id' => 1,
                'container_name' => 'Container 1',
                'created_by' => 1,
                'updated_by' => 1,
            ],
            [
                'job_tracker_id' => 1,
                'container_name' => 'Container 3',
                'created_by' => 1,
                'updated_by' => 1,
            ],
            [
                'job_tracker_id' => 1,
                'container_name' => 'Container 4',
                'created_by' => 1,
                'updated_by' => 1,
            ],
            [
                'job_tracker_id' => 1,
                'container_name' => 'Container 5',
                'created_by' => 1,
                'updated_by' => 1,
            ]
        );

        JobTrackerInjectorGoosneckModel::insert(
            [
                'job_tracker_id' => 1,
                'injector_goosneck_name' => 'Goosneck 4',
                'created_by' => 1,
                'updated_by' => 1,
            ],
            [
                'job_tracker_id' => 1,
                'injector_goosneck_name' => 'Goosneck 1',
                'created_by' => 1,
                'updated_by' => 1,
            ],
            [
                'job_tracker_id' => 1,
                'injector_goosneck_name' => 'Goosneck 2',
                'created_by' => 1,
                'updated_by' => 1,
            ],
            [
                'job_tracker_id' => 1,
                'injector_goosneck_name' => 'Goosneck 3',
                'created_by' => 1,
                'updated_by' => 1,
            ]
        );

        JobTrackerMiscellaneousToolModel::insert(
            [
                'job_tracker_id' => 1,
                'miscellaneous_tool_name' => 'Tool 5',
                'created_by' => 1,
                'updated_by' => 1,
            ],
            [
                'job_tracker_id' => 1,
                'miscellaneous_tool_name' => 'Tool 1',
                'created_by' => 1,
                'updated_by' => 1,
            ],
            [
                'job_tracker_id' => 1,
                'miscellaneous_tool_name' => 'Tool 2',
                'created_by' => 1,
                'updated_by' => 1,
            ],
            [
                'job_tracker_id' => 1,
                'miscellaneous_tool_name' => 'Tool 3',
                'created_by' => 1,
                'updated_by' => 1,
            ],
            [
                'job_tracker_id' => 1,
                'miscellaneous_tool_name' => 'Tool 4',
                'created_by' => 1,
                'updated_by' => 1,
            ]
        );

        JobTrackerCTPersonnelModel::insert(
            [
                'job_tracker_id' => 1,
                'ct_personnel_name' => 'CT Personnel 2',
                'created_by' => 1,
                'updated_by' => 1,
            ],
            [
                'job_tracker_id' => 1,
                'ct_personnel_name' => 'CT Personnel 3',
                'created_by' => 1,
                'updated_by' => 1,
            ],
            [
                'job_tracker_id' => 1,
                'ct_personnel_name' => 'CT Personnel 4',
                'created_by' => 1,
                'updated_by' => 1,
            ],
            [
                'job_tracker_id' => 1,
                'ct_personnel_name' => 'CT Personnel 5',
                'created_by' => 1,
                'updated_by' => 1,
            ]
        );

        JobTrackerNitrogenPersonnelModel::insert(
            [
                'job_tracker_id' => 1,
                'nitrogen_personnel_name' => 'Nitrogen Personnel 2',
                'created_by' => 1,
                'updated_by' => 1,
            ],
            [
                'job_tracker_id' => 1,
                'nitrogen_personnel_name' => 'Nitrogen Personnel 3',
                'created_by' => 1,
                'updated_by' => 1,
            ],
            [
                'job_tracker_id' => 1,
                'nitrogen_personnel_name' => 'Nitrogen Personnel 4',
                'created_by' => 1,
                'updated_by' => 1,
            ],
            [
                'job_tracker_id' => 1,
                'nitrogen_personnel_name' => 'Nitrogen Personnel 5',
                'created_by' => 1,
                'updated_by' => 1,
            ]
        );

        JobTrackerAcidTypeModel::insert(
            [
                'job_tracker_id' => 1,
                'acid_type' => 'Acid Type 1',
                'created_by' => 1,
                'updated_by' => 1,
            ],
            [
                'job_tracker_id' => 1,
                'acid_type' => 'Acid Type 2',
                'created_by' => 1,
                'updated_by' => 1,
            ],
            [
                'job_tracker_id' => 1,
                'acid_type' => 'Acid Type 3',
                'created_by' => 1,
                'updated_by' => 1,
            ]
        );

        JobTrackerAcidVolumeModel::insert(
            [
                'job_tracker_id' => 1,
                'volume' => 100.00,
                'volume_unit' => 'Gals',
                'created_by' => 1,
                'updated_by' => 1,
            ],
            [
                'job_tracker_id' => 1,
                'volume' => 200.00,
                'volume_unit' => 'Gals',
                'created_by' => 1,
                'updated_by' => 1,
            ],
            [
                'job_tracker_id' => 1,
                'volume' => 300.00,
                'volume_unit' => 'Gals',
                'created_by' => 1,
                'updated_by' => 1,
            ]
        );

        JobTrackerPumpPersonnelModel::insert(
            [
                'job_tracker_id' => 1,
                'pump_personnel_name' => 'Pump Personnel 1',
                'created_by' => 1,
                'updated_by' => 1,
            ],
            [
                'job_tracker_id' => 1,
                'pump_personnel_name' => 'Pump Personnel 2',
                'created_by' => 1,
                'updated_by' => 1,
            ],
            [
                'job_tracker_id' => 1,
                'pump_personnel_name' => 'Pump Personnel 3',
                'created_by' => 1,
                'updated_by' => 1,
            ]
        );
    }
}
