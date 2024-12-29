<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Setting extends Model
{
    use HasFactory;

    protected $fillable =[
        'company_name',
        'email',
        'mobile',
        'logo',
        'favicon',
        'profile',
        'login_img',
        'desc',
        'tags',
        'solution',
        'main_color',
        'second_color'
    ];
}
