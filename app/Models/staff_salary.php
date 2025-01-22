<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class staff_salary extends Model
{ protected $table = 'staff_salary'; // Explicitly defining table name

    protected $fillable = ['staff_id', 'payment'];


    use HasFactory;
}
