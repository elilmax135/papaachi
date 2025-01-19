<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class SellProduct extends Model
{

    protected $table = 'sell_product';
    protected $fillable = [
        'sell_id', 'product_id', 'quantity', 'purchase_price', 'selling_price', 'subtotal'
    ];

    public function sell()
    {
        return $this->belongsTo(Sell::class);
    }

    public function product()
    {
        return $this->belongsTo(Product::class);
    }
}

