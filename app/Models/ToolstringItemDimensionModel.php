<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class ToolstringItemDimensionModel extends Model
{
    use SoftDeletes;

    protected $table = 'toolstring_item_dimensions';
    public $timestamps = false;
    protected $fillable = [
        'toolstring_item_id',
        'outer_diameter',
        'outer_diameter_unit',
        'inner_diameter',
        'inner_diameter_unit',
        'length',
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

    public function toolstringItem()
    {
        return $this->belongsTo(ToolstringItemModel::class, 'toolstring_item_id');
    }
}
