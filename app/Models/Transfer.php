<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Transfer extends Model
{
    protected $table = 'transfer';
    protected $fillable = [
        'transfer_date',
        'transaction_id',
        'branch_id',
        'total',
        'transfer_status',
    ];

    // Relationship with Branch
    public function branch()
    {
        return $this->belongsTo(Branch::class);
    }

    // Relationship with TransferProduct
    public function transferProducts()
    {
        return $this->hasMany(TransferProduct::class);
    }

    // Relationship with TransferPayment
    public function transferPayments()
    {
        return $this->hasMany(TransferPayment::class);
    }
    use HasFactory;
}
