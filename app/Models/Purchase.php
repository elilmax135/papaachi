<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Purchase extends Model
{
    protected $table = 'purchase';
    protected $primaryKey = 'purchase_id';
    public $incrementing = true; // Set true if the key is auto-increment
    protected $keyType = 'int';
    protected $fillable = [

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
    public function productPurchases()
    {
        return $this->hasMany(ProductPurchase::class);
    }

    use HasFactory;
}
