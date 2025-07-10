<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class ToolstringReportingHistoryModel extends Model
{
    use SoftDeletes;
    protected $table = 'toolstring_reporting_histories';
    protected $fillable = [
        'name',
        'title',
        'client',
        'well',
        'date',
        'created_by',
        'updated_by',
    ];
    
    protected $casts = [
        'created_at' => 'datetime',
        'updated_at' => 'datetime',
    ];

    public function updatedByUser()
    {
        return $this->belongsTo(User::class, 'updated_by');
    }

}
