<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class WellstackItemModel extends Model
{
    use SoftDeletes;

    protected $table = 'wellstack_items';
    public $timestamps = false;
    protected $fillable = [
        'wellstack_type_id',
        'name',
        'description',
        'serial_number',
        'image',
        'height',
        'heigth_unit',
        'weight',
        'weight_unit',
        'pressure_rating',
        'pressure_rating_unit',
        'owner',
        'shear_ram_dist_from_bottom',
        'shear_ram_dist_from_bottom_unit',
        'created_at',
        'created_by',
        'updated_at',
        'updated_by',
    ];

    protected $casts = [
        'created_at' => 'datetime',
        'updated_at' => 'datetime',
        'deleted_at' => 'datetime',
    ];

    public function wellstackType()
    {
        return $this->belongsTo(WellstackTypeModel::class, 'wellstack_type_id');
    }

    public function updatedByUser()
    {
        return $this->belongsTo(User::class, 'updated_by');
    }
}
