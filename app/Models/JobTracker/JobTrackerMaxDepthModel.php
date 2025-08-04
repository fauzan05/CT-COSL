<?php

namespace App\Models\JobTracker;

use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class JobTrackerMaxDepthModel extends Model
{
    use SoftDeletes, HasUuids;
    protected $table = 'job_tracker_max_depths';
    public $timestamps = false;
    protected $fillable = [
        'job_tracker_id',
        'max_depth',
        'max_depth_unit',
        'created_by',
        'updated_by',
    ];

    protected $casts = [
        'created_at' => 'datetime',
        'updated_at' => 'datetime',
        'deleted_at' => 'datetime',
    ];
}
