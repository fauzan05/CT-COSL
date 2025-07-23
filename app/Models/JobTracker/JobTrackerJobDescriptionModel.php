<?php

namespace App\Models\JobTracker;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class JobTrackerJobDescriptionModel extends Model
{
    use SoftDeletes;
    protected $table = 'job_tracker_job_descriptions';
    public $timestamps = false;
    protected $fillable = [
        'job_tracker_id',
        'description',
        'created_by',
        'updated_by',
    ];
    protected $casts = [
        'created_at' => 'datetime',
        'updated_at' => 'datetime',
        'deleted_at' => 'datetime',
    ];
    /**
     * Get the job tracker associated with the job description.
     */
    public function jobTracker()
    {
        return $this->belongsTo(JobTrackerModel::class, 'job_tracker_id');
    }
}
