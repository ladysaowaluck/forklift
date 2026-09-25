<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class TransactionDetail extends Model
{
    use HasFactory;

    protected $table = 'transaction_details'; 
    protected $primaryKey = 'detail_id';
    public $incrementing = true;
    protected $keyType = 'int';

    protected $fillable = [
        'transaction_id', 'item_name', 'quantity', 'unit','description'
    ];

    public function transaction()
    {
        return $this->belongsTo(Transaction::class, 'transaction_id');
    }
}
