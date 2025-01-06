<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class BoxType extends Model
{
    protected $table = 'box_type';

    // Set the primary key if it's not using the default 'id'
    protected $primaryKey = 'box_type_id';

    // Specify which attributes are mass assignable
    protected $fillable = ['box_type_name'];
    use HasFactory;
}
