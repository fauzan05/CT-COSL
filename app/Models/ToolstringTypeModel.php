<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class ToolstringTypeModel extends Model
{
    use SoftDeletes;

    protected $table = 'toolstring_types';
    public $timestamps = false;
    protected $fillable = [
        'name',
        'slug',
        'created_by',
        'updated_by',
    ];

    protected $casts = [
        'created_at' => 'datetime',
        'updated_at' => 'datetime',
    ];
}
