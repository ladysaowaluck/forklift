<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class WarehouseRoute extends Model
{
    use HasFactory;

    protected $table = 'routes';
    protected $primaryKey = 'route_id';

    public $timestamps = true;

    protected $fillable = [
        'origin_warehouse_id',
        'dest_warehouse_id',
        'distance',
    ];

    protected $casts = [
        'distance' => 'float',
    ];

    public function originWarehouse()
    {
        return $this->belongsTo(Warehouse::class, 'origin_warehouse_id', 'warehouse_id');
    }

    public function destWarehouse()
    {
        return $this->belongsTo(Warehouse::class, 'dest_warehouse_id', 'warehouse_id');
    }

    public function getCo2ReductionAttribute(): float
    {
        return round($this->distance * 1.20, 2);
    }
}
