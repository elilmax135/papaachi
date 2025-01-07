<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Purchase extends Model
{
    protected $table = 'purchase';

    // Define the primary key if it's not the default 'id'
    protected $primaryKey = 'purchase_id';

    // Disable auto-incrementing if using a custom primary key
    public $incrementing = true;

    // Define the columns that can be mass-assigned
    protected $fillable = [
        'purchase_product_id',
        'purchase_product_name',
        'quantity',
        'p_total_amount',
        'purchase_date',
        'location_id'
    ];
    use HasFactory;
}
