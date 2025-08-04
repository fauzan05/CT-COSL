<?php

namespace App\Models\JobTracker;

use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class ControlCabinModel extends Model
{
    use SoftDeletes, HasUuids;
    protected $table = 'control_cabins';
    public $timestamps = false;
    protected $fillable = [
        'cabin_name',
        'created_by',
        'updated_by',
    ];
    protected $casts = [
        'created_at' => 'datetime',
        'updated_at' => 'datetime',
        'deleted_at' => 'datetime',
    ];
}
