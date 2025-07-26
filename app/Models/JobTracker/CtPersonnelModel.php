<?php

namespace App\Models\JobTracker;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class CTPersonnelModel extends Model
{
    use SoftDeletes;
    protected $table = 'ct_personnels';
    public $timestamps = false;
    protected $fillable = [
        'job_tracker_id',
        'ct_personnel_name',
        'created_by',
        'updated_by',
    ];
    protected $casts = [
        'created_at' => 'datetime',
        'updated_at' => 'datetime',
        'deleted_at' => 'datetime',
    ];

    /**
     * Get the job tracker associated with the CT personnel.
     */

    public function jobTracker()
    {
        return $this->belongsTo(JobTrackerModel::class, 'job_tracker_id');
    }
}
