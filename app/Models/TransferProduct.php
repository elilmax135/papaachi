<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class TransferProduct extends Model
{

    protected $table = 'transfer_product';
    protected $fillable = [
        'transfer_id',
        'product_id',
        'quantity',
        'purchase_price',
        'selling_price',
        'subtotal',
    ];

    // Relationship with Transfer
    public function transfer()
    {
        return $this->belongsTo(Transfer::class);
    }

    // Relationship with Product
    public function product()
    {
        return $this->belongsTo(Product::class, 'product_id');
    }
    use HasFactory;
}
