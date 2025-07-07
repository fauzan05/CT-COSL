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
        'manufacturer',
        'outer_diameter',
        'inner_diameter',
        'length',
        'comment',
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
}
