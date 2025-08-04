<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class ThreadModel extends Model
{
    use SoftDeletes, HasUuids;

    protected $table = 'threads';
    public $timestamps = false;
    protected $fillable = [
        'type',
        'created_by',
        'updated_by',
    ];

    protected $casts = [
        'created_at' => 'datetime',
        'updated_at' => 'datetime',
        'deleted_at' => 'datetime',
    ];

    public function sizes()
    {
        return $this->hasMany(ThreadSizeModel::class, 'thread_id');
    }
}
