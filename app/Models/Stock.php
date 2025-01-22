<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Stock extends Model {
    use HasFactory;
    protected $table = 'stocks';
    protected $fillable = [
        'branch_id',
        'product_id',
        'branch_name',
        'product_name',
        'total_quantity',
        'product_type',
        'selling_price'
    ];

    public function branch() {
        return $this->belongsTo(Branch::class, 'branch_id');
    }

    public function product() {
        return $this->belongsTo(Product::class, 'product_id');
    }

}
