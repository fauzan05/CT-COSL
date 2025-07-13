<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class WellstackReportingHistoryDetailModel extends Model
{
    use SoftDeletes;

    protected $table = 'wellstack_reporting_history_details';
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

    public function updatedBy()
    {
        return $this->belongsTo(User::class, 'updated_by');
    }
}
