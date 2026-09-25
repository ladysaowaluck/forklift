<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Driver extends Model
{
    // กำหนด primary key เป็น 'driver_id'
    protected $primaryKey = 'driver_id';
    
    // กำหนดให้ไม่ใช้ auto-increment (ถ้าไม่ใช่)
    public $incrementing = false;

    protected $fillable = ['user_id', 'name', 'license_no', 'status'];

    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
