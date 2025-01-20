<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class TransferPayment extends Model
{

    protected $table = 'transfer_payment';
    protected $primaryKey = 'transfer_pay_id';
    protected $fillable = [
        'transfer_id',
        'payment_method',
        'check_number',
        'bank_name',
        'transection_id',
        'payment_platform',
        'payment_date',
        'transfer_total',
        'pay_amount',
        'pay_due',
    ];

    // Relationship with Transfer
    public function transfer()
    {
        return $this->belongsTo(Transfer::class, 'transfer_id', 'id');
    }
    use HasFactory;
}
