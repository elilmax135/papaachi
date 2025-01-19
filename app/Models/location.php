<?php

namespace App\Models;

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Location extends Model
{
    use HasFactory;

    protected $table = 'locations'; // Table name
    protected $fillable = ['total_distance'];

    public function sell()
    {
        return $this->belongsTo(Sell::class);
    }
}
