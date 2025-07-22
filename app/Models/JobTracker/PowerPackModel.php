<?php

namespace App\Models\JobTracker;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class PowerPackModel extends Model
{
    use SoftDeletes;
    protected $table = 'power_packs';
    public $timestamps = false;
    protected $fillable = [
        'power_pack_name',
        'created_by',
        'updated_by',
    ];
    protected $casts = [
        'created_at' => 'datetime',
        'updated_at' => 'datetime',
    ];
}
