<?php

namespace App\Models\JobTracker;

use App\Models\User;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class JobTrackerModel extends Model
{
    use SoftDeletes;
    protected $table = 'job_trackers';
    public $timestamps = false;
    protected $fillable = [
        'bh_pressure',
        'bh_pressure_unit',
        'bh_temp',
        'bh_temp_unit',
        'cosl_base',
        'cosl_ocd_representative',
        'bop',
        'casing_liner_size',
        'casing_liner_size_unit',
        'cement_volume',
        'cement_volume_unit',
        'company_man',
        'completion_size',
        'completion_size_unit',
        'control_cabin',
        'created_by',
        'ct_grade',
        'ct_size',
        'ct_size_unit',
        'ct_string',
        'ct_supervisor',
        'cj_injector',
        'customer',
        'depth_md',
        'depth_md_unit',
        'depth_tvd',
        'depth_tvd_unit',
        'field_location',
        'field_type',
        'job_days',
        'job_finish_date',
        'job_start_date',
        'max_bha_od',
        'max_bha_od_unit',
        'max_deviation',
        'material_charges',
        'mobilization_charges',
        'n2_converter',
        'nitrogen_supervisor',
        'nitrogen_volume',
        'nitrogen_volume_unit',
        'nozzle_type',
        'other_charges',
        'personnel_charges',
        'power_pack',
        'power_reel',
        'pump_supervisor',
        'revenue_acid',
        'revenue_cement',
        'revenue_coiled_tubing',
        'revenue_currency',
        'revenue_nitrogen_equipment',
        'revenue_nitrogen_product',
        'revenue_pumping',
        'revenue_special_tools',
        'service_charges',
        'total_revenue',
        'updated_by',
        'well_name',
        'well_status',
        'well_type',
        'wellhead_x_over',
        'wt',
    ];

    protected $casts = [
        'created_at' => 'datetime',
        'updated_at' => 'datetime',
        'deleted_at' => 'datetime',
    ];

    /**
     * Get the updated by user.
     */
    public function updatedBy()
    {
        return $this->belongsTo(User::class, 'updated_by');
    }

    /**
     * Get the created by user.
     */
    public function createdBy()
    {
        return $this->belongsTo(User::class, 'created_by');
    }

    /**
     * Get the job descriptions associated with the job tracker.
     */
    public function jobDescriptions()
    {
        return $this->hasMany(JobTrackerJobDescriptionModel::class, 'job_tracker_id');
    }

    /**
     * Get the maximum depth associated with the job tracker.
     */
    public function maxDepths()
    {
        return $this->hasMany(JobTrackerMaxDepthModel::class, 'job_tracker_id');
    }

    /**
     * Get the N2 tanks associated with the job tracker.
     */
    public function n2Tanks()
    {
        return $this->hasMany(JobTrackerN2TankModel::class, 'job_tracker_id');
    }

    // containers
    public function containers()
    {
        return $this->hasMany(JobTrackerContainerModel::class, 'job_tracker_id');
    }

    // injector goosneck
    public function injectorGoosnecks()
    {
        return $this->hasMany(JobTrackerInjectorGoosneckModel::class, 'job_tracker_id');
    }

    // miscellaneous tools
    public function miscellaneousTools()
    {
        return $this->hasMany(JobTrackerMiscellaneousToolModel::class, 'job_tracker_id');
    }

    // ct personnels
    public function ctPersonnels()
    {
        return $this->hasMany(JobTrackerCTPersonnelModel::class, 'job_tracker_id');
    }

    // nitrogen personnels
    public function nitrogenPersonnels()
    {
        return $this->hasMany(JobTrackerNitrogenPersonnelModel::class, 'job_tracker_id');
    }

    // acid types
    public function acidTypes()
    {
        return $this->hasMany(JobTrackerAcidTypeModel::class, 'job_tracker_id');
    }

    // acid volumes
    public function acidVolumes()
    {
        return $this->hasMany(JobTrackerAcidVolumeModel::class, 'job_tracker_id');
    }

    /**
     * Get the pump personnel associated with the job tracker.
     */
    public function pumpPersonnels()
    {
        return $this->hasMany(JobTrackerPumpPersonnelModel::class, 'job_tracker_id');
    }
}
