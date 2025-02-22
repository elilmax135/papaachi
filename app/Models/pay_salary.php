<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class pay_salary extends Model
{

    use HasFactory;
    protected $table = 'pay_salary';
    protected $fillable = ['staff_id', 'payment', 'paid','salary_status', 'payment_date'];

    public function staff()
    {
        return $this->belongsTo(Staff::class, 'staff_id');
    }



}
