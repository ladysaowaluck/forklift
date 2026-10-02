<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Transaction extends Model
{
    use HasFactory;

    protected $table = 'transactions';
    protected $primaryKey = 'transaction_id';
    public $incrementing = true;
    protected $keyType = 'int';

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'forklift_id', 
        'driver_id', 
        'warehouse_from', 
        'warehouse_to', 
        // 'created_by_user_id',
        'status',
        'driver_rating',
        'driver_comment',
        'start_task_image_path',
        'end_task_image_path',   
        'claimed_at',
        'started_at',
        'arrived_at',
        'completed_at',
        'rejection_reason',
        'rejected_at',
        'rejected_by_driver_id',
    ];

    /**
     * The attributes that should be cast.
     * This tells Laravel to treat these columns as date/time objects.
     *
     * @var array<string, string>
     */
    protected $casts = [
        'claimed_at' => 'datetime',
        'started_at' => 'datetime',
        'arrived_at' => 'datetime',
        'completed_at' => 'datetime',
    ];

    public function forklift()
    {
        return $this->belongsTo(Forklift::class, 'forklift_id');
    }

    public function driver()
    {
        return $this->belongsTo(Driver::class, 'driver_id');
    }

    public function warehouseFrom()
    {
        return $this->belongsTo(Warehouse::class, 'warehouse_from', 'warehouse_id');
    }

    public function warehouseTo()
    {
        return $this->belongsTo(Warehouse::class, 'warehouse_to', 'warehouse_id');
    }

    public function details()
    {
        return $this->hasMany(TransactionDetail::class, 'transaction_id');
    }
    
    // public function creator()
    // {
    //     return $this->belongsTo(User::class, 'created_by_user_id');
    // }
    public function rejectedBy()
    {
        return $this->belongsTo(Driver::class, 'rejected_by_driver_id', 'driver_id');
    }
}
