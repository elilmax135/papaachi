<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ProductPurchase extends Model
{
    use HasFactory;

    // Define the table associated with the model
    protected $table = 'product_purchases';

    // Specify the columns that are mass assignable
    protected $fillable = [
        'product_id',
        'purchase_id',
        'product_name',
        'quantity',
        'purchase_price',
        'selling_price',
    ];

    // If needed, you can define relationships here (e.g., to 'products', 'suppliers', etc.)
}
