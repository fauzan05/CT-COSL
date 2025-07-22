<?php

namespace App\Models\JobTracker;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class WtsModel extends Model
{
    use SoftDeletes;
    protected $table = 'wts';
    public $timestamps = false;
    protected $fillable = [
        'wt_name',
        'wt_unit',
        'created_by',
        'updated_by',
    ];
    protected $casts = [
        'created_at' => 'datetime',
        'updated_at' => 'datetime',
        'deleted_at' => 'datetime',
    ];
}
