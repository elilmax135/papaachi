<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class sell extends Model
{
    use HasFactory;
    protected $table = 'sells';
    protected $primaryKey = 'id';
    protected $fillable = [
        'customer_name',
        'customer_mobile',
        'sell_date',
        'transaction_id',
        'transport_mode',
        'customer_address',
        'doctor_confirm',
        'branch_id',
        'total',
        'sell_status',
        'empoming_amount',
        'emapoming_date',
        'panthal_amount',
        'lift_amount',
        'band_amount',
        'transport_amount',
        'melam_amount'
    ];

    public function sellproducts()
    {
        return $this->hasMany(sellProduct::class, 'sell_id');
    }
    public function locations()
    {
        return $this->hasMany(Location::class);
    }
    public function service()
    {
        return $this->belongsTo(Service::class, 'service_id', 'service_id_uniq');
    }
    // In Sale model
public function salary()
{
    return $this->hasMany(Salary::class); // Or belongsToMany if it's a many-to-many relationship
}
public function latestPayment()
{
    return $this->hasOne(SellPayment::class, 'sell_id')->latest();
}

public function latestSalary()
{
    return $this->hasOne(Salary::class, 'sells_id')->latest();
}

public function staff()
{
    return $this->belongsTo(Staff::class, 'staff_id');
}
}
