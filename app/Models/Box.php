<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Box extends Model
{
    protected $table = 'box_info';
    protected $primaryKey = 'box_unique_id';
    protected $fillable = ['box_name', 'box_image', 'bx_color_id','bx_type_id', 'price_purchase', 'price_selling'];

    // Define the inverse relationship with the product

    use HasFactory;
}
