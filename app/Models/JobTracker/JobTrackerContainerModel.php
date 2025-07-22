<?php

namespace App\Models\JobTracker;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class JobTrackerContainerModel extends Model
{
    use SoftDeletes;
    protected $table = 'job_tracker_containers';
    public $timestamps = false;
    protected $fillable = [
        'job_tracker_id',
        'container_id',
        'created_by',
        'updated_by',
    ];
    protected $casts = [
        'created_at' => 'datetime',
        'updated_at' => 'datetime',
        'deleted_at' => 'datetime',
    ];
    /**
     * Get the job tracker associated with the container.
     */
    public function jobTracker()
    {
        return $this->belongsTo(JobTrackerModel::class, 'job_tracker_id');
    }
    /**
     * Get the container associated with the job tracker.
     */
    public function container()
    {
        return $this->belongsTo(ContainerModel::class, 'container_id');
    }
}
