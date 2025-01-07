<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class customer extends Model
{

    protected $table = 'customer';
    protected $primaryKey = 'id';
    // Define the fillable columns (you can also use $guarded to block specific fields)
    protected $fillable = ['name'];

    use HasFactory;
}
