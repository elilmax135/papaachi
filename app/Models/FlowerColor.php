<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class FlowerColor extends Model
{       // Set the table name if it does not follow the default naming convention
    protected $table = 'flower_color';

    // Set the primary key if it does not follow the default 'id'
    protected $primaryKey = 'flower_color_id';

    // Specify which attributes are mass assignable
    protected $fillable = ['flower_color_name'];
    use HasFactory;
}
