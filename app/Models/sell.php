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
        'total',
        'sell_status',
        'service_id',
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
}
