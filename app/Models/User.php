<?php

namespace App\Models;

use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use App\Models\Driver; 
class User extends Authenticatable
{
    use HasFactory, Notifiable;

    protected $fillable = ['name', 'email', 'password', 'role', 'warehouse_id']; 

    protected $hidden = ['password', 'remember_token'];

    public function warehouse(): BelongsTo
    {
        return $this->belongsTo(Warehouse::class, 'warehouse_id', 'warehouse_id');
    }

    public function driver()
    {

        return $this->hasOne(Driver::class, 'user_id', 'id');
    }

    // Helper method to determine if the user has an admin role (case-insensitive)
    public function isAdmin(): bool
    {
        return strtolower($this->role ?? '') === 'admin';
    }
}
