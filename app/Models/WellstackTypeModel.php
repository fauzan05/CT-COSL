<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class WellstackTypeModel extends Model
{
    use SoftDeletes;

    protected $table = 'wellstack_types';

    protected $fillable = [
        'name',
        'slug',
        'created_by',
        'updated_by',
    ];

    protected $casts = [
        'created_at' => 'datetime',
        'updated_at' => 'datetime',
    ];   
    
    public function wellstackItems()
    {
        return $this->hasMany(WellstackItemModel::class, 'wellstack_type_id');
    }
}
