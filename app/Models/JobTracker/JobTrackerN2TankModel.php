<?php

namespace App\Models\JobTracker;

use App\Models\User;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class JobTrackerN2TankModel extends Model
{
    use SoftDeletes;
    protected $table = 'job_tracker_n2_tanks';
    public $timestamps = false;
    protected $fillable = [
        'job_tracker_id',
        'n2_tank_name',
        'created_by',
        'updated_by',
    ];
    protected $casts = [
        'created_at' => 'datetime',
        'updated_at' => 'datetime',
        'deleted_at' => 'datetime',
    ];

    /**
     * Get the job tracker associated with the N2 tank.
     */
    public function jobTracker()
    {
        return $this->belongsTo(JobTrackerModel::class, 'job_tracker_id');
    }
    /**
     * Get the user who created the record.
     */
    public function createdBy()
    {
        return $this->belongsTo(User::class, 'created_by');
    }
    /**
     * Get the user who last updated the record.
     */
    public function updatedBy()
    {
        return $this->belongsTo(User::class, 'updated_by');
    }
}
