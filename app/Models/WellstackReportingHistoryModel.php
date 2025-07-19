<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class WellstackReportingHistoryModel extends Model
{
    use SoftDeletes;

    protected $table = 'wellstack_reporting_histories';
    public $timestamps = false;
    protected $fillable = [
        'name',
        'client',
        'field',
        'well_name_number',
        'min_restriction',
        'kop',
        'category',
        'bhp',
        'bhst',
        'so',
        'supplier',
        'date_drawn',
        'drawn_by',
        'created_by',
        'updated_by'
    ];

    protected $casts = [
        'created_at' => 'datetime',
        'updated_at' => 'datetime',
        'deleted_at' => 'datetime',
        'date_drawn' => 'date'
    ];

    public function details()
    {
        return $this->hasMany(WellstackReportingHistoryDetailModel::class, 'wellstack_reporting_history_id');
    }

    public function createdByUser()
    {
        return $this->belongsTo(User::class, 'created_by');
    }

    public function updatedByUser()
    {
        return $this->belongsTo(User::class, 'updated_by');
    }
}
