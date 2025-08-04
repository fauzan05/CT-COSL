<?php

namespace App\Models\JobTracker;

use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class MiscellaneousToolModel extends Model
{
    use SoftDeletes, HasUuids;
    protected $table = 'miscellaneous_tools';
    public $timestamps = false;
    protected $fillable = [
        'miscellaneous_tool_name',
        'created_by',
        'updated_by',
    ];
    protected $casts = [
        'created_at' => 'datetime',
        'updated_at' => 'datetime',
        'deleted_at' => 'datetime',
    ];
}
