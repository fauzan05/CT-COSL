<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class ThreadSizeModel extends Model
{
    use SoftDeletes, HasUuids;

    protected $table = 'thread_sizes';
    public $timestamps = false;
    protected $fillable = [
        'thread_id',
        'top_connection',
        'bottom_connection',
        'created_by',
        'updated_by',
    ];

    protected $casts = [
        'created_at' => 'datetime',
        'updated_at' => 'datetime',
        'deleted_at' => 'datetime',
    ];

    public function thread()
    {
        return $this->belongsTo(ThreadModel::class, 'thread_id');
    }
}
