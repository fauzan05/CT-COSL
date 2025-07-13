<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class ToolstringReportingHistoryDetailModel extends Model
{
    use SoftDeletes;
    protected $table = 'toolstring_reporting_history_details';
    protected $fillable = [
        'toolstring_reporting_history_id',
        'toolstring_type_id',
        'toolstring_item_id',
        'toolstring_item_dimension_id',
        'position',
        'created_by',
        'updated_by'
    ];
    protected $casts = [
        'created_at' => 'datetime',
        'updated_at' => 'datetime',
        'deleted_at' => 'datetime'
    ];

    public function reportingHistory()
    {
        return $this->belongsTo(ToolstringReportingHistoryModel::class, 'toolstring_reporting_history_id');
    }

    public function type()
    {
        return $this->belongsTo(ToolstringTypeModel::class, 'toolstring_type_id');
    }

    public function item()
    {
        return $this->belongsTo(ToolstringItemModel::class, 'toolstring_item_id');
    }

    public function dimension()
    {
        return $this->belongsTo(ToolstringItemDimensionModel::class, 'toolstring_item_dimension_id');
    }
}
