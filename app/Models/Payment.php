<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Payment extends Model
{

    protected $table = 'payment_info';
    protected $primaryKey = 'payment_id';
    protected $fillable = [

        'purchase_id',
        'payment_method',
        'check_number',
        'bank_name',
        'transection_id',
        'payment_platform',
        'payment_date',
        'purchase_total', // Ensure this matches the database column name
        'pay_amount',
        'pay_due',
    ];

    use HasFactory;
}
