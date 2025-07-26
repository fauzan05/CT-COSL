<?php

namespace App\Models\JobTracker;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class BOPModel extends Model
{
    use SoftDeletes;
    protected $table = 'bops';
    public $timestamps = false;
    protected $fillable = [
        'bop_name',
        'created_by',
        'updated_by',
    ];
    protected $casts = [
        'created_at' => 'datetime',
        'updated_at' => 'datetime',
        'deleted_at' => 'datetime',
    ];
}
