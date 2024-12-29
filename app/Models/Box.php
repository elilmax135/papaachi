<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Box extends Model
{  protected $table = 'box_info';


    protected $fillable = [
        'box_name',
        'box_image',
        'box_type',
        'price',
        'size',
        'color',
        'quantity',
    ];
    use HasFactory;
}
