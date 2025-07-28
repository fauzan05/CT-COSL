<?php

namespace App\Http\Controllers;

use App\Models\JobTracker\BJDistrictModel;
use App\Models\JobTracker\BOPModel;
use App\Models\JobTracker\CasingLinerSizeModel;
use App\Models\JobTracker\CJInjectorModel;
use App\Models\JobTracker\CompletionSizeModel;
use App\Models\JobTracker\ContainerModel;
use App\Models\JobTracker\ControlCabinModel;
use App\Models\JobTracker\CTGradeModel;
use App\Models\JobTracker\CTPersonnelModel;
use App\Models\JobTracker\CTSizeModel;
use App\Models\JobTracker\CTStringModel;
use App\Models\JobTracker\CTSupervisorModel;
use App\Models\JobTracker\CustomerModel;
use App\Models\JobTracker\FieldLocationModel;
use App\Models\JobTracker\FieldTypeModel;
use App\Models\JobTracker\InjectorGoosneckModel;
use App\Models\JobTracker\JobDescriptionModel;
use App\Models\JobTracker\JobTrackerAcidTypeModel;
use App\Models\JobTracker\JobTrackerAcidVolumeModel;
use App\Models\JobTracker\JobTrackerCompletionSizeModel;
use App\Models\JobTracker\JobTrackerContainerModel;
use App\Models\JobTracker\JobTrackerCTPersonnelModel;
use App\Models\JobTracker\JobTrackerInjectorGoosneckModel;
use App\Models\JobTracker\JobTrackerJobDescriptionModel;
use App\Models\JobTracker\JobTrackerMaxBHAODModel;
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
use App\Models\JobTracker\NitrogenSupervisorModel;
use App\Models\JobTracker\NozzleTypeModel;
use App\Models\JobTracker\PowerPackModel;
use App\Models\JobTracker\PowerReelModel;
use App\Models\JobTracker\WellheadXOverModel;
use App\Models\JobTracker\WellStatusModel;
use App\Models\JobTracker\WellTypeModel;
use App\Models\JobTracker\WTSModel;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class JobTrackerController extends Controller
{
    public function getJobTrackers(Request $request)
    {
        $perPage = $request->input('per_page', 10);

        // Query builder
        $query = JobTrackerModel::with([
            'updatedBy'
        ]);

        $sortBy = $request->input('sort_by', 'created_at');
        $sortOrder = $request->input('is_desc', 'desc') === 'true' ? 'desc' : 'asc';

        // Apply sorting
        $query->orderBy($sortBy, $sortOrder);

        // Apply filters if any
        if ($request->filled('search')) {
            $search = $request->input('search');
            $query->where(function ($q) use ($search) {
                $q->where('job_name', 'like', '%' . $search . '%')
                    ->orWhere('job_number', 'like', '%' . $search . '%');
            });
        }
        // Paginate the results
        $jobTrackers = $query->paginate($perPage);
        // Transform items in the paginator
        $jobTrackers->getCollection()->transform(function ($jobTracker) {
            return [
                'id' => $jobTracker->id,
                'well_name' => $jobTracker->well_name,
                'company_man' => $jobTracker->company_man,
                'bj_representative' => $jobTracker->bj_representative,
                'job_start_date' => $jobTracker->job_start_date,
                'job_finish_date' => $jobTracker->job_finish_date,
                'job_days' => $jobTracker->job_days,
                'updated_at' => $jobTracker->updated_at,
                'updated_by_name' => $jobTracker->updatedBy ? $jobTracker->updatedBy->fullname : null
            ];
        });

        // Return the full paginator object as JSON
        return response()->json($jobTrackers, 200);
    }

    public function storeJobTracker(Request $request)
    {
        try {
            // Start database transaction
            DB::beginTransaction();

            // Prepare main job tracker data
            $data = [
                'well_name' => $request->well_name,
                'company_man' => $request->company_man,
                'bj_representative' => $request->bj_representative,
                'job_start_date' => $request->job_start_date,
                'job_finish_date' => $request->job_finish_date,
                'job_days' => $request->job_days,
                'max_deviation' => $request->max_deviation,
                'customer' => $request->customer,
                'bj_district' => $request->bj_district,
                'field_location' => $request->field_location,
                'casing_liner_size' => $request->casing_liner_size['size'] ?? 0,
                'casing_liner_size_unit' => $request->casing_liner_size['unit'] ?? null,
                'max_bha_od' => $request->max_bha_od['size'] ?? 0,
                'max_bha_od_unit' => $request->max_bha_od['unit'] ?? 0,
                'completion_size' => $request->completion_size['size'] ?? 0,
                'completion_size_unit' => $request->completion_size['unit'] ?? null,
                'field_type' => $request->field_type,
                'wellhead_x_over' => $request->wellhead_x_over,
                'well_status' => $request->well_status,
                'well_type' => $request->well_type,
                'nozzle_type' => $request->nozzle_type,
                'control_cabin' => $request->control_cabin,
                'power_pack' => $request->power_pack,
                'power_reel' => $request->power_reel,
                'cj_injector' => $request->cj_injector,
                'bop' => $request->bop,
                'ct_size' => $request->ct_size['size'] ?? 0,
                'ct_size_unit' => $request->ct_size['unit'] ?? null,
                'ct_grade' => $request->ct_grade,
                'wt' => $request->wt['size'] ?? 0,
                'wt_unit' => $request->wt['unit'],
                'ct_string' => $request->ct_string,
                'n2_converter' => $request->n2_converter,
                'ct_supervisor' => $request->ct_supervisor,
                'nitrogen_supervisor' => $request->nitrogen_supervisor,
                'pump_supervisor' => $request->pump_supervisor,
                'depth_md' => $request->depth_md,
                'depth_md_unit' => $request->depth_md_unit,
                'depth_tvd' => $request->depth_tvd,
                'depth_tvd_unit' => $request->depth_tvd_unit,
                'bh_pressure' => $request->bh_pressure,
                'bh_pressure_unit' => $request->bh_pressure_unit,
                'bh_temp' => $request->bh_temp,
                'bh_temp_unit' => $request->bh_temp_unit,
                'nitrogen_volume' => $request->nitrogen_volume,
                'nitrogen_volume_unit' => $request->nitrogen_volume_unit,
                'cement_volume' => $request->cement_volume,
                'cement_volume_unit' => $request->cement_volume_unit,
                'revenue_currency' => $request->revenue_currency,
                'revenue_coiled_tubing' => floatval($request->revenue_coiled_tubing),
                'revenue_pumping' => floatval($request->revenue_pumping),
                'revenue_special_tools' => floatval($request->revenue_special_tools),
                'revenue_acid' => floatval($request->revenue_acid),
                'revenue_nitrogen_equipment' => floatval($request->revenue_nitrogen_equipment),
                'revenue_nitrogen_product' => floatval($request->revenue_nitrogen_product),
                'revenue_cement' => floatval($request->revenue_cement),
                'personnel_charges' => floatval($request->personnel_charges),
                'service_charges' => floatval($request->service_charges),
                'other_charges' => floatval($request->other_charges),
                'total_revenue' => floatval($request->total_revenue),
                'updated_at' => now(),
                'updated_by' => $request->user()->id,
            ];

            // Create main job tracker record
            $jobTracker = JobTrackerModel::create($data + [
                'created_at' => now(),
                'created_by' => $request->user()->id,
                'updated_at' => now(),
                'updated_by' => $request->user()->id,
            ]);

            // Handle job descriptions
            if ($request->has('job_descriptions') && is_array($request->job_descriptions)) {
                foreach ($request->job_descriptions as $description) {
                    JobTrackerJobDescriptionModel::create([
                        'job_tracker_id' => $jobTracker->id,
                        'description' => $description,
                        'created_at' => now(),
                        'created_by' => $request->user()->id,
                        'updated_at' => now(),
                        'updated_by' => $request->user()->id,
                    ]);
                }
            }

            // Handle max depths
            if ($request->has('max_depths') && is_array($request->max_depths)) {
                foreach ($request->max_depths as $maxDepth) {
                    if (isset($maxDepth['value']) && isset($maxDepth['unit'])) {
                        JobTrackerMaxDepthModel::create([
                            'job_tracker_id' => $jobTracker->id,
                            'max_depth' => $maxDepth['value'] ?? 0,
                            'max_depth_unit' => $maxDepth['unit'] ?? null,
                            'created_at' => now(),
                            'created_by' => $request->user()->id,
                            'updated_at' => now(),
                            'updated_by' => $request->user()->id,
                        ]);
                    }
                }
            }

            // Handle N2 Tanks (array)
            if ($request->has('n2_tanks') && is_array($request->n2_tanks)) {
                foreach ($request->n2_tanks as $tank_name) {
                    JobTrackerN2TankModel::create([
                        'job_tracker_id' => $jobTracker->id,
                        'n2_tank_name' => $tank_name,
                        'created_at' => now(),
                        'created_by' => $request->user()->id,
                        'updated_at' => now(),
                        'updated_by' => $request->user()->id,
                    ]);
                }
            }

            // Handle Containers (array)
            if ($request->has('containers') && is_array($request->containers)) {
                foreach ($request->containers as $container_name) {
                    JobTrackerContainerModel::create([
                        'job_tracker_id' => $jobTracker->id,
                        'container_name' => $container_name,
                        'created_at' => now(),
                        'created_by' => $request->user()->id,
                        'updated_at' => now(),
                        'updated_by' => $request->user()->id,
                    ]);
                }
            }

            // Handle Injector Goosnecks (array)
            if ($request->has('injector_goosnecks') && is_array($request->injector_goosnecks)) {
                foreach ($request->injector_goosnecks as $injector_goosneck_name) {
                    JobTrackerInjectorGoosneckModel::create([
                        'job_tracker_id' => $jobTracker->id,
                        'injector_goosneck_name' => $injector_goosneck_name,
                        'created_at' => now(),
                        'created_by' => $request->user()->id,
                        'updated_at' => now(),
                        'updated_by' => $request->user()->id,
                    ]);
                }
            }

            // Handle Miscellaneous Tools (array)
            if ($request->has('miscellaneous_tools') && is_array($request->miscellaneous_tools)) {
                foreach ($request->miscellaneous_tools as $miscellaneous_tool_name) {
                    JobTrackerMiscellaneousToolModel::create([
                        'job_tracker_id' => $jobTracker->id,
                        'miscellaneous_tool_name' => $miscellaneous_tool_name,
                        'created_at' => now(),
                        'created_by' => $request->user()->id,
                        'updated_at' => now(),
                        'updated_by' => $request->user()->id,
                    ]);
                }
            }

            // Handle CT Personnel (array)
            if ($request->has('ct_personnels') && is_array($request->ct_personnels)) {
                foreach ($request->ct_personnels as $ct_personnel_name) {
                    JobTrackerCTPersonnelModel::create([
                        'job_tracker_id' => $jobTracker->id,
                        'ct_personnel_name' => $ct_personnel_name,
                        'created_at' => now(),
                        'created_by' => $request->user()->id,
                        'updated_at' => now(),
                        'updated_by' => $request->user()->id,
                    ]);
                }
            }

            // Handle Nitrogen Personnels (array)
            if ($request->has('nitrogen_personnels') && is_array($request->nitrogen_personnels)) {
                foreach ($request->nitrogen_personnels as $nitrogen_personnel_name) {
                    JobTrackerNitrogenPersonnelModel::create([
                        'job_tracker_id' => $jobTracker->id,
                        'nitrogen_personnel_name' => $nitrogen_personnel_name,
                        'created_at' => now(),
                        'created_by' => $request->user()->id,
                        'updated_at' => now(),
                        'updated_by' => $request->user()->id,
                    ]);
                }
            }

            // Handle Pump Personnels (array)
            if ($request->has('pump_personnels') && is_array($request->pump_personnels)) {
                foreach ($request->pump_personnels as $pump_personnel_name) {
                    JobTrackerPumpPersonnelModel::create([
                        'job_tracker_id' => $jobTracker->id,
                        'pump_personnel_name' => $pump_personnel_name,
                        'created_at' => now(),
                        'created_by' => $request->user()->id,
                        'updated_at' => now(),
                        'updated_by' => $request->user()->id,
                    ]);
                }
            }

            // Handle Acid Types (array)
            if ($request->has('acid_types') && is_array($request->acid_types)) {
                foreach ($request->acid_types as $acid_type) {
                    JobTrackerAcidTypeModel::create([
                        'job_tracker_id' => $jobTracker->id,
                        'acid_type' => $acid_type['value'] ?? 0,
                        'created_at' => now(),
                        'created_by' => $request->user()->id,
                        'updated_at' => now(),
                        'updated_by' => $request->user()->id,
                    ]);
                }
            }

            // Handle Acid Volume (array)
            if ($request->has('acid_volumes') && is_array($request->acid_volumes)) {
                foreach ($request->acid_volumes as $acid_volume) {
                    JobTrackerAcidVolumeModel::create([
                        'job_tracker_id' => $jobTracker->id,
                        'volume' => $acid_volume['value'] ?? 0,
                        'volume_unit' => $acid_volume['unit'] ?? null,
                        'created_at' => now(),
                        'created_by' => $request->user()->id,
                        'updated_at' => now(),
                        'updated_by' => $request->user()->id,
                    ]);
                }
            }

            // Commit transaction if everything is successful
            DB::commit();

            return response()->json([
                'success' => true,
                'message' => 'Job Tracker created successfully',
                'data' => $jobTracker->fresh() // Fresh instance to get latest data
            ], 201);
        } catch (Exception $e) {
            // Rollback transaction on any error
            DB::rollBack();

            // Log the error for debugging
            Log::error('Job Tracker creation failed', [
                'error' => $e->getMessage(),
                'trace' => $e->getTraceAsString(),
                'request_data' => $request->all()
            ]);

            return response()->json([
                'success' => false,
                'message' => 'Failed to create Job Tracker',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    public function getJobTracker(Request $request, $id)
    {
        // Fetch the job tracker with related models
        $jobTracker = JobTrackerModel::with([
            'updatedBy',
            'jobDescriptions',
            'maxDepths',
            'n2Tanks',
            'containers',
            'injectorGoosnecks',
            'miscellaneousTools',
            'ctPersonnels',
            'nitrogenPersonnels',
            'pumpPersonnels',
            'acidTypes',
            'acidVolumes',
        ])->findOrFail($id);

        // Transform the job tracker data dan ubah propertinya
        $jobTracker->jobDescriptions->transform(function ($description) {
            return $description->description;
        });

        $jobTracker->maxDepths->transform(function ($maxDepth) {
            return [
                'value' => $maxDepth->max_depth,
                'unit' => $maxDepth->max_depth_unit,
            ];
        });

        $jobTracker->casing_liner_size = [
            'size' => number_format(floatval($jobTracker->casing_liner_size), 2),
            'unit' => $jobTracker->casing_liner_size_unit,
        ];

        $jobTracker->completion_size = [
            'size' => number_format(floatval($jobTracker->completion_size), 2),
            'unit' => $jobTracker->completion_size_unit,
        ];

        $jobTracker->max_bha_od = [
            'size' => number_format(floatval($jobTracker->max_bha_od), 2),
            'unit' => $jobTracker->max_bha_od_unit,
        ];

        $jobTracker->ct_size = [
            'size' => number_format(floatval($jobTracker->ct_size), 2),
            'unit' => $jobTracker->ct_size_unit,
        ];

        $jobTracker->wt = [
            'size' => number_format(floatval($jobTracker->wt), 2),
            'unit' => $jobTracker->wt_unit,
        ];

        $jobTracker->n2Tanks->transform(function ($tank) {
            return $tank->n2_tank_name;
        });

        $jobTracker->containers->transform(function ($container) {
            return $container->container_name;
        });

        $jobTracker->injectorGoosnecks->transform(function ($injectorGoosneck) {
            return $injectorGoosneck->injector_goosneck_name;
        });

        $jobTracker->miscellaneousTools->transform(function ($miscellaneousTool) {
            return $miscellaneousTool->miscellaneous_tool_name;
        });

        $jobTracker->ctPersonnels->transform(function ($ctPersonnel) {
            return $ctPersonnel->ct_personnel_name;
        });

        $jobTracker->nitrogenPersonnels->transform(function ($nitrogenPersonnel) {
            return $nitrogenPersonnel->nitrogen_personnel_name;
        });

        $jobTracker->pumpPersonnels->transform(function ($pumpPersonnel) {
            return $pumpPersonnel->pump_personnel_name;
        });

        $jobTracker->acidTypes->transform(function ($acidType) {
            return [
                'value' => $acidType->acid_type,
            ];
        });

        $jobTracker->acidVolumes->transform(function ($acidVolume) {
            return [
                'value' => number_format(floatval($acidVolume->volume), 2),
                'unit' => $acidVolume->volume_unit,
            ];
        });

        $jobTracker->max_deviation = number_format(floatval($jobTracker->max_deviation), 2);
        $jobTracker->depth_md = number_format(floatval($jobTracker->depth_md), 2);
        $jobTracker->depth_tvd = number_format(floatval($jobTracker->depth_tvd), 2);
        $jobTracker->bh_pressure = number_format(floatval($jobTracker->bh_pressure), 2);
        $jobTracker->bh_temp = number_format(floatval($jobTracker->bh_temp), 2);
        $jobTracker->nitrogen_volume = number_format(floatval($jobTracker->nitrogen_volume), 2);
        $jobTracker->cement_volume = number_format(floatval($jobTracker->cement_volume), 2);
        $jobTracker->revenue_coiled_tubing = number_format(floatval($jobTracker->revenue_coiled_tubing), 2);
        $jobTracker->revenue_pumping = number_format(floatval($jobTracker->revenue_pumping), 2);
        $jobTracker->revenue_special_tools = number_format(floatval($jobTracker->revenue_special_tools), 2);
        $jobTracker->revenue_acid = number_format(floatval($jobTracker->revenue_acid), 2);
        $jobTracker->revenue_nitrogen_equipment = number_format(floatval($jobTracker->revenue_nitrogen_equipment), 2);
        $jobTracker->revenue_nitrogen_product = number_format(floatval($jobTracker->revenue_nitrogen_product), 2);
        $jobTracker->revenue_cement = number_format(floatval($jobTracker->revenue_cement), 2);
        $jobTracker->personnel_charges = number_format(floatval($jobTracker->personnel_charges), 2);
        $jobTracker->service_charges = number_format(floatval($jobTracker->service_charges), 2);
        $jobTracker->other_charges = number_format(floatval($jobTracker->other_charges), 2);
        $jobTracker->total_revenue = number_format(floatval($jobTracker->total_revenue), 2);

        // dd($jobTracker);
        return response()->json($jobTracker, 200);
    }

    public function updateJobTracker(Request $request, $id)
    {
        try {
            DB::beginTransaction();

            $jobTracker = JobTrackerModel::findOrFail($id);
            // Update main data
            $data = [
                'well_name' => $request->well_name,
                'company_man' => $request->company_man,
                'bj_representative' => $request->bj_representative,
                'job_start_date' => $request->job_start_date,
                'job_finish_date' => $request->job_finish_date,
                'job_days' => $request->job_days,
                'max_deviation' => $request->max_deviation,
                'customer' => $request->customer,
                'bj_district' => $request->bj_district,
                'field_location' => $request->field_location,
                'casing_liner_size' => $request->casing_liner_size['size'] ?? 0,
                'casing_liner_size_unit' => $request->casing_liner_size['unit'] ?? null,
                'max_bha_od' => $request->max_bha_od['size'] ?? 0,
                'max_bha_od_unit' => $request->max_bha_od['unit'] ?? 0,
                'completion_size' => $request->completion_size['size'] ?? 0,
                'completion_size_unit' => $request->completion_size['unit'] ?? null,
                'field_type' => $request->field_type,
                'wellhead_x_over' => $request->wellhead_x_over,
                'well_status' => $request->well_status,
                'well_type' => $request->well_type,
                'nozzle_type' => $request->nozzle_type,
                'control_cabin' => $request->control_cabin,
                'power_pack' => $request->power_pack,
                'power_reel' => $request->power_reel,
                'cj_injector' => $request->cj_injector,
                'bop' => $request->bop,
                'ct_size' => $request->ct_size['size'] ?? 0,
                'ct_size_unit' => $request->ct_size['unit'] ?? null,
                'ct_grade' => $request->ct_grade,
                'wt' => $request->wt['size'] ?? 0,
                'wt_unit' => $request->wt['unit'],
                'ct_string' => $request->ct_string,
                'n2_converter' => $request->n2_converter,
                'ct_supervisor' => $request->ct_supervisor,
                'nitrogen_supervisor' => $request->nitrogen_supervisor,
                'pump_supervisor' => $request->pump_supervisor,
                'depth_md' => $request->depth_md,
                'depth_md_unit' => $request->depth_md_unit,
                'depth_tvd' => $request->depth_tvd,
                'depth_tvd_unit' => $request->depth_tvd_unit,
                'bh_pressure' => $request->bh_pressure,
                'bh_pressure_unit' => $request->bh_pressure_unit,
                'bh_temp' => $request->bh_temp,
                'bh_temp_unit' => $request->bh_temp_unit,
                'nitrogen_volume' => $request->nitrogen_volume,
                'nitrogen_volume_unit' => $request->nitrogen_volume_unit,
                'cement_volume' => $request->cement_volume,
                'cement_volume_unit' => $request->cement_volume_unit,
                'revenue_currency' => $request->revenue_currency,
                'revenue_coiled_tubing' => floatval($request->revenue_coiled_tubing),
                'revenue_pumping' => floatval($request->revenue_pumping),
                'revenue_special_tools' => floatval($request->revenue_special_tools),
                'revenue_acid' => floatval($request->revenue_acid),
                'revenue_nitrogen_equipment' => floatval($request->revenue_nitrogen_equipment),
                'revenue_nitrogen_product' => floatval($request->revenue_nitrogen_product),
                'revenue_cement' => floatval($request->revenue_cement),
                'personnel_charges' => floatval($request->personnel_charges),
                'service_charges' => floatval($request->service_charges),
                'other_charges' => floatval($request->other_charges),
                'total_revenue' => floatval($request->total_revenue),
                'updated_at' => now(),
                'updated_by' => $request->user()->id,
            ];

            // dd($data);

            $jobTracker->update($data);

            // Clear all related data (bisa diganti jadi selective update kalau mau lebih optimal)
            $jobTracker->jobDescriptions()->delete();
            $jobTracker->maxDepths()->delete();
            $jobTracker->n2Tanks()->delete();
            $jobTracker->containers()->delete();
            $jobTracker->injectorGoosnecks()->delete();
            $jobTracker->miscellaneousTools()->delete();
            $jobTracker->ctPersonnels()->delete();
            $jobTracker->nitrogenPersonnels()->delete();
            $jobTracker->pumpPersonnels()->delete();
            $jobTracker->acidTypes()->delete();
            $jobTracker->acidVolumes()->delete();

            // Lanjutkan dengan insert ulang data terkait seperti di storeJobTracker
            // Handle job descriptions
            if ($request->has('job_descriptions') && is_array($request->job_descriptions)) {
                foreach ($request->job_descriptions as $description) {
                    JobTrackerJobDescriptionModel::create([
                        'job_tracker_id' => $jobTracker->id,
                        'description' => $description,
                        'created_at' => now(),
                        'created_by' => $request->user()->id,
                        'updated_at' => now(),
                        'updated_by' => $request->user()->id,
                    ]);
                }
            }

            // Handle max depths
            if ($request->has('max_depths') && is_array($request->max_depths)) {
                foreach ($request->max_depths as $maxDepth) {
                    if (isset($maxDepth['value']) && isset($maxDepth['unit'])) {
                        JobTrackerMaxDepthModel::create([
                            'job_tracker_id' => $jobTracker->id,
                            'max_depth' => $maxDepth['value'] ?? 0,
                            'max_depth_unit' => $maxDepth['unit'] ?? null,
                            'created_at' => now(),
                            'created_by' => $request->user()->id,
                            'updated_at' => now(),
                            'updated_by' => $request->user()->id,
                        ]);
                    }
                }
            }

            // Handle N2 Tanks (array)
            if ($request->has('n2_tanks') && is_array($request->n2_tanks)) {
                foreach ($request->n2_tanks as $tank_name) {
                    JobTrackerN2TankModel::create([
                        'job_tracker_id' => $jobTracker->id,
                        'n2_tank_name' => $tank_name,
                        'created_at' => now(),
                        'created_by' => $request->user()->id,
                        'updated_at' => now(),
                        'updated_by' => $request->user()->id,
                    ]);
                }
            }

            // Handle Containers (array)
            if ($request->has('containers') && is_array($request->containers)) {
                foreach ($request->containers as $container_name) {
                    JobTrackerContainerModel::create([
                        'job_tracker_id' => $jobTracker->id,
                        'container_name' => $container_name,
                        'created_at' => now(),
                        'created_by' => $request->user()->id,
                        'updated_at' => now(),
                        'updated_by' => $request->user()->id,
                    ]);
                }
            }

            // Handle Injector Goosnecks (array)
            if ($request->has('injector_goosnecks') && is_array($request->injector_goosnecks)) {
                foreach ($request->injector_goosnecks as $injector_goosneck_name) {
                    JobTrackerInjectorGoosneckModel::create([
                        'job_tracker_id' => $jobTracker->id,
                        'injector_goosneck_name' => $injector_goosneck_name,
                        'created_at' => now(),
                        'created_by' => $request->user()->id,
                        'updated_at' => now(),
                        'updated_by' => $request->user()->id,
                    ]);
                }
            }

            // Handle Miscellaneous Tools (array)
            if ($request->has('miscellaneous_tools') && is_array($request->miscellaneous_tools)) {
                foreach ($request->miscellaneous_tools as $miscellaneous_tool_name) {
                    JobTrackerMiscellaneousToolModel::create([
                        'job_tracker_id' => $jobTracker->id,
                        'miscellaneous_tool_name' => $miscellaneous_tool_name,
                        'created_at' => now(),
                        'created_by' => $request->user()->id,
                        'updated_at' => now(),
                        'updated_by' => $request->user()->id,
                    ]);
                }
            }

            // Handle CT Personnel (array)
            if ($request->has('ct_personnels') && is_array($request->ct_personnels)) {
                foreach ($request->ct_personnels as $ct_personnel_name) {
                    JobTrackerCTPersonnelModel::create([
                        'job_tracker_id' => $jobTracker->id,
                        'ct_personnel_name' => $ct_personnel_name,
                        'created_at' => now(),
                        'created_by' => $request->user()->id,
                        'updated_at' => now(),
                        'updated_by' => $request->user()->id,
                    ]);
                }
            }

            // Handle Nitrogen Personnels (array)
            if ($request->has('nitrogen_personnels') && is_array($request->nitrogen_personnels)) {
                foreach ($request->nitrogen_personnels as $nitrogen_personnel_name) {
                    JobTrackerNitrogenPersonnelModel::create([
                        'job_tracker_id' => $jobTracker->id,
                        'nitrogen_personnel_name' => $nitrogen_personnel_name,
                        'created_at' => now(),
                        'created_by' => $request->user()->id,
                        'updated_at' => now(),
                        'updated_by' => $request->user()->id,
                    ]);
                }
            }

            // Handle Pump Personnels (array)
            if ($request->has('pump_personnels') && is_array($request->pump_personnels)) {
                foreach ($request->pump_personnels as $pump_personnel_name) {
                    JobTrackerPumpPersonnelModel::create([
                        'job_tracker_id' => $jobTracker->id,
                        'pump_personnel_name' => $pump_personnel_name,
                        'created_at' => now(),
                        'created_by' => $request->user()->id,
                        'updated_at' => now(),
                        'updated_by' => $request->user()->id,
                    ]);
                }
            }

            // Handle Acid Types (array)
            if ($request->has('acid_types') && is_array($request->acid_types)) {
                foreach ($request->acid_types as $acid_type) {
                    JobTrackerAcidTypeModel::create([
                        'job_tracker_id' => $jobTracker->id,
                        'acid_type' => $acid_type['value'] ?? 0,
                        'created_at' => now(),
                        'created_by' => $request->user()->id,
                        'updated_at' => now(),
                        'updated_by' => $request->user()->id,
                    ]);
                }
            }

            // Handle Acid Volume (array)
            if ($request->has('acid_volumes') && is_array($request->acid_volumes)) {
                foreach ($request->acid_volumes as $acid_volume) {
                    JobTrackerAcidVolumeModel::create([
                        'job_tracker_id' => $jobTracker->id,
                        'volume' => $acid_volume['value'] ?? 0,
                        'volume_unit' => $acid_volume['unit'] ?? null,
                        'created_at' => now(),
                        'created_by' => $request->user()->id,
                        'updated_at' => now(),
                        'updated_by' => $request->user()->id,
                    ]);
                }
            }

            DB::commit();

            return response()->json([
                'success' => true,
                'message' => 'Job Tracker updated successfully',
                'data' => $jobTracker->fresh()
            ]);
        } catch (Exception $e) {
            DB::rollBack();
            Log::error('Job Tracker update failed', [
                'error' => $e->getMessage(),
                'trace' => $e->getTraceAsString(),
                'request_data' => $request->all()
            ]);

            return response()->json([
                'success' => false,
                'message' => 'Failed to update Job Tracker',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    public function deleteJobTracker(Request $request)
    {
        try {
            DB::beginTransaction();

            $ids = $request->input('ids', []);
            if (empty($ids)) {
                return response()->json([
                    'success' => false,
                    'message' => 'No Job Tracker IDs provided for deletion',
                ], 400);
            }

            // Delete job trackers and their related data
            JobTrackerModel::whereIn('id', $ids)->each(function ($jobTracker) {
                // Delete related models
                $jobTracker->jobDescriptions()->delete();
                $jobTracker->maxDepths()->delete();
                $jobTracker->n2Tanks()->delete();
                $jobTracker->containers()->delete();
                $jobTracker->injectorGoosnecks()->delete();
                $jobTracker->miscellaneousTools()->delete();
                $jobTracker->ctPersonnels()->delete();
                $jobTracker->nitrogenPersonnels()->delete();
                $jobTracker->pumpPersonnels()->delete();
                $jobTracker->acidTypes()->delete();
                $jobTracker->acidVolumes()->delete();

                // Finally delete the job tracker itself
                $jobTracker->delete();
            });
            
            // Commit transaction if everything is successful
            DB::commit();

            return response()->json([
                'success' => true,
                'message' => 'Job Tracker deleted successfully',
            ], 200);
        } catch (Exception $e) {
            DB::rollBack();
            Log::error('Job Tracker deletion failed', [
                'error' => $e->getMessage(),
                'trace' => $e->getTraceAsString(),
                'request_data' => $request->all()
            ]);

            return response()->json([
                'success' => false,
                'message' => 'Failed to delete Job Tracker',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    public function getJobDescriptions(Request $request)
    {
        // return with no pagination
        $jobDescriptions = JobDescriptionModel::with('updatedBy')
            ->get()
            ->map(function ($jobDescription) {
                return [
                    'id' => $jobDescription->id,
                    'description' => $jobDescription->description,
                    'created_by' => $jobDescription->created_by,
                    'updated_by_name' => $jobDescription->updatedBy ? $jobDescription->updatedBy->fullname : null,
                ];
            });
        return response()->json($jobDescriptions, 200);
    }

    public function storeJobDescription(Request $request)
    {
        $request->validate([
            'description' => 'required|string|max:255',
        ]);

        $jobDescription = JobDescriptionModel::create([
            'description' => $request->input('description'),
            'created_at' => now(),
            'created_by' => $request->user()->id,
            'updated_at' => now(),
            'updated_by' => $request->user()->id,
        ]);

        return response()->json([
            'message' => 'Job description created successfully.',
            'data' => $jobDescription,
        ], 201);
    }

    public function updateJobDescription(Request $request, $id)
    {
        $request->validate([
            'description' => 'required|string|max:255',
        ]);

        $jobDescription = JobDescriptionModel::findOrFail($id);
        $jobDescription->update([
            'description' => $request->input('description'),
            'updated_at' => now(),
            'updated_by' => $request->user()->id,
        ]);

        return response()->json([
            'message' => 'Job description updated successfully.',
            'data' => $jobDescription,
        ], 200);
    }

    public function deleteJobDescription(Request $request, $id)
    {
        $jobDescription = JobDescriptionModel::findOrFail($id);
        $jobDescription->delete();

        return response()->json([
            'message' => 'Job description deleted successfully.',
        ], 200);
    }

    public function getCustomers(Request $request)
    {
        // Assuming you have a Customer model
        $customers = CustomerModel::select('id', 'customer_name')
            ->orderBy('customer_name')
            ->get();

        return response()->json($customers, 200);
    }

    public function storeCustomer(Request $request)
    {
        $request->validate([
            'customer_name' => 'required|string|max:255',
        ]);

        $customer = CustomerModel::create([
            'customer_name' => $request->input('customer_name'),
            'created_at' => now(),
            'created_by' => $request->user()->id,
            'updated_at' => now(),
            'updated_by' => $request->user()->id,
        ]);

        return response()->json([
            'message' => 'Customer created successfully.',
            'data' => $customer,
        ], 201);
    }

    public function updateCustomer(Request $request, $id)
    {
        $request->validate([
            'customer_name' => 'required|string|max:255',
        ]);

        $customer = CustomerModel::findOrFail($id);
        $customer->update([
            'customer_name' => $request->input('customer_name'),
            'updated_at' => now(),
            'updated_by' => $request->user()->id,
        ]);

        return response()->json([
            'message' => 'Customer updated successfully.',
            'data' => $customer,
        ], 200);
    }

    public function deleteCustomer(Request $request, $id)
    {
        $customer = CustomerModel::findOrFail($id);
        $customer->delete();

        return response()->json([
            'message' => 'Customer deleted successfully.',
        ], 200);
    }

    public function getBJDistricts(Request $request)
    {
        // Assuming you have a BJDistrict model
        $districts = BJDistrictModel::select('id', 'district_name')
            ->orderBy('district_name')
            ->get();

        return response()->json($districts, 200);
    }

    public function storeBJDistrict(Request $request)
    {
        $request->validate([
            'district_name' => 'required|string|max:255',
        ]);

        $district = BJDistrictModel::create([
            'district_name' => $request->input('district_name'),
            'created_at' => now(),
            'created_by' => $request->user()->id,
            'updated_at' => now(),
            'updated_by' => $request->user()->id,
        ]);

        return response()->json([
            'message' => 'BJ District created successfully.',
            'data' => $district,
        ], 201);
    }

    public function updateBJDistrict(Request $request, $id)
    {
        $request->validate([
            'district_name' => 'required|string|max:255',
        ]);

        $district = BJDistrictModel::findOrFail($id);
        $district->update([
            'district_name' => $request->input('district_name'),
            'updated_at' => now(),
            'updated_by' => $request->user()->id,
        ]);

        return response()->json([
            'message' => 'BJ District updated successfully.',
            'data' => $district,
        ], 200);
    }

    public function deleteBJDistrict(Request $request, $id)
    {
        $district = BJDistrictModel::findOrFail($id);
        $district->delete();

        return response()->json([
            'message' => 'BJ District deleted successfully.',
        ], 200);
    }

    public function getFieldLocations(Request $request)
    {
        // Assuming you have a FieldLocation model
        $fieldLocations = FieldLocationModel::select('id', 'location_name')
            ->orderBy('location_name')
            ->get();

        return response()->json($fieldLocations, 200);
    }

    public function storeFieldLocation(Request $request)
    {
        $request->validate([
            'location_name' => 'required|string|max:255',
        ]);

        $fieldLocation = FieldLocationModel::create([
            'location_name' => $request->input('location_name'),
            'created_at' => now(),
            'created_by' => $request->user()->id,
            'updated_at' => now(),
            'updated_by' => $request->user()->id,
        ]);

        return response()->json([
            'message' => 'Field location created successfully.',
            'data' => $fieldLocation,
        ], 201);
    }

    public function updateFieldLocation(Request $request, $id)
    {
        $request->validate([
            'location_name' => 'required|string|max:255',
        ]);

        $fieldLocation = FieldLocationModel::findOrFail($id);
        $fieldLocation->update([
            'location_name' => $request->input('location_name'),
            'updated_at' => now(),
            'updated_by' => $request->user()->id,
        ]);

        return response()->json([
            'message' => 'Field location updated successfully.',
            'data' => $fieldLocation,
        ], 200);
    }

    public function deleteFieldLocation(Request $request, $id)
    {
        $fieldLocation = FieldLocationModel::findOrFail($id);
        $fieldLocation->delete();

        return response()->json([
            'message' => 'Field location deleted successfully.',
        ], 200);
    }

    public function getFieldTypes(Request $request)
    {
        // Assuming you have a FieldTypeModel
        $fieldTypes = FieldTypeModel::select('id', 'type_name')
            ->orderBy('type_name')
            ->get();

        return response()->json($fieldTypes, 200);
    }

    public function storeFieldType(Request $request)
    {
        $request->validate([
            'type_name' => 'required|string|max:255',
        ]);

        $fieldType = FieldTypeModel::create([
            'type_name' => $request->input('type_name'),
            'created_at' => now(),
            'created_by' => $request->user()->id,
            'updated_at' => now(),
            'updated_by' => $request->user()->id,
        ]);

        return response()->json([
            'message' => 'Field type created successfully.',
            'data' => $fieldType,
        ], 201);
    }

    public function updateFieldType(Request $request, $id)
    {
        $request->validate([
            'type_name' => 'required|string|max:255',
        ]);

        $fieldType = FieldTypeModel::findOrFail($id);
        $fieldType->update([
            'type_name' => $request->input('type_name'),
            'updated_at' => now(),
            'updated_by' => $request->user()->id,
        ]);

        return response()->json([
            'message' => 'Field type updated successfully.',
            'data' => $fieldType,
        ], 200);
    }

    public function deleteFieldType(Request $request, $id)
    {
        $fieldType = FieldTypeModel::findOrFail($id);
        $fieldType->delete();

        return response()->json([
            'message' => 'Field type deleted successfully.',
        ], 200);
    }

    public function getWellStatuses(Request $request)
    {
        // Assuming you have a WellStatusModel
        $wellStatuses = WellStatusModel::select('id', 'status_name')
            ->orderBy('status_name')
            ->get();

        return response()->json($wellStatuses, 200);
    }

    public function storeWellStatus(Request $request)
    {
        $request->validate([
            'status_name' => 'required|string|max:255',
        ]);

        $wellStatus = WellStatusModel::create([
            'status_name' => $request->input('status_name'),
            'created_at' => now(),
            'created_by' => $request->user()->id,
            'updated_at' => now(),
            'updated_by' => $request->user()->id,
        ]);

        return response()->json([
            'message' => 'Well status created successfully.',
            'data' => $wellStatus,
        ], 201);
    }

    public function updateWellStatus(Request $request, $id)
    {
        $request->validate([
            'status_name' => 'required|string|max:255',
        ]);

        $wellStatus = WellStatusModel::findOrFail($id);
        $wellStatus->update([
            'status_name' => $request->input('status_name'),
            'updated_at' => now(),
            'updated_by' => $request->user()->id,
        ]);

        return response()->json([
            'message' => 'Well status updated successfully.',
            'data' => $wellStatus,
        ], 200);
    }

    public function deleteWellStatus(Request $request, $id)
    {
        $wellStatus = WellStatusModel::findOrFail($id);
        $wellStatus->delete();

        return response()->json([
            'message' => 'Well status deleted successfully.',
        ], 200);
    }

    public function getWellTypes(Request $request)
    {
        // Assuming you have a WellTypeModel
        $wellTypes = WellTypeModel::select('id', 'type_name')
            ->orderBy('type_name')
            ->get();

        return response()->json($wellTypes, 200);
    }

    public function storeWellType(Request $request)
    {
        $request->validate([
            'type_name' => 'required|string|max:255',
        ]);

        $wellType = WellTypeModel::create([
            'type_name' => $request->input('type_name'),
            'created_at' => now(),
            'created_by' => $request->user()->id,
            'updated_at' => now(),
            'updated_by' => $request->user()->id,
        ]);

        return response()->json([
            'message' => 'Well type created successfully.',
            'data' => $wellType,
        ], 201);
    }

    public function updateWellType(Request $request, $id)
    {
        $request->validate([
            'type_name' => 'required|string|max:255',
        ]);

        $wellType = WellTypeModel::findOrFail($id);
        $wellType->update([
            'type_name' => $request->input('type_name'),
            'updated_at' => now(),
            'updated_by' => $request->user()->id,
        ]);

        return response()->json([
            'message' => 'Well type updated successfully.',
            'data' => $wellType,
        ], 200);
    }

    public function deleteWellType(Request $request, $id)
    {
        $wellType = WellTypeModel::findOrFail($id);
        $wellType->delete();

        return response()->json([
            'message' => 'Well type deleted successfully.',
        ], 200);
    }

    public function getWellheadXOvers(Request $request)
    {
        // Assuming you have a WellheadXOvershootModel
        $wellheadxovers = WellheadXOverModel::select('id', 'wellhead_name')
            ->orderBy('wellhead_name')
            ->get();

        return response()->json($wellheadxovers, 200);
    }

    public function storeWellheadXOver(Request $request)
    {
        $request->validate([
            'wellhead_name' => 'required|string|max:255',
        ]);

        $wellheadxover = WellheadXOverModel::create([
            'wellhead_name' => $request->input('wellhead_name'),
            'created_at' => now(),
            'created_by' => $request->user()->id,
            'updated_at' => now(),
            'updated_by' => $request->user()->id,
        ]);

        return response()->json([
            'message' => 'Wellhead XOver created successfully.',
            'data' => $wellheadxover,
        ], 201);
    }

    public function updateWellheadXOver(Request $request, $id)
    {
        $request->validate([
            'wellhead_name' => 'required|string|max:255',
        ]);

        $wellheadxover = WellheadXOverModel::findOrFail($id);
        $wellheadxover->update([
            'wellhead_name' => $request->input('wellhead_name'),
            'updated_at' => now(),
            'updated_by' => $request->user()->id,
        ]);

        return response()->json([
            'message' => 'Wellhead XOver updated successfully.',
            'data' => $wellheadxover,
        ], 200);
    }

    public function deleteWellheadXOver(Request $request, $id)
    {
        $wellheadxover = WellheadXOverModel::findOrFail($id);
        $wellheadxover->delete();

        return response()->json([
            'message' => 'Wellhead XOver deleted successfully.',
        ], 200);
    }

    public function getCasingLinerSizes(Request $request)
    {
        // Assuming you have a CasingLinerSizeModel
        $casingLinerSizes = CasingLinerSizeModel::select('id', 'size')
            ->orderBy('size')
            ->get();

        return response()->json($casingLinerSizes, 200);
    }

    public function storeCasingLinerSize(Request $request)
    {
        $request->validate([
            'size' => 'required|string|max:255',
        ]);

        $casingLinerSize = CasingLinerSizeModel::create([
            'size' => $request->input('size'),
            'created_at' => now(),
            'created_by' => $request->user()->id,
            'updated_at' => now(),
            'updated_by' => $request->user()->id,
        ]);

        return response()->json([
            'message' => 'Casing Liner Size created successfully.',
            'data' => $casingLinerSize,
        ], 201);
    }

    public function updateCasingLinerSize(Request $request, $id)
    {
        $request->validate([
            'size' => 'required|string|max:255',
        ]);

        $casingLinerSize = CasingLinerSizeModel::findOrFail($id);
        $casingLinerSize->update([
            'size' => $request->input('size'),
            'updated_at' => now(),
            'updated_by' => $request->user()->id,
        ]);

        return response()->json([
            'message' => 'Casing Liner Size updated successfully.',
            'data' => $casingLinerSize,
        ], 200);
    }

    public function deleteCasingLinerSize(Request $request, $id)
    {
        $casingLinerSize = CasingLinerSizeModel::findOrFail($id);
        $casingLinerSize->delete();

        return response()->json([
            'message' => 'Casing Liner Size deleted successfully.',
        ], 200);
    }

    public function getCompletionSizes(Request $request)
    {
        // Assuming you have a CompletionSizeModel
        $completionSizes = CompletionSizeModel::select('id', 'size')
            ->orderBy('size')
            ->get();

        return response()->json($completionSizes, 200);
    }

    public function storeCompletionSize(Request $request)
    {
        $request->validate([
            'size' => 'required|string|max:255',
        ]);

        $completionSize = CompletionSizeModel::create([
            'size' => $request->input('size'),
            'created_at' => now(),
            'created_by' => $request->user()->id,
            'updated_at' => now(),
            'updated_by' => $request->user()->id,
        ]);

        return response()->json([
            'message' => 'Completion Size created successfully.',
            'data' => $completionSize,
        ], 201);
    }

    public function updateCompletionSize(Request $request, $id)
    {
        $request->validate([
            'size' => 'required|string|max:255',
        ]);

        $completionSize = CompletionSizeModel::findOrFail($id);
        $completionSize->update([
            'size' => $request->input('size'),
            'updated_at' => now(),
            'updated_by' => $request->user()->id,
        ]);

        return response()->json([
            'message' => 'Completion Size updated successfully.',
            'data' => $completionSize,
        ], 200);
    }

    public function deleteCompletionSize(Request $request, $id)
    {
        $completionSize = CompletionSizeModel::findOrFail($id);
        $completionSize->delete();

        return response()->json([
            'message' => 'Completion Size deleted successfully.',
        ], 200);
    }

    public function getNozzleTypes(Request $request)
    {
        // Assuming you have a NozzleTypeModel
        $nozzleTypes = NozzleTypeModel::select('id', 'type_name')
            ->orderBy('type_name')
            ->get();

        return response()->json($nozzleTypes, 200);
    }

    public function storeNozzleType(Request $request)
    {
        $request->validate([
            'type_name' => 'required|string|max:255',
        ]);

        $nozzleType = NozzleTypeModel::create([
            'type_name' => $request->input('type_name'),
            'created_at' => now(),
            'created_by' => $request->user()->id,
            'updated_at' => now(),
            'updated_by' => $request->user()->id,
        ]);

        return response()->json([
            'message' => 'Nozzle Type created successfully.',
            'data' => $nozzleType,
        ], 201);
    }

    public function updateNozzleType(Request $request, $id)
    {
        $request->validate([
            'type_name' => 'required|string|max:255',
        ]);

        $nozzleType = NozzleTypeModel::findOrFail($id);
        $nozzleType->update([
            'type_name' => $request->input('type_name'),
            'updated_at' => now(),
            'updated_by' => $request->user()->id,
        ]);

        return response()->json([
            'message' => 'Nozzle Type updated successfully.',
            'data' => $nozzleType,
        ], 200);
    }

    public function deleteNozzleType(Request $request, $id)
    {
        $nozzleType = NozzleTypeModel::findOrFail($id);
        $nozzleType->delete();

        return response()->json([
            'message' => 'Nozzle Type deleted successfully.',
        ], 200);
    }

    public function getMaxBHAODs(Request $request)
    {
        // Assuming you have a MaxBHAODModel
        $maxBHAODs = MaxBHAODModel::select('id', 'size')
            ->orderBy('size')
            ->get();

        return response()->json($maxBHAODs, 200);
    }

    public function storeMaxBHAOD(Request $request)
    {
        $request->validate([
            'size' => 'required|string|max:255',
        ]);

        $maxBHAOD = MaxBHAODModel::create([
            'size' => $request->input('size'),
            'created_at' => now(),
            'created_by' => $request->user()->id,
            'updated_at' => now(),
            'updated_by' => $request->user()->id,
        ]);

        return response()->json([
            'message' => 'Max BHA OD created successfully.',
            'data' => $maxBHAOD,
        ], 201);
    }

    public function updateMaxBHAOD(Request $request, $id)
    {
        $request->validate([
            'size' => 'required|string|max:255',
        ]);

        $maxBHAOD = MaxBHAODModel::findOrFail($id);
        $maxBHAOD->update([
            'size' => $request->input('size'),
            'updated_at' => now(),
            'updated_by' => $request->user()->id,
        ]);

        return response()->json([
            'message' => 'Max BHA OD updated successfully.',
            'data' => $maxBHAOD,
        ], 200);
    }

    public function deleteMaxBHAOD(Request $request, $id)
    {
        $maxBHAOD = MaxBHAODModel::findOrFail($id);
        $maxBHAOD->delete();

        return response()->json([
            'message' => 'Max BHA OD deleted successfully.',
        ], 200);
    }

    public function getControlCabins(Request $request)
    {
        // Assuming you have a ControlCabinModel
        $controlCabins = ControlCabinModel::select('id', 'cabin_name')
            ->orderBy('cabin_name')
            ->get();

        return response()->json($controlCabins, 200);
    }

    public function storeControlCabin(Request $request)
    {
        $request->validate([
            'cabin_name' => 'required|string|max:255',
        ]);

        $controlCabin = ControlCabinModel::create([
            'cabin_name' => $request->input('cabin_name'),
            'created_at' => now(),
            'created_by' => $request->user()->id,
            'updated_at' => now(),
            'updated_by' => $request->user()->id,
        ]);

        return response()->json([
            'message' => 'Control Cabin created successfully.',
            'data' => $controlCabin,
        ], 201);
    }

    public function updateControlCabin(Request $request, $id)
    {
        $request->validate([
            'cabin_name' => 'required|string|max:255',
        ]);

        $controlCabin = ControlCabinModel::findOrFail($id);
        $controlCabin->update([
            'cabin_name' => $request->input('cabin_name'),
            'updated_at' => now(),
            'updated_by' => $request->user()->id,
        ]);

        return response()->json([
            'message' => 'Control Cabin updated successfully.',
            'data' => $controlCabin,
        ], 200);
    }

    public function deleteControlCabin(Request $request, $id)
    {
        $controlCabin = ControlCabinModel::findOrFail($id);
        $controlCabin->delete();

        return response()->json([
            'message' => 'Control Cabin deleted successfully.',
        ], 200);
    }

    public function getPowerPacks(Request $request)
    {
        // Assuming you have a PowerPackModel
        $powerPacks = PowerPackModel::select('id', 'power_pack_name')
            ->orderBy('power_pack_name')
            ->get();

        return response()->json($powerPacks, 200);
    }

    public function storePowerPack(Request $request)
    {
        $request->validate([
            'power_pack_name' => 'required|string|max:255',
        ]);

        $powerPack = PowerPackModel::create([
            'power_pack_name' => $request->input('power_pack_name'),
            'created_at' => now(),
            'created_by' => $request->user()->id,
            'updated_at' => now(),
            'updated_by' => $request->user()->id,
        ]);

        return response()->json([
            'message' => 'Power Pack created successfully.',
            'data' => $powerPack,
        ], 201);
    }

    public function updatePowerPack(Request $request, $id)
    {
        $request->validate([
            'power_pack_name' => 'required|string|max:255',
        ]);

        $powerPack = PowerPackModel::findOrFail($id);
        $powerPack->update([
            'power_pack_name' => $request->input('power_pack_name'),
            'updated_at' => now(),
            'updated_by' => $request->user()->id,
        ]);

        return response()->json([
            'message' => 'Power Pack updated successfully.',
            'data' => $powerPack,
        ], 200);
    }

    public function deletePowerPack(Request $request, $id)
    {
        $powerPack = PowerPackModel::findOrFail($id);
        $powerPack->delete();

        return response()->json([
            'message' => 'Power Pack deleted successfully.',
        ], 200);
    }

    public function getPowerReels(Request $request)
    {
        // Assuming you have a PowerReelModel
        $powerReels = PowerReelModel::select('id', 'power_reel_name')
            ->orderBy('power_reel_name')
            ->get();

        return response()->json($powerReels, 200);
    }

    public function storePowerReel(Request $request)
    {
        $request->validate([
            'power_reel_name' => 'required|string|max:255',
        ]);

        $powerReel = PowerReelModel::create([
            'power_reel_name' => $request->input('power_reel_name'),
            'created_at' => now(),
            'created_by' => $request->user()->id,
            'updated_at' => now(),
            'updated_by' => $request->user()->id,
        ]);

        return response()->json([
            'message' => 'Power Reel created successfully.',
            'data' => $powerReel,
        ], 201);
    }

    public function updatePowerReel(Request $request, $id)
    {
        $request->validate([
            'power_reel_name' => 'required|string|max:255',
        ]);

        $powerReel = PowerReelModel::findOrFail($id);
        $powerReel->update([
            'power_reel_name' => $request->input('power_reel_name'),
            'updated_at' => now(),
            'updated_by' => $request->user()->id,
        ]);

        return response()->json([
            'message' => 'Power Reel updated successfully.',
            'data' => $powerReel,
        ], 200);
    }

    public function deletePowerReel(Request $request, $id)
    {
        $powerReel = PowerReelModel::findOrFail($id);
        $powerReel->delete();

        return response()->json([
            'message' => 'Power Reel deleted successfully.',
        ], 200);
    }

    public function getCJInjectors(Request $request)
    {
        // Assuming you have a CJInjectorModel
        $cjInjectors = CJInjectorModel::select('id', 'cj_injector_name')
            ->orderBy('cj_injector_name')
            ->get();

        return response()->json($cjInjectors, 200);
    }

    public function storeCJInjector(Request $request)
    {
        $request->validate([
            'cj_injector_name' => 'required|string|max:255',
        ]);

        $cjInjector = CJInjectorModel::create([
            'cj_injector_name' => $request->input('cj_injector_name'),
            'created_at' => now(),
            'created_by' => $request->user()->id,
            'updated_at' => now(),
            'updated_by' => $request->user()->id,
        ]);

        return response()->json([
            'message' => 'CJ Injector created successfully.',
            'data' => $cjInjector,
        ], 201);
    }

    public function updateCJInjector(Request $request, $id)
    {
        $request->validate([
            'cj_injector_name' => 'required|string|max:255',
        ]);

        $cjInjector = CJInjectorModel::findOrFail($id);
        $cjInjector->update([
            'cj_injector_name' => $request->input('cj_injector_name'),
            'updated_at' => now(),
            'updated_by' => $request->user()->id,
        ]);

        return response()->json([
            'message' => 'CJ Injector updated successfully.',
            'data' => $cjInjector,
        ], 200);
    }

    public function deleteCJInjector(Request $request, $id)
    {
        $cjInjector = CJInjectorModel::findOrFail($id);
        $cjInjector->delete();

        return response()->json([
            'message' => 'CJ Injector deleted successfully.',
        ], 200);
    }

    public function getBOPs(Request $request)
    {
        // Assuming you have a BOPModel
        $bops = BOPModel::select('id', 'bop_name')
            ->orderBy('bop_name')
            ->get();

        return response()->json($bops, 200);
    }

    public function storeBOP(Request $request)
    {
        $request->validate([
            'bop_name' => 'required|string|max:255',
        ]);

        $bop = BOPModel::create([
            'bop_name' => $request->input('bop_name'),
            'created_at' => now(),
            'created_by' => $request->user()->id,
            'updated_at' => now(),
            'updated_by' => $request->user()->id,
        ]);

        return response()->json([
            'message' => 'BOP created successfully.',
            'data' => $bop,
        ], 201);
    }

    public function updateBOP(Request $request, $id)
    {
        $request->validate([
            'bop_name' => 'required|string|max:255',
        ]);

        $bop = BOPModel::findOrFail($id);
        $bop->update([
            'bop_name' => $request->input('bop_name'),
            'updated_at' => now(),
            'updated_by' => $request->user()->id,
        ]);

        return response()->json([
            'message' => 'BOP updated successfully.',
            'data' => $bop,
        ], 200);
    }

    public function deleteBOP(Request $request, $id)
    {
        $bop = BOPModel::findOrFail($id);
        $bop->delete();

        return response()->json([
            'message' => 'BOP deleted successfully.',
        ], 200);
    }

    public function getCTSizes(Request $request)
    {
        // Assuming you have a CTSizesModel
        $ctSizes = CTSizeModel::select('id', 'size')
            ->orderBy('size')
            ->get();

        return response()->json($ctSizes, 200);
    }

    public function storeCTSize(Request $request)
    {
        $request->validate([
            'size' => 'required|string|max:255',
        ]);

        $ctSize = CTSizeModel::create([
            'size' => $request->input('size'),
            'created_at' => now(),
            'created_by' => $request->user()->id,
            'updated_at' => now(),
            'updated_by' => $request->user()->id,
        ]);

        return response()->json([
            'message' => 'CT Size created successfully.',
            'data' => $ctSize,
        ], 201);
    }

    public function updateCTSize(Request $request, $id)
    {
        $request->validate([
            'size' => 'required|string|max:255',
        ]);

        $ctSize = CTSizeModel::findOrFail($id);
        $ctSize->update([
            'size' => $request->input('size'),
            'updated_at' => now(),
            'updated_by' => $request->user()->id,
        ]);

        return response()->json([
            'message' => 'CT Size updated successfully.',
            'data' => $ctSize,
        ], 200);
    }

    public function deleteCTSize(Request $request, $id)
    {
        $ctSize = CTSizeModel::findOrFail($id);
        $ctSize->delete();

        return response()->json([
            'message' => 'CT Size deleted successfully.',
        ], 200);
    }

    public function getCTGrades(Request $request)
    {
        // Assuming you have a CTGradeModel
        $ctGrades = CTGradeModel::select('id', 'grade_name')
            ->orderBy('grade_name')
            ->get();

        return response()->json($ctGrades, 200);
    }

    public function storeCTGrade(Request $request)
    {
        $request->validate([
            'grade_name' => 'required|string|max:255',
        ]);

        $ctGrade = CTGradeModel::create([
            'grade_name' => $request->input('grade_name'),
            'created_at' => now(),
            'created_by' => $request->user()->id,
            'updated_at' => now(),
            'updated_by' => $request->user()->id,
        ]);

        return response()->json([
            'message' => 'CT Grade created successfully.',
            'data' => $ctGrade,
        ], 201);
    }

    public function updateCTGrade(Request $request, $id)
    {
        $request->validate([
            'grade_name' => 'required|string|max:255',
        ]);

        $ctGrade = CTGradeModel::findOrFail($id);
        $ctGrade->update([
            'grade_name' => $request->input('grade_name'),
            'updated_at' => now(),
            'updated_by' => $request->user()->id,
        ]);

        return response()->json([
            'message' => 'CT Grade updated successfully.',
            'data' => $ctGrade,
        ], 200);
    }

    public function deleteCTGrade(Request $request, $id)
    {
        $ctGrade = CTGradeModel::findOrFail($id);
        $ctGrade->delete();

        return response()->json([
            'message' => 'CT Grade deleted successfully.',
        ], 200);
    }

    public function getWTs(Request $request)
    {
        // Assuming you have a WTModel
        $wts = WTSModel::select('id', 'size')
            ->orderBy('size')
            ->get();

        return response()->json($wts, 200);
    }

    public function storeWT(Request $request)
    {
        $request->validate([
            'size' => 'required|string|max:255',
        ]);

        $wt = WTSModel::create([
            'size' => $request->input('size'),
            'created_at' => now(),
            'created_by' => $request->user()->id,
            'updated_at' => now(),
            'updated_by' => $request->user()->id,
        ]);

        return response()->json([
            'message' => 'WT created successfully.',
            'data' => $wt,
        ], 201);
    }

    public function updateWT(Request $request, $id)
    {
        $request->validate([
            'size' => 'required|string|max:255',
        ]);

        $wt = WTSModel::findOrFail($id);
        $wt->update([
            'size' => $request->input('size'),
            'updated_at' => now(),
            'updated_by' => $request->user()->id,
        ]);

        return response()->json([
            'message' => 'WT updated successfully.',
            'data' => $wt,
        ], 200);
    }

    public function deleteWT(Request $request, $id)
    {
        $wt = WTSModel::findOrFail($id);
        $wt->delete();

        return response()->json([
            'message' => 'WT deleted successfully.',
        ], 200);
    }

    public function getCTStrings(Request $request)
    {
        // Assuming you have a CTStringModel
        $ctStrings = CTStringModel::select('id', 'ct_string_name')
            ->orderBy('ct_string_name')
            ->get();

        return response()->json($ctStrings, 200);
    }

    public function storeCTString(Request $request)
    {
        $request->validate([
            'ct_string_name' => 'required|string|max:255',
        ]);

        $ctString = CTStringModel::create([
            'ct_string_name' => $request->input('ct_string_name'),
            'created_at' => now(),
            'created_by' => $request->user()->id,
            'updated_at' => now(),
            'updated_by' => $request->user()->id,
        ]);

        return response()->json([
            'message' => 'CT String created successfully.',
            'data' => $ctString,
        ], 201);
    }

    public function updateCTString(Request $request, $id)
    {
        $request->validate([
            'ct_string_name' => 'required|string|max:255',
        ]);

        $ctString = CTStringModel::findOrFail($id);
        $ctString->update([
            'ct_string_name' => $request->input('ct_string_name'),
            'updated_at' => now(),
            'updated_by' => $request->user()->id,
        ]);

        return response()->json([
            'message' => 'CT String updated successfully.',
            'data' => $ctString,
        ], 200);
    }

    public function deleteCTString(Request $request, $id)
    {
        $ctString = CTStringModel::findOrFail($id);
        $ctString->delete();

        return response()->json([
            'message' => 'CT String deleted successfully.',
        ], 200);
    }

    public function getN2Converters(Request $request)
    {
        // Assuming you have a N2ConverterModel
        $n2Converters = N2ConverterModel::select('id', 'n2_converter_name')
            ->orderBy('n2_converter_name')
            ->get();

        return response()->json($n2Converters, 200);
    }

    public function storeN2Converter(Request $request)
    {
        $request->validate([
            'n2_converter_name' => 'required|string|max:255',
        ]);

        $n2Converter = N2ConverterModel::create([
            'n2_converter_name' => $request->input('n2_converter_name'),
            'created_at' => now(),
            'created_by' => $request->user()->id,
            'updated_at' => now(),
            'updated_by' => $request->user()->id,
        ]);

        return response()->json([
            'message' => 'N2 Converter created successfully.',
            'data' => $n2Converter,
        ], 201);
    }

    public function updateN2Converter(Request $request, $id)
    {
        $request->validate([
            'n2_converter_name' => 'required|string|max:255',
        ]);

        $n2Converter = N2ConverterModel::findOrFail($id);
        $n2Converter->update([
            'n2_converter_name' => $request->input('n2_converter_name'),
            'updated_at' => now(),
            'updated_by' => $request->user()->id,
        ]);

        return response()->json([
            'message' => 'N2 Converter updated successfully.',
            'data' => $n2Converter,
        ], 200);
    }

    public function deleteN2Converter(Request $request, $id)
    {
        $n2Converter = N2ConverterModel::findOrFail($id);
        $n2Converter->delete();

        return response()->json([
            'message' => 'N2 Converter deleted successfully.',
        ], 200);
    }

    public function getN2Tanks(Request $request)
    {
        // Assuming you have a N2TankModel
        $n2Tanks = N2TankModel::select('id', 'n2_tank_name')
            ->orderBy('n2_tank_name')
            ->get();

        return response()->json($n2Tanks, 200);
    }

    public function storeN2Tank(Request $request)
    {
        $request->validate([
            'n2_tank_name' => 'required|string|max:255',
        ]);

        $n2Tank = N2TankModel::create([
            'n2_tank_name' => $request->input('n2_tank_name'),
            'created_at' => now(),
            'created_by' => $request->user()->id,
            'updated_at' => now(),
            'updated_by' => $request->user()->id,
        ]);

        return response()->json([
            'message' => 'N2 Tank created successfully.',
            'data' => $n2Tank,
        ], 201);
    }

    public function updateN2Tank(Request $request, $id)
    {
        $request->validate([
            'n2_tank_name' => 'required|string|max:255',
        ]);

        $n2Tank = N2TankModel::findOrFail($id);
        $n2Tank->update([
            'n2_tank_name' => $request->input('n2_tank_name'),
            'updated_at' => now(),
            'updated_by' => $request->user()->id,
        ]);

        return response()->json([
            'message' => 'N2 Tank updated successfully.',
            'data' => $n2Tank,
        ], 200);
    }

    public function deleteN2Tank(Request $request, $id)
    {
        $n2Tank = N2TankModel::findOrFail($id);
        $n2Tank->delete();

        return response()->json([
            'message' => 'N2 Tank deleted successfully.',
        ], 200);
    }

    public function getContainers(Request $request)
    {
        // Assuming you have a ContainerModel
        $containers = ContainerModel::select('id', 'container_name')
            ->orderBy('container_name')
            ->get();

        return response()->json($containers, 200);
    }

    public function storeContainer(Request $request)
    {
        $request->validate([
            'container_name' => 'required|string|max:255',
        ]);

        $container = ContainerModel::create([
            'container_name' => $request->input('container_name'),
            'created_at' => now(),
            'created_by' => $request->user()->id,
            'updated_at' => now(),
            'updated_by' => $request->user()->id,
        ]);

        return response()->json([
            'message' => 'Container created successfully.',
            'data' => $container,
        ], 201);
    }

    public function updateContainer(Request $request, $id)
    {
        $request->validate([
            'container_name' => 'required|string|max:255',
        ]);

        $container = ContainerModel::findOrFail($id);
        $container->update([
            'container_name' => $request->input('container_name'),
            'updated_at' => now(),
            'updated_by' => $request->user()->id,
        ]);

        return response()->json([
            'message' => 'Container updated successfully.',
            'data' => $container,
        ], 200);
    }

    public function deleteContainer(Request $request, $id)
    {
        $container = ContainerModel::findOrFail($id);
        $container->delete();

        return response()->json([
            'message' => 'Container deleted successfully.',
        ], 200);
    }

    public function getInjectorGoosnecks(Request $request)
    {
        // Assuming you have a InjectorGooseneckModel
        $injectorGoosenecks = InjectorGoosneckModel::select('id', 'injector_goosneck_name')
            ->orderBy('injector_goosneck_name')
            ->get();

        return response()->json($injectorGoosenecks, 200);
    }

    public function storeInjectorGoosneck(Request $request)
    {
        $request->validate([
            'injector_goosneck_name' => 'required|string|max:255',
        ]);

        $injectorGoosneck = InjectorGoosneckModel::create([
            'injector_goosneck_name' => $request->input('injector_goosneck_name'),
            'created_at' => now(),
            'created_by' => $request->user()->id,
            'updated_at' => now(),
            'updated_by' => $request->user()->id,
        ]);

        return response()->json([
            'message' => 'Injector Gooseneck created successfully.',
            'data' => $injectorGoosneck,
        ], 201);
    }

    public function updateInjectorGoosneck(Request $request, $id)
    {
        $request->validate([
            'injector_goosneck_name' => 'required|string|max:255',
        ]);

        $injectorGoosneck = InjectorGoosneckModel::findOrFail($id);
        $injectorGoosneck->update([
            'injector_goosneck_name' => $request->input('injector_goosneck_name'),
            'updated_at' => now(),
            'updated_by' => $request->user()->id,
        ]);

        return response()->json([
            'message' => 'Injector Gooseneck updated successfully.',
            'data' => $injectorGoosneck,
        ], 200);
    }

    public function deleteInjectorGoosneck(Request $request, $id)
    {
        $injectorGoosneck = InjectorGoosneckModel::findOrFail($id);
        $injectorGoosneck->delete();

        return response()->json([
            'message' => 'Injector Gooseneck deleted successfully.',
        ], 200);
    }

    public function getMiscellaneousTools(Request $request)
    {
        // Assuming you have a MiscellaneousToolModel
        $miscellaneousTools = MiscellaneousToolModel::select('id', 'miscellaneous_tool_name')
            ->orderBy('miscellaneous_tool_name')
            ->get();

        return response()->json($miscellaneousTools, 200);
    }

    public function storeMiscellaneousTool(Request $request)
    {
        $request->validate([
            'miscellaneous_tool_name' => 'required|string|max:255',
        ]);

        $miscellaneousTool = MiscellaneousToolModel::create([
            'miscellaneous_tool_name' => $request->input('miscellaneous_tool_name'),
            'created_at' => now(),
            'created_by' => $request->user()->id,
            'updated_at' => now(),
            'updated_by' => $request->user()->id,
        ]);

        return response()->json([
            'message' => 'Miscellaneous Tool created successfully.',
            'data' => $miscellaneousTool,
        ], 201);
    }

    public function updateMiscellaneousTool(Request $request, $id)
    {
        $request->validate([
            'miscellaneous_tool_name' => 'required|string|max:255',
        ]);

        $miscellaneousTool = MiscellaneousToolModel::findOrFail($id);
        $miscellaneousTool->update([
            'miscellaneous_tool_name' => $request->input('miscellaneous_tool_name'),
            'updated_at' => now(),
            'updated_by' => $request->user()->id,
        ]);

        return response()->json([
            'message' => 'Miscellaneous Tool updated successfully.',
            'data' => $miscellaneousTool,
        ], 200);
    }

    public function deleteMiscellaneousTool(Request $request, $id)
    {
        $miscellaneousTool = MiscellaneousToolModel::findOrFail($id);
        $miscellaneousTool->delete();

        return response()->json([
            'message' => 'Miscellaneous Tool deleted successfully.',
        ], 200);
    }

    public function getCTPersonnels(Request $request)
    {
        // Assuming you have a CTPersonnelModel
        $ctPersonnels = CTPersonnelModel::select('id', 'ct_personnel_name')
            ->orderBy('ct_personnel_name')
            ->get();

        return response()->json($ctPersonnels, 200);
    }

    public function storeCTPersonnel(Request $request)
    {
        $request->validate([
            'ct_personnel_name' => 'required|string|max:255',
        ]);

        $ctPersonnel = CTPersonnelModel::create([
            'ct_personnel_name' => $request->input('ct_personnel_name'),
            'created_at' => now(),
            'created_by' => $request->user()->id,
            'updated_at' => now(),
            'updated_by' => $request->user()->id,
        ]);

        return response()->json([
            'message' => 'CT Personnel created successfully.',
            'data' => $ctPersonnel,
        ], 201);
    }

    public function updateCTPersonnel(Request $request, $id)
    {
        $request->validate([
            'ct_personnel_name' => 'required|string|max:255',
        ]);

        $ctPersonnel = CTPersonnelModel::findOrFail($id);
        $ctPersonnel->update([
            'ct_personnel_name' => $request->input('ct_personnel_name'),
            'updated_at' => now(),
            'updated_by' => $request->user()->id,
        ]);

        return response()->json([
            'message' => 'CT Personnel updated successfully.',
            'data' => $ctPersonnel,
        ], 200);
    }

    public function deleteCTPersonnel(Request $request, $id)
    {
        $ctPersonnel = CTPersonnelModel::findOrFail($id);
        $ctPersonnel->delete();

        return response()->json([
            'message' => 'CT Personnel deleted successfully.',
        ], 200);
    }

    public function getCTSupervisors(Request $request)
    {
        // Assuming you have a CTSupervisorModel
        $ctSupervisors = CTSupervisorModel::select('id', 'ct_supervisor_name')
            ->orderBy('ct_supervisor_name')
            ->get();

        return response()->json($ctSupervisors, 200);
    }

    public function storeCTSupervisor(Request $request)
    {
        $request->validate([
            'ct_supervisor_name' => 'required|string|max:255',
        ]);

        $ctSupervisor = CTSupervisorModel::create([
            'ct_supervisor_name' => $request->input('ct_supervisor_name'),
            'created_at' => now(),
            'created_by' => $request->user()->id,
            'updated_at' => now(),
            'updated_by' => $request->user()->id,
        ]);

        return response()->json([
            'message' => 'CT Supervisor created successfully.',
            'data' => $ctSupervisor,
        ], 201);
    }

    public function updateCTSupervisor(Request $request, $id)
    {
        $request->validate([
            'ct_supervisor_name' => 'required|string|max:255',
        ]);

        $ctSupervisor = CTSupervisorModel::findOrFail($id);
        $ctSupervisor->update([
            'ct_supervisor_name' => $request->input('ct_supervisor_name'),
            'updated_at' => now(),
            'updated_by' => $request->user()->id,
        ]);

        return response()->json([
            'message' => 'CT Supervisor updated successfully.',
            'data' => $ctSupervisor,
        ], 200);
    }

    public function deleteCTSupervisor(Request $request, $id)
    {
        $ctSupervisor = CTSupervisorModel::findOrFail($id);
        $ctSupervisor->delete();

        return response()->json([
            'message' => 'CT Supervisor deleted successfully.',
        ], 200);
    }

    public function getNitrogenSupervisors(Request $request)
    {
        // Assuming you have a NitrogenSupervisorModel
        $nitrogenSupervisors = NitrogenSupervisorModel::select('id', 'nitrogen_supervisor_name')
            ->orderBy('nitrogen_supervisor_name')
            ->get();

        return response()->json($nitrogenSupervisors, 200);
    }

    public function storeNitrogenSupervisor(Request $request)
    {
        $request->validate([
            'nitrogen_supervisor_name' => 'required|string|max:255',
        ]);

        $nitrogenSupervisor = NitrogenSupervisorModel::create([
            'nitrogen_supervisor_name' => $request->input('nitrogen_supervisor_name'),
            'created_at' => now(),
            'created_by' => $request->user()->id,
            'updated_at' => now(),
            'updated_by' => $request->user()->id,
        ]);

        return response()->json([
            'message' => 'Nitrogen Supervisor created successfully.',
            'data' => $nitrogenSupervisor,
        ], 201);
    }

    public function updateNitrogenSupervisor(Request $request, $id)
    {
        $request->validate([
            'nitrogen_supervisor_name' => 'required|string|max:255',
        ]);

        $nitrogenSupervisor = NitrogenSupervisorModel::findOrFail($id);
        $nitrogenSupervisor->update([
            'nitrogen_supervisor_name' => $request->input('nitrogen_supervisor_name'),
            'updated_at' => now(),
            'updated_by' => $request->user()->id,
        ]);

        return response()->json([
            'message' => 'Nitrogen Supervisor updated successfully.',
            'data' => $nitrogenSupervisor,
        ], 200);
    }

    public function deleteNitrogenSupervisor(Request $request, $id)
    {
        $nitrogenSupervisor = NitrogenSupervisorModel::findOrFail($id);
        $nitrogenSupervisor->delete();

        return response()->json([
            'message' => 'Nitrogen Supervisor deleted successfully.',
        ], 200);
    }

    public function getNitrogenPersonnels(Request $request)
    {
        // Assuming you have a NitrogenPersonnelModel
        $nitrogenPersonnels = NitrogenPersonnelModel::select('id', 'nitrogen_personnel_name')
            ->orderBy('nitrogen_personnel_name')
            ->get();

        return response()->json($nitrogenPersonnels, 200);
    }

    public function storeNitrogenPersonnel(Request $request)
    {
        $request->validate([
            'nitrogen_personnel_name' => 'required|string|max:255',
        ]);

        $nitrogenPersonnel = NitrogenPersonnelModel::create([
            'nitrogen_personnel_name' => $request->input('nitrogen_personnel_name'),
            'created_at' => now(),
            'created_by' => $request->user()->id,
            'updated_at' => now(),
            'updated_by' => $request->user()->id,
        ]);

        return response()->json([
            'message' => 'Nitrogen Personnel created successfully.',
            'data' => $nitrogenPersonnel,
        ], 201);
    }

    public function updateNitrogenPersonnel(Request $request, $id)
    {
        $request->validate([
            'nitrogen_personnel_name' => 'required|string|max:255',
        ]);

        $nitrogenPersonnel = NitrogenPersonnelModel::findOrFail($id);
        $nitrogenPersonnel->update([
            'nitrogen_personnel_name' => $request->input('nitrogen_personnel_name'),
            'updated_at' => now(),
            'updated_by' => $request->user()->id,
        ]);

        return response()->json([
            'message' => 'Nitrogen Personnel updated successfully.',
            'data' => $nitrogenPersonnel,
        ], 200);
    }

    public function deleteNitrogenPersonnel(Request $request, $id)
    {
        $nitrogenPersonnel = NitrogenPersonnelModel::findOrFail($id);
        $nitrogenPersonnel->delete();

        return response()->json([
            'message' => 'Nitrogen Personnel deleted successfully.',
        ], 200);
    }
}
