<?php

namespace App\Models\JobTracker;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class CompletionSizeModel extends Model
{
    use SoftDeletes;
    protected $table = 'completion_sizes';
    public $timestamps = false;
    protected $fillable = [
        'size',
        'size_unit',
        'created_by',
        'updated_by',
    ];
    protected $casts = [
        'created_at' => 'datetime',
        'updated_at' => 'datetime',
        'deleted_at' => 'datetime',
    ];
}
