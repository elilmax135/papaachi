<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class salary extends Model
{

    protected $table = 'salary';
    protected $fillable = ['staff_id', 'payment', 'paid','due','salary_status', 'payment_date', 'sells_id'];

    public function staff()
    {
        return $this->belongsTo(Staff::class, 'staff_id');
    }

    public function sell()
    {
        return $this->belongsTo(Sell::class, 'sells_id');
    }
    use HasFactory;
}
