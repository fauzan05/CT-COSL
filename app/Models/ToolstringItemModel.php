<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class ToolstringItemModel extends Model
{
    use SoftDeletes;

    protected $table = 'toolstring_items';

    protected $fillable = [
        'toolstring_category_id',
        'name',
        'description',
        'image',
        'thread_id',
        'thread_size_id',
        'created_by',
        'updated_by',
    ];

    protected $casts = [
        'created_at' => 'datetime',
        'updated_at' => 'datetime',
    ];

    public function toolstringCategory()
    {
        return $this->belongsTo(ToolstringCategoryModel::class, 'toolstring_category_id');
    }

    // ToolstringItemModel.php
    public function updatedByUser()
    {
        return $this->belongsTo(User::class, 'updated_by');
    }

    public function createdByUser()
    {
        return $this->belongsTo(User::class, 'created_by');
    }

    public function thread()
    {
        return $this->belongsTo(ThreadModel::class, 'thread_id');
    }

    public function threadSize()
    {
        return $this->belongsTo(ThreadSizeModel::class, 'thread_size_id');
    }
}
