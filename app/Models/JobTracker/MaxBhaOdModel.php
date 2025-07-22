<?php

namespace App\Models\JobTracker;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class MaxBhaOdModel extends Model
{
    use SoftDeletes;
    protected $table = 'max_bha_ods';
    public $timestamps = false;
    protected $fillable = [
        'max_bha_od',
        'max_bha_od_unit',
        'created_by',
        'updated_by',
    ];
    protected $casts = [
        'created_at' => 'datetime',
        'updated_at' => 'datetime',
        'deleted_at' => 'datetime',
    ];
}
