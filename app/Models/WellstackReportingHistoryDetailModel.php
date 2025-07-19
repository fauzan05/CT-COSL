<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class WellstackReportingHistoryDetailModel extends Model
{
    use SoftDeletes;

    protected $table = 'wellstack_reporting_history_details';
    public $timestamps = false;
    protected $fillable = [
        'wellstack_reporting_history_id',
        'wellstack_type_id',
        'wellstack_item_id',
        'position',
        'created_by',
        'updated_by'
    ];

    protected $casts = [
        'created_at' => 'datetime',
        'updated_at' => 'datetime',
        'deleted_at' => 'datetime'
    ];

    public function updatedByUser()
    {
        return $this->belongsTo(User::class, 'updated_by');
    }

    public function createdByUser()
    {
        return $this->belongsTo(User::class, 'created_by');
    }

    public function item()
    {
        return $this->belongsTo(WellstackItemModel::class, 'wellstack_item_id');
    }

    public function type()
    {
        return $this->belongsTo(WellstackTypeModel::class, 'wellstack_type_id');
    }

    public function reportingHistory()
    {
        return $this->belongsTo(WellstackReportingHistoryModel::class, 'wellstack_reporting_history_id');
    }


}
