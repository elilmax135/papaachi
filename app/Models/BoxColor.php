<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class BoxColor extends Model
{

         // Set the table name if it's not following the default naming convention
    protected $table = 'box_color';

    // Set the primary key if it's not using the default 'id'
    protected $primaryKey = 'box_color_id';

    // Specify which attributes are mass assignable
    protected $fillable = ['box_color_name'];
    use HasFactory;
}
