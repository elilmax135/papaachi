<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class BoxStock extends Model
{


    protected $fillable = [
        'stock_name',

        'price',
        'quantity',
        'box_id'
    ];
    use HasFactory;
}
