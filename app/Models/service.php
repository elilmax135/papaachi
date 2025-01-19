<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class service extends Model
{ protected $table = 'service'; // Table name

    protected $primaryKey = 'service_id_uniq'; // Primary key

    public $incrementing = true; // Ensure auto-increment is enabled for this key
    protected $keyType = 'int';

    protected $fillable = ['service_name'];

    // Define the one-to-many relationship with Sell
    public function sells()
    {
        return $this->hasMany(Sell::class, 'service_id', 'service_id_uniq');
    }
    use HasFactory;
}
