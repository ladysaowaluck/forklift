<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Warehouse extends Model
{
    use HasFactory;

    protected $primaryKey = 'warehouse_id'; 
    protected $keyType = 'int'; 

    protected $fillable = [
        'name', 'location', 'status',
    ];

    protected $casts = [
        'location' => 'string',
    ];
}
