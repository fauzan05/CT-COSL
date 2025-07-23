<?php

namespace App\Http\Controllers;

use App\Models\JobTracker\JobTrackerModel;
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
}
