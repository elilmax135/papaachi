<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class sellPayment extends Model
{
    protected $table = 'sell_payment';
    protected $primaryKey = 'sell_pay_id';
    protected $fillable = [

        'sell_id',
        'payment_method',
        'check_number',
        'bank_name',
        'transection_id',
        'payment_platform',
        'payment_date',
        'sell_total', // Ensure this matches the database column name
        'pay_amount',
        'pay_due',
    ];
    use HasFactory;
}
