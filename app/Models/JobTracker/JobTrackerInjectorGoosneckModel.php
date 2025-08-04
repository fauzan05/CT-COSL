<?php

namespace App\Models\JobTracker;

use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class JobTrackerInjectorGoosneckModel extends Model
{
    use SoftDeletes, HasUuids;
    protected $table = 'job_tracker_injector_goosnecks';
    public $timestamps = false;
    protected $fillable = [
        'job_tracker_id',
        'injector_goosneck_name',
        'created_by',
        'updated_by',
    ];

    protected $casts = [
        'created_at' => 'datetime',
        'updated_at' => 'datetime',
        'deleted_at' => 'datetime',
    ];
    /**
     * Get the job tracker associated with the injector goosneck.
     */
    public function jobTracker()
    {
        return $this->belongsTo(JobTrackerModel::class, 'job_tracker_id');
    }
}
