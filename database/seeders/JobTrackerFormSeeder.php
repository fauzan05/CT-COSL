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
use Illuminate\Support\Str;

class JobTrackerFormSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        JobDescriptionModel::insert(array_map(fn($desc) => [
            'id' => Str::uuid(),
            'description' => $desc,
        ], [
            'Well Intervention',
            'Well Completion',
            'Well Abandonment',
            'Well Testing',
            'Well Maintenance',
            'Other'
        ]));

        N2TankModel::insert(array_map(fn($name) => [
            'id' => Str::uuid(),
            'n2_tank_name' => $name,
        ], [
            'N2 Tank 1',
            'N2 Tank 2',
            'N2 Tank 3',
            'N2 Tank 4',
            'N2 Tank 5'
        ]));

        ContainerModel::insert(array_map(fn($name) => [
            'id' => Str::uuid(),
            'container_name' => $name,
        ], [
            'Container 1',
            'Container 2',
            'Container 3',
            'Container 4',
            'Container 5'
        ]));

        InjectorGoosneckModel::insert(array_map(fn($name) => [
            'id' => Str::uuid(),
            'injector_goosneck_name' => $name,
        ], [
            'Goosneck 1',
            'Goosneck 2',
            'Goosneck 3',
            'Goosneck 4',
            'Goosneck 5'
        ]));

        MiscellaneousToolModel::insert(array_map(fn($name) => [
            'id' => Str::uuid(),
            'miscellaneous_tool_name' => $name,
        ], [
            'Tool 1',
            'Tool 2',
            'Tool 3',
            'Tool 4',
            'Tool 5'
        ]));

        CTPersonnelModel::insert(array_map(fn($name) => [
            'id' => Str::uuid(),
            'ct_personnel_name' => $name,
        ], [
            'CT Personnel 1',
            'CT Personnel 2',
            'CT Personnel 3',
            'CT Personnel 4',
            'CT Personnel 5'
        ]));

        NitrogenPersonnelModel::insert(array_map(fn($name) => [
            'id' => Str::uuid(),
            'nitrogen_personnel_name' => $name,
        ], [
            'Nitrogen Personnel 1',
            'Nitrogen Personnel 2',
            'Nitrogen Personnel 3',
            'Nitrogen Personnel 4',
            'Nitrogen Personnel 5'
        ]));

        WTSModel::insert(array_map(fn($size) => [
            'id' => Str::uuid(),
            'size' => $size,
        ], [
            'WT 1',
            'WT 2',
            'WT 3',
            'WT 4',
            'WT 5'
        ]));

        CustomerModel::insert(array_map(fn($name) => [
            'id' => Str::uuid(),
            'customer_name' => $name,
        ], [
            'Customer A',
            'Customer B',
            'Customer C',
            'Customer D',
            'Customer E'
        ]));

        FieldLocationModel::insert(array_map(fn($name) => [
            'id' => Str::uuid(),
            'location_name' => $name,
        ], [
            'Field Location 1',
            'Field Location 2',
            'Field Location 3',
            'Field Location 4',
            'Field Location 5'
        ]));

        FieldTypeModel::insert(array_map(fn($name) => [
            'id' => Str::uuid(),
            'type_name' => $name,
        ], [
            'Type A',
            'Type B',
            'Type C',
            'Type D',
            'Type E'
        ]));

        WellStatusModel::insert(array_map(fn($name) => [
            'id' => Str::uuid(),
            'status_name' => $name,
        ], [
            'Active',
            'Inactive',
            'Completed',
            'Abandoned',
            'Testing'
        ]));

        WellTypeModel::insert(array_map(fn($name) => [
            'id' => Str::uuid(),
            'type_name' => $name,
        ], [
            'Oil Well',
            'Gas Well',
            'Water Well',
            'Injection Well',
            'Observation Well'
        ]));

        WellheadXOverModel::insert(array_map(fn($name) => [
            'id' => Str::uuid(),
            'wellhead_name' => $name,
        ], [
            'Wellhead X-Over 1',
            'Wellhead X-Over 2',
            'Wellhead X-Over 3',
            'Wellhead X-Over 4',
            'Wellhead X-Over 5'
        ]));

        CasingLinerSizeModel::insert(array_map(fn($size) => [
            'id' => Str::uuid(),
            'size' => $size,
        ], [
            7.00,
            9.00,
            10.75,
            13.37,
            16.00
        ]));

        CompletionSizeModel::insert(array_map(fn($size) => [
            'id' => Str::uuid(),
            'size' => $size,
        ], [
            5.50,
            7.00,
            9.00,
            10.75,
            13.37
        ]));

        NozzleTypeModel::insert(array_map(fn($name) => [
            'id' => Str::uuid(),
            'type_name' => $name,
        ], [
            'Nozzle Type 1',
            'Nozzle Type 2',
            'Nozzle Type 3',
            'Nozzle Type 4',
            'Nozzle Type 5'
        ]));

        MaxBHAODModel::insert(array_map(fn($size) => [
            'id' => Str::uuid(),
            'size' => $size,
        ], [
            3.00,
            4.00,
            5.00,
            6.00,
            7.00
        ]));

        ControlCabinModel::insert(array_map(fn($name) => [
            'id' => Str::uuid(),
            'cabin_name' => $name,
        ], [
            'Control Cabin 1',
            'Control Cabin 2',
            'Control Cabin 3',
            'Control Cabin 4',
            'Control Cabin 5'
        ]));

        PowerPackModel::insert(array_map(fn($name) => [
            'id' => Str::uuid(),
            'power_pack_name' => $name,
        ], [
            'Power Pack 1',
            'Power Pack 2',
            'Power Pack 3',
            'Power Pack 4',
            'Power Pack 5'
        ]));

        PowerReelModel::insert(array_map(fn($name) => [
            'id' => Str::uuid(),
            'power_reel_name' => $name,
        ], [
            'Power Reel 1',
            'Power Reel 2',
            'Power Reel 3',
            'Power Reel 4',
            'Power Reel 5'
        ]));

        CJInjectorModel::insert(array_map(fn($name) => [
            'id' => Str::uuid(),
            'cj_injector_name' => $name,
        ], [
            'CJ Injector 1',
            'CJ Injector 2',
            'CJ Injector 3',
            'CJ Injector 4',
            'CJ Injector 5'
        ]));

        BOPModel::insert(array_map(fn($name) => [
            'id' => Str::uuid(),
            'bop_name' => $name,
        ], [
            'BOP 1',
            'BOP 2',
            'BOP 3',
            'BOP 4',
            'BOP 5'
        ]));

        CTSizeModel::insert(array_map(fn($size) => [
            'id' => Str::uuid(),
            'size' => $size,
        ], [
            1.00,
            1.25,
            1.50,
            1.75,
            2.00
        ]));

        CTGradeModel::insert(array_map(fn($name) => [
            'id' => Str::uuid(),
            'grade_name' => $name,
        ], [
            'Grade A',
            'Grade B',
            'Grade C',
            'Grade D',
            'Grade E'
        ]));

        CTStringModel::insert(array_map(fn($name) => [
            'id' => Str::uuid(),
            'ct_string_name' => $name,
        ], [
            'CT String 1',
            'CT String 2',
            'CT String 3',
            'CT String 4',
            'CT String 5'
        ]));

        N2ConverterModel::insert(array_map(fn($name) => [
            'id' => Str::uuid(),
            'n2_converter_name' => $name,
        ], [
            'N2 Converter 1',
            'N2 Converter 2',
            'N2 Converter 3',
            'N2 Converter 4',
            'N2 Converter 5'
        ]));

        COSLBaseModel::insert(array_map(fn($name) => [
            'id' => Str::uuid(),
            'base_name' => $name,
        ], [
            'COSL Base 1',
            'COSL Base 2',
            'COSL Base 3',
            'COSL Base 4',
            'COSL Base 5'
        ]));

        // Note: JobTrackerModel dan relasi-relasinya akan dibuat di bagian lanjutan (karena butuh UUID tracking antar record)
    }
}
