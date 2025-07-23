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
        'well_name',
        'company_man',
        'bj_representative',
        'job_start_date',
        'job_finish_date',
        'job_days',
        'max_deviation',
        'depth_md',
        'depth_md_unit',
        'depth_tvd',
        'depth_tvd_unit',
        'bh_pressure',
        'bh_pressure_unit',
        'bh_temp',
        'bh_temp_unit',
        'nitrogen_volume',
        'nitrogen_volume_unit',
        'cement_volume',
        'cement_volume_unit',
        'revenue_currency',
        'revenue_coiled_tubing',
        'revenue_nitrogen',
        'revenue_pumping',
        'revenue_special_tools',
        'revenue_acid',
        'revenue_nitrogen',
        'revenue_cement',
        'personnel_charges',
        'service_charges',
        'other_charges',
        'total_revenue',
        'created_by',
        'updated_by',
    ];

    protected $casts = [
        'job_start_date' => 'datetime',
        'job_finish_date' => 'datetime',
        'created_at' => 'datetime',
        'updated_at' => 'datetime',
        'deleted_at' => 'datetime',
    ];

    /**
     * Get the pump personnel associated with the job tracker.
     */
    public function pumpPersonnels()
    {
        return $this->hasMany(PumpPersonnelModel::class, 'job_tracker_id');
    }
    /**
     * Get the nitrogen personnel associated with the job tracker.
     */
    public function nitrogenPersonnels()
    {
        return $this->hasMany(NitrogenPersonnelModel::class, 'job_tracker_id');
    }
     /**
     * Get the ct personnel associated with the job tracker.
     */
    public function ctPersonnels()
    {
        return $this->hasMany(CtPersonnelModel::class, 'job_tracker_id');
    }
    /**
     * Get the volume acids associated with the job tracker.
     */
    public function volumeAcids()
    {
        return $this->hasMany(VolumeAcidModel::class, 'job_tracker_id');
    }
    /**
     * Get the created by user.
     */
    public function createdBy()
    {
        return $this->belongsTo(User::class, 'created_by');
    }

    /**
     * Get the updated by user.
     */
    public function updatedBy()
    {
        return $this->belongsTo(User::class, 'updated_by');
    }

    /**
     * Get the job descriptions associated with the job tracker.
     */
    public function jobDescriptions()
    {
        return $this->hasMany(JobTrackerJobDescriptionModel::class, 'job_tracker_id');
    }
    

    /**
     * Get the n2 tank associated with the job tracker.
     */
    public function n2Tanks()
    {
        return $this->hasMany(JobTrackerN2TankModel::class, 'job_tracker_id');
    }

    /**
     * Get the containers associated with the job tracker.
     */
    public function containers()
    {
        return $this->hasMany(JobTrackerContainerModel::class, 'job_tracker_id');
    }

    /**
     * Get the injector goosenecks associated with the job tracker.
     */
    public function injectorGoosenecks()
    {
        return $this->hasMany(JobTrackerInjectorGoosneckModel::class, 'job_tracker_id');
    }

    /**
     * Get the miscellaneous tools associated with the job tracker.
     */
    public function miscellaneousTools()
    {
        return $this->hasMany(JobTrackerMiscellaneousToolModel::class, 'job_tracker_id');
    }
}
