<?php

namespace App\Models\JobTracker;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class JobTrackerAcidVolumeModel extends Model
{
    use SoftDeletes;
    protected $table = 'job_tracker_acid_volumes';
    public $timestamps = false;
    protected $fillable = [
        'job_tracker_id',
        'volume',
        'volume_unit',
        'created_by',
        'updated_by',
    ];
    protected $casts = [
        'created_at' => 'datetime',
        'updated_at' => 'datetime',
        'deleted_at' => 'datetime',
    ];

    /**
     * Get the job tracker associated with the acid volume.
     */
    public function jobTracker()
    {
        return $this->belongsTo(JobTrackerModel::class, 'job_tracker_id');
    }
}
