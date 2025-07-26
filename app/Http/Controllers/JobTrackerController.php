<?php

namespace App\Http\Controllers;

use App\Models\JobTracker\BjDistrictModel;
use App\Models\JobTracker\CasingLinerSizeModel;
use App\Models\JobTracker\CompletionSizeModel;
use App\Models\JobTracker\ControlCabinModel;
use App\Models\JobTracker\CustomerModel;
use App\Models\JobTracker\FieldLocationModel;
use App\Models\JobTracker\FieldTypeModel;
use App\Models\JobTracker\JobDescriptionModel;
use App\Models\JobTracker\JobTrackerModel;
use App\Models\JobTracker\MaxBHAODModel;
use App\Models\JobTracker\NozzleTypeModel;
use App\Models\JobTracker\PowerPackModel;
use App\Models\JobTracker\PowerReelModel;
use App\Models\JobTracker\WellheadXOverModel;
use App\Models\JobTracker\WellStatusModel;
use Illuminate\Http\Request;

class JobTrackerController extends Controller
{
    public function getJobTrackers(Request $request)
    {
        $perPage = $request->input('per_page', 10);

        // Query builder
        $query = JobTrackerModel::with([
            'pumpPersonnels',
            'nitrogenPersonnels',
            'ctPersonnels',
            'volumeAcids',
            'createdBy',
            'jobDescriptions',
            'n2Tanks',
            'miscellaneousTools',
            'containers',
            'injectorGoosnecks',
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
                'max_deviation' => $jobTracker->max_deviation,
                'depth_md' => $jobTracker->depth_md,
                'depth_md_unit' => $jobTracker->depth_md_unit,
                'depth_tvd' => $jobTracker->depth_tvd,
                'depth_tvd_unit' => $jobTracker->depth_tvd_unit,
                'bh_pressure' => $jobTracker->bh_pressure,
                'bh_pressure_unit' => $jobTracker->bh_pressure_unit,
                'bh_temp' => $jobTracker->bh_temp,
                'bh_temp_unit' => $jobTracker->bh_temp_unit,
                'nitrogen_volume' => $jobTracker->nitrogen_volume,
                'nitrogen_volume_unit' => $jobTracker->nitrogen_volume_unit,
                'cement_volume' => $jobTracker->cement_volume,
                'cement_volume_unit' => $jobTracker->cement_volume_unit,
                'revenue_currency' => $jobTracker->revenue_currency,
                'revenue_coiled_tubing' => $jobTracker->revenue_coiled_tubing,
                'revenue_nitrogen' => $jobTracker->revenue_nitrogen,
                'revenue_pumping' => $jobTracker->revenue_pumping,
                'revenue_special_tools' => $jobTracker->revenue_special_tools,
                'revenue_acid' => $jobTracker->revenue_acid,
                'revenue_nitrogen' => $jobTracker->revenue_nitrogen,
                'revenue_cement' => $jobTracker->revenue_cement,
                'personnel_charges' => $jobTracker->personnel_charges,
                'service_charges' => $jobTracker->service_charges,
                'other_charges' => $jobTracker->other_charges,
                'total_revenue' => $jobTracker->total_revenue,
                'created_by' => $jobTracker->created_by,
                'updated_by_name' => $jobTracker->updatedBy ? $jobTracker->updatedBy->fullname : null,
                'pump_personnels' => $jobTracker->pumpPersonnels->map(function ($pumpPersonnel) {
                    return [
                        'id' => $pumpPersonnel->id,
                        'name' => $pumpPersonnel->name,
                        'created_by' => $pumpPersonnel->created_by,
                        'updated_by' => $pumpPersonnel->updated_by,
                    ];
                }),
                'nitrogen_personnels' => $jobTracker->nitrogenPersonnels->map(function ($nitrogenPersonnel) {
                    return [
                        'id' => $nitrogenPersonnel->id,
                        'name' => $nitrogenPersonnel->name,
                        'created_by' => $nitrogenPersonnel->created_by,
                        'updated_by' => $nitrogenPersonnel->updated_by,
                    ];
                }),
                'ct_personnels' => $jobTracker->ctPersonnels->map(function ($ctPersonnel) {
                    return [
                        'id' => $ctPersonnel->id,
                        'name' => $ctPersonnel->name,
                        'created_by' => $ctPersonnel->created_by,
                        'updated_by' => $ctPersonnel->updated_by,
                    ];
                }),
                'volume_acids' => $jobTracker->volumeAcids->map(function ($volumeAcid) {
                    return [
                        'id' => $volumeAcid->id,
                        'volume' => $volumeAcid->volume,
                        'volume_unit' => $volumeAcid->volume_unit,
                        'created_by' => $volumeAcid->created_by,
                        'updated_by' => $volumeAcid->updated_by,
                    ];
                }),
            ];
        });

        // Return the full paginator object as JSON
        return response()->json($jobTrackers, 200);
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
        $districts = BjDistrictModel::select('id', 'district_name')
            ->orderBy('district_name')
            ->get();

        return response()->json($districts, 200);
    }

    public function storeBJDistrict(Request $request)
    {
        $request->validate([
            'district_name' => 'required|string|max:255',
        ]);

        $district = BjDistrictModel::create([
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

        $district = BjDistrictModel::findOrFail($id);
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
        $district = BjDistrictModel::findOrFail($id);
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
        $wellTypes = FieldTypeModel::select('id', 'type_name')
            ->orderBy('type_name')
            ->get();

        return response()->json($wellTypes, 200);
    }

    public function storeWellType(Request $request)
    {
        $request->validate([
            'type_name' => 'required|string|max:255',
        ]);

        $wellType = FieldTypeModel::create([
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

        $wellType = FieldTypeModel::findOrFail($id);
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
        $wellType = FieldTypeModel::findOrFail($id);
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
}
