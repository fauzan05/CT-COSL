<?php

namespace App\Models\JobTracker;

use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class MaxBHAODModel extends Model
{
    use SoftDeletes, HasUuids;
    protected $table = 'max_bha_ods';
    public $timestamps = false;
    protected $fillable = [
        'size',
        'created_by',
        'updated_by',
    ];
    protected $casts = [
        'created_at' => 'datetime',
        'updated_at' => 'datetime',
        'deleted_at' => 'datetime',
    ];
}
