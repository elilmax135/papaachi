<?php

namespace App\Models;
use App\Models\Box;
use App\Models\Flower;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Product extends Model
{
    protected $table = 'product';
    protected $primaryKey = 'product_id'; // Specify the primary key

    public $incrementing = false; // If `product_id` is not auto-incrementing
    protected $keyType = 'string';
        protected $fillable = ['product_id', 'product_name', 'product_image','product_type','product_boxtype_id', 'color_id', 'price_purchase', 'price_selling'];

    // Define the relationship with flower_info
    public function purchases()
    {
        return $this->belongsToMany(Purchase::class)->withPivot('stock_quantity', 'purchase_price', 'selling_price');
    }
    public function productPurchases()
{
    return $this->hasMany(ProductPurchase::class);
}

    use HasFactory;
}
