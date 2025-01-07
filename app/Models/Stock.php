<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Stock extends Model
{

    protected $table = 'stock';

    // Define the fillable fields for mass assignment
    protected $fillable = [
        'product_id',
        'customer_id',
        'product_name',
        'quantity',
    ];
    public function product()
    {
        return $this->belongsTo(Product::class);
    }
    public function customer()
    {
        return $this->belongsTo(customer::class);
    }
    use HasFactory;
}
