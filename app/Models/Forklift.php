<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Forklift extends Model
{
    use HasFactory;
    protected $table = 'forklifts';
    protected $primaryKey = 'forklift_id';

    public $timestamps = true;

    protected $fillable = [
        'model', 'plate_number', 'status',
    ];

    protected $casts = [
        'status' => 'string',
    ];
}
