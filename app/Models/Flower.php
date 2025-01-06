<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Flower extends Model
{
    protected $table = 'flower_info';
     protected $fillable = ['flower_name','flower_image','fw_color_id', 'price_purchase', 'price_selling'];

    // Define the inverse relationship with the product

}
