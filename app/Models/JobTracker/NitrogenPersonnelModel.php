<?php

namespace App\Models\JobTracker;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class NitrogenPersonnelModel extends Model
{
    use SoftDeletes;
    protected $table = 'nitrogen_personnels';
    public $timestamps = false;
    protected $fillable = [
        'job_tracker_id',
        'nitrogen_personnel_name',
        'created_by',
        'updated_by',
    ];
    protected $casts = [
        'created_at' => 'datetime',
        'updated_at' => 'datetime',
        'deleted_at' => 'datetime',
    ];

    /**
     * Get the job tracker associated with the nitrogen personnel.
     */
    public function jobTracker()
    {
        return $this->belongsTo(JobTrackerModel::class, 'job_tracker_id');
    }
}
