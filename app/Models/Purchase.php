<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Purchase extends Model
{
    protected $table = 'purchase';
    protected $fillable = [
        'purchase_id',
        'supplier_name',
        'purchase_date',
        'transaction_id',
        'branch',
        'purchase_status',
        'total'
    ];

    public function products()
    {
        return $this->belongsToMany(Product::class)->withPivot('stock_quantity', 'purchase_price', 'selling_price');
    }


    use HasFactory;
}
