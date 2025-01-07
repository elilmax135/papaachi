<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class AvailableProduct extends Model
{

    protected $table = 'available_product';

    // Specify the fields that can be mass assigned (optional, but recommended for security)
    protected $fillable = [
        'product_name',
        'price',
    ];

    use HasFactory;
}
