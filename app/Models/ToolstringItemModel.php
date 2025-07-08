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
        'outer_diameter',
        'inner_diameter',
        'length',
        'outer_diameter_unit',
        'inner_diameter_unit',
        'length_unit',
        'created_by',
        'updated_by',
    ];

    protected $casts = [
        'outer_diameter' => 'decimal:2',
        'inner_diameter' => 'decimal:2',
        'length' => 'decimal:2',
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
}
