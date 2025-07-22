<?php

namespace App\Models\JobTracker;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class VolumeAcidModel extends Model
{
    use SoftDeletes;
    protected $table = 'volume_acids';
    public $timestamps = false;
    protected $fillable = [
        'job_tracker_id',
        'volume',
        'volume_unit',
        'created_by',
        'updated_by',
    ];
}
