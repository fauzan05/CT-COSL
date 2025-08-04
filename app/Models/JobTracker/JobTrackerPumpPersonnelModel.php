<?php

namespace App\Models\JobTracker;

use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class JobTrackerPumpPersonnelModel extends Model
{
    use SoftDeletes, HasUuids;
    protected $table = 'job_tracker_pump_personnels';
    public $timestamps = false;
    protected $fillable = [
        'job_tracker_id',
        'pump_personnel_name',
        'created_by',
        'updated_by',
    ];
    protected $casts = [
        'created_at' => 'datetime',
        'updated_at' => 'datetime',
        'deleted_at' => 'datetime',
    ];

    /**
     * Get the job tracker associated with the pump personnel.
     */
    public function jobTracker()
    {
        return $this->belongsTo(JobTrackerModel::class, 'job_tracker_id');
    }
}
